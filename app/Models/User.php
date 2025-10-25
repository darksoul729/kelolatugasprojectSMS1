<?php
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

        // Simpan user baru
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table} 
            (nama_lengkap,email, password_hash, peran, kelas, nip_nis) 
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




public function editUser($id_user, $data) {
    $fields = [];
    $params = [];

    if (isset($data['nama_lengkap'])) {
        $fields[] = "nama_lengkap = :nama_lengkap";
        $params[':nama_lengkap'] = $data['nama_lengkap'];
    }
    if (isset($data['email'])) {
        $fields[] = "email = :email";
        $params[':email'] = $data['email'];
    }

    if (isset($data['wali_kelas'])) {
        $fields[] = "wali_kelas = :wali_kelas";
        $params[':wali_kelas'] = $data['wali_kelas'];
    }
    if (isset($data['peran'])) {
        $fields[] = "peran = :peran";
        $params[':peran'] = $data['peran'];
    }
    if (isset($data['kelas'])) {
        $fields[] = "kelas = :kelas";
        $params[':kelas'] = $data['kelas'];
    }
    if (isset($data['nip_nis'])) {
        $fields[] = "nip_nis = :nip_nis";
        $params[':nip_nis'] = $data['nip_nis'];
    }
    if (isset($data['status_aktif'])) {
        $fields[] = "status_aktif = :status_aktif";
        $params[':status_aktif'] = $data['status_aktif'];
    }

    if (empty($fields)) {
        return ['success' => false, 'message' => 'Tidak ada data yang diupdate.'];
    }

    $params[':id_user'] = $id_user;
    $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . " WHERE id_user = :id_user";
    $stmt = $this->pdo->prepare($sql);

    $result = $stmt->execute($params);

    return $result 
        ? ['success' => true, 'message' => 'Data pengguna berhasil diperbarui.'] 
        : ['success' => false, 'message' => 'Gagal memperbarui data pengguna.'];
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
            SELECT id_user, nama_lengkap, email, peran, wali_kelas, kelas, nip_nis, status_aktif 
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

public function deleteUser($id_user) {
    $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_user = ?");
    $result = $stmt->execute([$id_user]);

    return $result 
        ? ['success' => true, 'message' => 'Pengguna berhasil dihapus.'] 
        : ['success' => false, 'message' => 'Gagal menghapus pengguna.'];
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
}