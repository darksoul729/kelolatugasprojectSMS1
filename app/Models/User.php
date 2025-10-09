<?php
class User {
    private $pdo;
    private $table = "users";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // === Menambah user baru (register) ===
    public function register($nama_lengkap, $username, $password, $role = 'siswa') {
        // Cek apakah username sudah ada
        $stmt = $this->pdo->prepare("SELECT user_id FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Username sudah digunakan.'];
        }

        // Hash password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Simpan user baru
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama_lengkap, $username, $hashed, $role]);

        return ['success' => true, 'message' => 'Registrasi berhasil.'];
    }

    // === Login user ===
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Username tidak ditemukan.'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Password salah.'];
        }

        return ['success' => true, 'user' => $user];
    }

    // === Ambil user berdasarkan ID ===
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // === Ambil semua user (opsional, misal untuk admin) ===
    public function all() {
        $stmt = $this->pdo->query("SELECT user_id, nama_lengkap, username, role, created_at FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
