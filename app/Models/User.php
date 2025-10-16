<?php
class User {
    private $pdo;
    private $table = "users";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($nama_lengkap, $username, $email, $password, $peran = 'siswa', $kelas = null, $nip_nis = null) {
        // Cek username atau email sudah ada
        $stmt = $this->pdo->prepare("SELECT id_user FROM {$this->table} WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Username atau email sudah digunakan.'];
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nama_lengkap, username, email, password_hash, peran, kelas, nip_nis) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$nama_lengkap, $username, $email, $hashed, $peran, $kelas, $nip_nis]);

        return $result 
            ? ['success' => true, 'message' => 'Registrasi berhasil.'] 
            : ['success' => false, 'message' => 'Gagal menyimpan data pengguna.'];
    }

    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Username atau password salah.'];
        }

        return ['success' => true, 'user' => $user];
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_user = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT id_user, nama_lengkap, username, email, peran, kelas, nip_nis, status_aktif FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByRole($peran) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE peran = ?");
        $stmt->execute([$peran]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}