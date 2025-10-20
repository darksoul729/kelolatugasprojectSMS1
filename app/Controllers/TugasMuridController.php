<?php
// app/Controllers/TugasMuridController.php

require_once __DIR__ . '/../Models/Tugas.php';

// Tentukan base path proyek secara universal (lintas OS)
$basePath = dirname(__DIR__, 2); // dari app/Controllers ke root project

class TugasMuridController {
    private $tugasModel;
    private $basePath;

    public function __construct($pdo) {
        global $basePath; // ambil dari luar
        $this->basePath = $basePath;
        $this->tugasModel = new Tugas($pdo);
    }

    /** 
     * Halaman daftar tugas untuk siswa 
     */
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

    // Sekarang otomatis hanya ambil tugas sesuai kelas siswa
    $tugas = $this->tugasModel->allBySiswa($user['id_user']);

    include $this->basePath . '/resources/views/murid/dashboard_murid.php';
}


    /** 
     * Halaman detail tugas untuk siswa 
     */
    public function show($id) {
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

        $tugas = $this->tugasModel->findById($id);

        if (!$tugas) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Tugas tidak ditemukan.'];
            header("Location: ?route=murid/tugas");
            exit;
        }

        include $this->basePath . '/resources/views/murid/detail_tugas.php';
    }
}
