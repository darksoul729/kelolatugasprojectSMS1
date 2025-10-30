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
            $user = $result['user'];

            // ✅ Cek apakah akun belum diverifikasi
            if ($user['peran'] === 'belum_verifikasi') {
                $_SESSION['message'] = [
                    'type' => 'warning',
                    'text' => 'Akun Anda belum dikonfirmasi oleh admin. Silakan tunggu persetujuan.'
                ];
                header("Location: ?route=auth/login");
                exit;
            }

            // ✅ Simpan sesi user
            $_SESSION['user'] = $user;
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Login berhasil!'];

            // ✅ Redirect sesuai peran pengguna
            switch ($user['peran']) {
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
            // ❌ Login gagal
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
    // Pastikan session aktif
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Hanya tangani POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $this->showRegister();
    }

    // Ambil data input dengan aman
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $kelas = $_POST['kelas'] ?? null;
    $nip_nis = trim($_POST['nip_nis'] ?? '');
    $peran = 'belum_verifikasi'; // default role

    // ✅ 1. Validasi password konfirmasi
    if ($password !== $confirm_password) {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Password dan konfirmasi password tidak cocok.'
        ];
        header("Location: ?route=auth/register");
        exit;
    }

    // ✅ 2. Validasi input wajib isi
    if (empty($nama) || empty($email) || empty($password)) {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Nama lengkap, email, dan password wajib diisi.'
        ];
        header("Location: ?route=auth/register");
        exit;
    }

    // ✅ 3. Cek apakah email sudah digunakan
    if ($this->userModel->isEmailExist($email)) {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Email sudah terdaftar. Silakan gunakan email lain.'
        ];
        header("Location: ?route=auth/register");
        exit;
    }

    // ✅ 4. Cek apakah NIP/NIS sudah digunakan (jika diisi)
    if (!empty($nip_nis) && $this->userModel->isNipNisExist($nip_nis)) {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'NIP/NIS sudah digunakan oleh pengguna lain.'
        ];
        header("Location: ?route=auth/register");
        exit;
    }

    // ✅ 5. Simpan ke database
    $result = $this->userModel->register($nama, $email, $password, $peran, $kelas, $nip_nis);

    if ($result['success']) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Registrasi berhasil! Akun Anda menunggu konfirmasi dari admin.'
        ];
        header("Location: ?route=auth/menunggu-konfirmasi");
    } else {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => $result['message'] ?? 'Gagal menyimpan data pengguna.'
        ];
        header("Location: ?route=auth/register");
    }

    exit;
}

public function showMenungguKonfirmasi() {
    $view = $this->basePath . '/resources/views/auth/menunggu_konfirmasi.php';
    if (file_exists($view)) {
        include $view;
    } else {
        echo "<h3>File view menunggu konfirmasi tidak ditemukan di:</h3><code>$view</code>";
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