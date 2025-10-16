<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $result = $this->userModel->login($username, $password);

            if ($result['success']) {
                $_SESSION['user'] = $result['user'];
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Login berhasil!'];

                switch ($result['user']['peran']) {
                    case 'admin': header("Location: ?route=admin/users"); break;
                    case 'guru': header("Location: ?route=guru/dashboard"); break;
                    case 'siswa': header("Location: ?route=murid/dashboard"); break;
                    default: header("Location: ?route=home");
                }
                exit;
            } else {
                $_SESSION['message'] = ['type' => 'danger', 'text' => $result['message']];
                header("Location: ?route=auth/login");
                exit;
            }
        } else {
            $this->showLogin();
        }
    }

    public function showLogin() {
        include __DIR__ . '/../../resources/views/auth/login.php';
    }

    public function showRegister() {
        include __DIR__ . '/../../resources/views/auth/register.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if ($password !== $confirm_password) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Password dan konfirmasi tidak cocok.'];
                header("Location: ?route=auth/register");
                exit;
            }

            $nama = trim($_POST['nama_lengkap'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $peran = $_POST['peran'] ?? 'siswa';
            $kelas = $_POST['kelas'] ?? null;
            $nip_nis = $_POST['nip_nis'] ?? null;

            if (empty($nama) || empty($username) || empty($email) || empty($password)) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Semua field wajib diisi.'];
                header("Location: ?route=auth/register");
                exit;
            }

            $result = $this->userModel->register($nama, $username, $email, $password, $peran, $kelas, $nip_nis);

            if ($result['success']) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Registrasi berhasil! Silakan login.'];
                header("Location: ?route=auth/login");
            } else {
                $_SESSION['message'] = ['type' => 'danger', 'text' => $result['message']];
                header("Location: ?route=auth/register");
            }
            exit;
        } else {
            $this->showRegister();
        }
    }

    public function logout() {
        session_destroy();
        header("Location: ?route=home");
        exit;
    }

    public function requireRole($role) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] !== $role) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Akses ditolak.'];
            header("Location: ?route=auth/login");
            exit;
        }
    }
}
