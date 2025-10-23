<?php
// Pastikan path ke model selalu benar lintas OS
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $userModel;
    private $basePath;

    public function __construct($pdo) {
        // Simpan base path absolut dari root project (2 tingkat di atas Controllers)
        $this->basePath = dirname(__DIR__, 2);
        $this->userModel = new User($pdo);
    }

    /** -----------------------------
     *  LOGIN PAKAI EMAIL SAJA
     * ----------------------------- */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $result = $this->userModel->loginByEmail($email, $password);

            if ($result['success']) {
                $_SESSION['user'] = $result['user'];
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Login berhasil!'];

                // Redirect sesuai peran pengguna
                switch ($result['user']['peran']) {
                    case 'admin':
                        header("Location: ?route=admin/users");
                        break;
                    case 'guru':
                        header("Location: ?route=guru/dashboard");
                        break;
                    case 'siswa':
                        header("Location: ?route=murid/dashboard");
                        break;
                    default:
                        header("Location: ?route=home");
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

    /** -----------------------------
     *  TAMPIL HALAMAN LOGIN
     * ----------------------------- */
    public function showLogin() {
        $view = $this->basePath . '/resources/views/auth/login.php';
        if (file_exists($view)) {
            include $view;
        } else {
            echo "<h3>File view login tidak ditemukan di:</h3><code>$view</code>";
        }
    }

    /** -----------------------------
     *  TAMPIL HALAMAN REGISTER
     * ----------------------------- */
    public function showRegister() {
        $view = $this->basePath . '/resources/views/auth/register.php';
        if (file_exists($view)) {
            include $view;
        } else {
            echo "<h3>File view register tidak ditemukan di:</h3><code>$view</code>";
        }
    }

    /** -----------------------------
     *  REGISTRASI USER BARU
     * ----------------------------- */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validasi kesesuaian password
            if ($password !== $confirm_password) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Password dan konfirmasi tidak cocok.'];
                header("Location: ?route=auth/register");
                exit;
            }

            $nama = trim($_POST['nama_lengkap'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $peran = $_POST['peran'] ?? 'siswa';
            $kelas = $_POST['kelas'] ?? null;
            $nip_nis = $_POST['nip_nis'] ?? null;

            // Validasi form wajib isi
            if (empty($nama) || empty($email) || empty($password)) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Nama lengkap, email, dan password wajib diisi.'];
                header("Location: ?route=auth/register");
                exit;
            }

            $result = $this->userModel->register($nama, $email, $password, $peran, $kelas, $nip_nis);

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

    /** -----------------------------
     *  LOGOUT
     * ----------------------------- */
    public function logout() {
        session_destroy();
        header("Location: ?route=home");
        exit;
    }

    /** -----------------------------
     *  BATAS AKSES BERDASARKAN ROLE
     * ----------------------------- */
    public function requireRole($role) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] !== $role) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Akses ditolak.'];
            header("Location: ?route=auth/login");
            exit;
        }
    }
}