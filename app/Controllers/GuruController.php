<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/AnakKebiasaan.php'; // pastikan file ini ada

class GuruController {
   private $pdo;
    private $tugasModel;
    private $kategoriModel;
    private $basePath;
    private $userModel;
    private $model;
    private $anakKebiasaaan;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new AnakKebiasaan($pdo);
        $this->anakKebiasaaan = new AnakKebiasaan($pdo);
        $this->userModel = new User($pdo);
        $this->basePath = dirname(__DIR__, 2); // naik 2 level dari /app/Controllers
    }

 public function index() {
    // 🔒 Pastikan user login dan peran guru
    $user = $_SESSION['user'] ?? null;
    if (!$user || $user['peran'] !== 'guru') {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Hanya guru yang dapat mengakses halaman ini.'
        ];
        header("Location: /index.php");
        exit;
    }

    // 🎓 Ambil kelas wali guru
    $kelasGuru = $user['wali_kelas'] ?? null;
    if (!$kelasGuru) {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Guru tidak memiliki kelas wali.'
        ];
        header("Location: /index.php");
        exit;
    }

    // 📅 Ambil bulan & tahun dari parameter (fallback ke sekarang)
    $bulan = $_GET['bulan'] ?? date('m');
    $tahun = $_GET['tahun'] ?? date('Y');

    // 📊 Ambil data rekap kebiasaan siswa di kelas
    $data = $this->model->getMonthlySummaryByClass($kelasGuru, $bulan, $tahun);

    // 🗓️ Nama bulan Indonesia
    $namaBulan = [
        '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
        '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
    ];
    $bulanIndo = ($namaBulan[str_pad($bulan, 2, '0', STR_PAD_LEFT)] ?? $bulan) . ' ' . $tahun;

    // 🌐 Jika request AJAX → kirim JSON
    if ($this->isAjax()) {
        header('Content-Type: application/json');
        echo json_encode([
            'bulan' => $bulanIndo,
            'kelas' => $kelasGuru,
            'rekap' => $data
        ]);
        exit;
    }

    // 🧭 Ambil data kebiasaan siswa
    $stmtAnak = $this->pdo->prepare("
        SELECT * FROM anak_kebiasaan 
        WHERE kelas = :kelas
    ");
    $stmtAnak->execute([':kelas' => $kelasGuru]);
    $anakkebiasaan = $stmtAnak->fetchAll(PDO::FETCH_ASSOC);

    // 📋 Ambil daftar siswa berdasarkan kelas wali
    $siswa = $this->userModel->getSiswaByKelas($kelasGuru);

    // 👥 Hitung total siswa dalam kelas
    $stmtSiswa = $this->pdo->prepare("
        SELECT COUNT(*) AS total 
        FROM users 
        WHERE peran = 'siswa' AND wali_kelas = :kelas
    ");
    $stmtSiswa->execute([':kelas' => $kelasGuru]);
    $totalSiswa = $stmtSiswa->fetch()['total'] ?? 0;


    // 📦 Variabel untuk view (yang akan dikirim ke dashboard_guru.php)
    $kelas = $kelasGuru;
    $rekap = $data;
    $bulan = $bulanIndo;
    $wali_kelas = $kelasGuru;
    $jumlah_siswa = $totalSiswa;

    // 🔹 Tampilkan dashboard guru
    include $this->basePath . '/resources/views/guru/dashboard_guru.php';
}


/** 
 * 🔍 Deteksi apakah request datang dari AJAX (fetch atau XMLHttpRequest)
 */
private function isAjax() {
    return (
        !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) || (isset($_GET['ajax']) && $_GET['ajax'] == 1);
}



  public function getAllKebiasaan() {
    $user = $_SESSION['user'] ?? null;
    if (!$user || $user['peran'] !== 'guru') {
        $_SESSION['message'] = ['type'=>'danger','text'=>'Hanya guru yang bisa melihat data kebiasaan.'];
        header('Location: /index.php');
        exit;
    }

    $waliKelas = $user['wali_kelas'];
    $bulan = $_GET['bulan'] ?? date('m');
    $tahun = $_GET['tahun'] ?? date('Y');

    $data = $this->anakKebiasaaan->getLaporanByWaliKelas($waliKelas, $bulan, $tahun);

    include __DIR__ . '/../../resources/views/components/tabel_kebiasaan.php';
}

}
