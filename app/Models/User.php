<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class User {
    private $pdo;
    private $table = "users";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function setPeran(int $id_user, string $peran, ?string $wali_kelas = null): bool {
        try {
            $sql = "UPDATE {$this->table} SET peran = :peran";

            // Jika peran guru, update wali_kelas juga
            if ($peran === 'guru' && $wali_kelas) {
                $sql .= ", wali_kelas = :wali_kelas";
            }

            $sql .= " WHERE id_user = :id_user";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':peran', $peran, PDO::PARAM_STR);
            $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);

            if ($peran === 'guru' && $wali_kelas) {
                $stmt->bindValue(':wali_kelas', $wali_kelas, PDO::PARAM_STR);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Gagal setPeran: " . $e->getMessage());
            return false;
        }
    }

    /** REGISTER USER BARU **/
    public function register($nama_lengkap, $email, $password, $peran = 'siswa', $kelas = null, $nip_nis = null) {
        // Cek apakah email sudah digunakan
        $stmt = $this->pdo->prepare("SELECT id_user FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Email sudah digunakan.'];
        }

        // Hash password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Simpan user baru - TANPA status_aktif
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table} 
            (nama_lengkap, email, password_hash, peran, kelas, nip_nis) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $result = $stmt->execute([
            $nama_lengkap, 
            $email, 
            $hashed, 
            $peran, 
            $kelas, 
            $nip_nis
        ]);

        return $result 
            ? ['success' => true, 'message' => 'Registrasi berhasil.'] 
            : ['success' => false, 'message' => 'Gagal menyimpan data pengguna.'];
    }

    public function getSiswaByKelas($kelas) {
        $sql = "SELECT * FROM {$this->table} WHERE peran = 'siswa' AND kelas = :kelas";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':kelas' => $kelas]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function importSiswaFromExcel(string $filePath): array {
        try {
            // Baca file Excel
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $imported = 0;
            $skipped = 0;
            $errors = [];
            $results = [];
            $counterKelas = [];

            // Lewati header baris pertama
            foreach (array_slice($rows, 1) as $rowNumber => $row) {
                $no = trim($row['A'] ?? '');
                $nama_lengkap = trim($row['B'] ?? '');
                $kelas = trim($row['C'] ?? '');
                $email_input = trim($row['D'] ?? '');

                // Validasi data wajib
                if (empty($nama_lengkap) || empty($kelas) || empty($email_input)) {
                    $skipped++;
                    $errors[] = "Baris " . ($rowNumber + 1) . ": Data wajib (nama/kelas/email) kosong.";
                    continue;
                }

                // Format email
                $email = $this->formatEmail($email_input);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $skipped++;
                    $errors[] = "Baris " . ($rowNumber + 1) . ": Format email '$email_input' tidak valid.";
                    continue;
                }

                if ($this->isEmailExist($email)) {
                    $skipped++;
                    $errors[] = "Baris " . ($rowNumber + 1) . ": Email '$email' sudah terdaftar.";
                    continue;
                }

                $kelas = $this->formatKelas($kelas);
                $password_default = $this->generatePasswordFromKelas($kelas, $counterKelas);

                // Insert TANPA status_aktif
                $stmt = $this->pdo->prepare("
                    INSERT INTO {$this->table} 
                    (nama_lengkap, email, password_hash, peran, kelas)
                    VALUES (:nama_lengkap, :email, :password_hash, 'siswa', :kelas)
                ");
                
                $result = $stmt->execute([
                    ':nama_lengkap' => $nama_lengkap,
                    ':email' => $email,
                    ':password_hash' => password_hash($password_default, PASSWORD_DEFAULT),
                    ':kelas' => $kelas
                ]);

                if ($result) {
                    $imported++;
                    $results[] = [
                        'nama' => $nama_lengkap,
                        'email' => $email,
                        'password' => $password_default,
                        'kelas' => $kelas
                    ];
                } else {
                    $skipped++;
                    $errorInfo = $stmt->errorInfo();
                    $errors[] = "Baris " . ($rowNumber + 1) . ": Gagal menyimpan. Error: " . $errorInfo[2];
                }
            }

            return [
                'success' => true,
                'message' => "Import selesai. Berhasil: {$imported}, dilewati: {$skipped}.",
                'imported' => $imported,
                'skipped' => $skipped,
                'errors' => $errors,
                'results' => $results
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membaca file Excel: ' . $e->getMessage(),
                'imported' => 0,
                'skipped' => 0,
                'errors' => [],
                'results' => []
            ];
        }
    }

    /**
     * Format email - handle @username format
     */
    private function formatEmail(string $email): string {
        // Jika email dimulai dengan @, tambahkan @gmail.com
        if (strpos($email, '@') === 0) {
            return substr($email, 1) . '@gmail.com';
        }
        
        // Jika tidak ada @ sama sekali, tambahkan @gmail.com
        if (strpos($email, '@') === false) {
            return $email . '@gmail.com';
        }
        
        // Email sudah valid
        return $email;
    }

    /**
     * Format kelas - standardisasi format
     */
   private function formatKelas(string $kelas): string {
    $kelas = strtoupper(trim($kelas));

    // Jika diawali dengan "KELAS", hapus kata itu
    $kelas = preg_replace('/^KELAS\s*/i', '', $kelas);

    // Jika formatnya seperti "7A" tanpa spasi, tambahkan spasi antara angka dan huruf
    if (preg_match('/^([0-9]+)([A-Z])$/i', $kelas, $matches)) {
        return "{$matches[1]} {$matches[2]}";
    }

    // Jika sudah memiliki spasi seperti "7 A", biarkan saja
    return $kelas;
}


    /**
     * Generate password berdasarkan kelas (format: 7A123)
     * 2 karakter pertama: kode kelas (7A, 8B, dll)
     * 3 karakter berikutnya: nomor urut per kelas (001, 002, dst.)
     */
   private function generatePasswordFromKelas(string $kelas): string {
    // Coba ambil pola "KELAS 7A" → hasil "7A"
    if (preg_match('/KELAS\s+([0-9]+[A-Z])/i', $kelas, $matches)) {
        return strtoupper($matches[1]);
    }

    // Kalau sudah dalam bentuk "7A" langsung kembalikan
    if (preg_match('/^[0-9]+[A-Z]$/i', $kelas)) {
        return strtoupper($kelas);
    }

    // Fallback: bersihkan karakter dan ambil 2 pertama
    $cleaned = preg_replace('/[^0-9A-Z]/i', '', $kelas);
    return strtoupper(substr($cleaned, 0, 2));
}


    public function editUser($id_user, $data) {
        if (empty($id_user) || !is_numeric($id_user)) {
            return ['success' => false, 'message' => 'ID pengguna tidak valid.'];
        }

        $fields = [];
        $params = [];

        // Field yang boleh diubah - HAPUS status_aktif
        $allowedFields = [
            'nama_lengkap', 'email', 'wali_kelas',
            'peran', 'kelas', 'nip_nis', 'password'
        ];

        // Ambil field valid
        foreach ($allowedFields as $field) {
            if (isset($data[$field]) && $data[$field] !== '') {
                // 🔐 Jika field password, hash dulu dan simpan ke kolom password_hash
                if ($field === 'password') {
                    $fields[] = "password_hash = :password_hash";
                    $params[':password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
                } else {
                    $fields[] = "$field = :$field";
                    $params[":$field"] = trim($data[$field]);
                }
            }
        }

        if (empty($fields)) {
            return ['success' => false, 'message' => 'Tidak ada data yang diupdate.'];
        }

        // ✅ Cek email unik jika diubah
        if (isset($data['email'])) {
            $checkEmail = $this->pdo->prepare("
                SELECT COUNT(*) FROM {$this->table} 
                WHERE email = :email AND id_user != :id_user
            ");
            $checkEmail->execute([
                ':email' => $data['email'],
                ':id_user' => $id_user
            ]);
            if ($checkEmail->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'Email sudah digunakan oleh pengguna lain.'];
            }
        }

        // ✅ Cek NIP/NIS unik jika diubah
        if (isset($data['nip_nis'])) {
            $checkNipNis = $this->pdo->prepare("
                SELECT COUNT(*) FROM {$this->table} 
                WHERE nip_nis = :nip_nis AND id_user != :id_user
            ");
            $checkNipNis->execute([
                ':nip_nis' => $data['nip_nis'],
                ':id_user' => $id_user
            ]);
            if ($checkNipNis->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'NIP/NIS sudah digunakan oleh pengguna lain.'];
            }
        }

        // ✅ Jalankan query update
        $params[':id_user'] = $id_user;
        $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . " WHERE id_user = :id_user";
        $stmt = $this->pdo->prepare($sql);

        try {
            $result = $stmt->execute($params);
            return $result
                ? ['success' => true, 'message' => 'Data pengguna berhasil diperbarui.']
                : ['success' => false, 'message' => 'Gagal memperbarui data pengguna.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Kesalahan database: ' . $e->getMessage()];
        }
    }

    public function loginByEmail($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return [
                'success' => true,
                'user' => [
                    'id_user'     => $user['id_user'],
                    'nama_lengkap'=> $user['nama_lengkap'],
                    'email'       => $user['email'],
                    'peran'       => $user['peran'],
                    'kelas'       => $user['kelas'],        // untuk siswa
                    'wali_kelas'  => $user['wali_kelas'],   // untuk guru
                    'nip_nis'     => $user['nip_nis'],
                    'foto_profil' => $user['foto_profil']
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Email atau password salah.'
            ];
        }
    }

    /** CARI USER BERDASARKAN ID **/
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_user = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** AMBIL SEMUA USER **/
    public function all() {
        $stmt = $this->pdo->query("
            SELECT id_user, nama_lengkap, email, peran, wali_kelas, kelas, nip_nis 
            FROM {$this->table}
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** AMBIL USER BERDASARKAN ROLE **/
    public function findByRole($peran) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE peran = ?");
        $stmt->execute([$peran]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM {$this->table}");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

   public function deleteUser($id_user)
{
    try {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_user = ?");
        $result = $stmt->execute([$id_user]);

        if (!$result) {
            $errorInfo = $stmt->errorInfo();
            return [
                'success' => false,
                'message' => 'Gagal menghapus pengguna: ' . ($errorInfo[2] ?? 'Tidak diketahui.')
            ];
        }

        // 🔍 Cek apakah ada baris yang benar-benar terhapus
        if ($stmt->rowCount() === 0) {
            return [
                'success' => false,
                'message' => 'Tidak ada pengguna yang dihapus. ID mungkin tidak ditemukan.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Pengguna berhasil dihapus.'
        ];

    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'PDO Error: ' . $e->getMessage()
        ];
    }
}

    public function countByRole() {
        $stmt = $this->pdo->query("
            SELECT peran, COUNT(*) AS jumlah
            FROM {$this->table}
            GROUP BY peran
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($keyword) {
        $sql = "
            SELECT * FROM {$this->table}
            WHERE nama_lengkap LIKE :kw1
               OR email LIKE :kw2
               OR peran LIKE :kw3
            ORDER BY nama_lengkap ASC
        ";

        $stmt = $this->pdo->prepare($sql);

        $like = "%{$keyword}%";
        $stmt->bindParam(':kw1', $like, PDO::PARAM_STR);
        $stmt->bindParam(':kw2', $like, PDO::PARAM_STR);
        $stmt->bindParam(':kw3', $like, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isEmailExist(string $email): bool {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} WHERE email = :email");
            $stmt->execute([':email' => $email]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Gagal cek email exist: " . $e->getMessage());
            return false;
        }
    }

    public function isNipNisExist(string $nip_nis): bool {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} WHERE nip_nis = :nip_nis");
            $stmt->execute([':nip_nis' => $nip_nis]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Gagal cek nip/nis exist: " . $e->getMessage());
            return false;
        }
    }
}