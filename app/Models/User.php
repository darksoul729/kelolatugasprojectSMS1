<?php
class User {
    private $pdo;
    private $table = "users";

    public function __construct($pdo) {
        $this->pdo = $pdo;
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

    /** LOGIN PAKAI EMAIL SAJA **/
    public function loginByEmail($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = ? AND status_aktif = 1 LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Email tidak ditemukan atau akun tidak aktif.'];
        }

        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Password salah.'];
        }

        return ['success' => true, 'user' => $user];
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
            SELECT id_user, nama_lengkap, email, peran, kelas, nip_nis, status_aktif 
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
