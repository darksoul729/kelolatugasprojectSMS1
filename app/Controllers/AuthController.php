<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $pdo;
    private $userModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    // === HALAMAN REGISTER ===
    public function showRegister() {
        $error = '';
        include __DIR__ . '/../../resources/views/auth/register.php';
    }

    // === PROSES REGISTER ===
    public function doRegister() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if (empty($nama_lengkap) || empty($username) || empty($password) || empty($confirm_password)) {
                $error = "Semua field harus diisi.";
            } elseif ($password !== $confirm_password) {
                $error = "Password tidak cocok.";
            } elseif (strlen($password) < 6) {
                $error = "Password minimal 6 karakter.";
            } else {
                $result = $this->userModel->register($nama_lengkap, $username, $password, 'siswa');

                if ($result['success']) {
                   
                    header("Location: /routes/web.php?route=auth/login&registered=1");
                    exit;
                } else {
                    $error = $result['message'];
                }
            }
        }

        include __DIR__ . '/../../resources/views/auth/register.php';
    }

    // === HALAMAN LOGIN ===
    public function showLogin() {
        $error = '';
        include __DIR__ . '/../../resources/views/auth/login.php';
    }

    // === PROSES LOGIN ===
    public function doLogin() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            if (empty($username) || empty($password)) {
                $error = "Masukkan username dan password.";
            } else {
                $result = $this->userModel->login($username, $password);

                if ($result['success']) {
                    $user = $result['user'];

                    session_start();
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'siswa') {
                        header("Location: /routes/web.php?route=tugas/list");
                    } else {
                        header("Location: /routes/web.php?route=admin/dashboard");
                    }
                    exit;
                } else {
                    $error = $result['message'];
                }
            }
        }

        include __DIR__ . '/../../resources/views/auth/login.php';
    }

    // === LOGOUT ===
    public function logout() {
        session_start();
        session_destroy();
        header("Location: /routes/web.php?route=auth/login");
        exit;
    }

    // === MIDDLEWARE (ROLE PROTECTION) ===
    public function requireRole($role) {
        session_start();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            header("Location: /routes/web.php?route=auth/login");
            exit;
        }
    }
}
