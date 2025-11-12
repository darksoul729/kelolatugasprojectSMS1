<?php

require_once __DIR__ . '/../Models/User.php';

// Tentukan base path proyek secara universal (lintas OS)
$basePath = dirname(__DIR__, 2); // dari app/Controllers ke root project


class SiswaController {
    private $userModel;
    private $basePath;

    public function __construct($pdo) {
        global $basePath; // ambil dari luar
        $this->basePath = $basePath;
        $this->userModel = new User($pdo);
    }



   public function index() {
    if (!isset($_SESSION['user'])) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Silakan login terlebih dahulu.'];
        header("Location: ?route=auth/login");
        exit;
    }

    $user = $_SESSION['user'];

    if ($user['peran'] !== 'siswa') {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Hanya siswa yang dapat mengakses halaman ini.'];
        header("Location: ?route=home");
        exit;
    }


    include $this->basePath . '/resources/views/murid/dashboard_murid.php';
}

// Ambil siswa sesuai kelas wali guru
    public function getSiswaKelas($kelas) {
        return $this->userModel->getSiswaByKelas($kelas);
    }

    // Tampilkan view
    public function tampilSiswaKelas($kelas) {
        $siswa = $this->getSiswaKelas($kelas);
        include $this->basePath . '/resources/views/components/tabel_siswa.php';
    }

    
}

?>