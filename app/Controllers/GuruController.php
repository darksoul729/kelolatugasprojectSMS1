<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/AnakKebiasaan.php'; // pastikan file ini ada

class GuruController {
    private $pdo;
    private $userModel;
    private $basePath;
    private $kebiasaanModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->kebiasaanModel = new AnakKebiasaan($pdo);
        $this->userModel = new User($pdo);
        $this->basePath = dirname(__DIR__, 2); // ke root proyek
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
        $bulanParam = $_GET['bulan'] ?? date('m');
        $tahunParam = $_GET['tahun'] ?? date('Y');

        // 🗓️ Nama bulan Indonesia
        $namaBulan = [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
            '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
        ];
        $bulanIndo = ($namaBulan[str_pad($bulanParam, 2, '0', STR_PAD_LEFT)] ?? $bulanParam) . ' ' . $tahunParam;

        // 📊 Ambil data rekap kebiasaan siswa
        $kebiasaanModel = new AnakKebiasaan($this->pdo);
        $rekap = $kebiasaanModel->getMonthlySummaryByClass($kelasGuru, $bulanParam, $tahunParam);

        // 👥 Ambil data siswa
        $siswa = $this->userModel->getSiswaByKelas($kelasGuru);

        // Hitung total siswa
        $stmtTotal = $this->pdo->prepare("
            SELECT COUNT(*) AS total FROM users 
            WHERE peran = 'siswa' AND wali_kelas = :kelas
        ");
        $stmtTotal->execute([':kelas' => $kelasGuru]);
        $jumlahSiswa = $stmtTotal->fetch()['total'] ?? 0;

        // 🔹 Siapkan variabel untuk view
        $kelas = $kelasGuru;
        $bulan = $bulanIndo;
        $rekap = $rekap ?? [];
        $siswa = $siswa ?? [];
        $jumlah_siswa = $jumlahSiswa;

        // 🌐 Jika request AJAX → kirim JSON
        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode([
                'bulan' => $bulan,
                'kelas' => $kelas,
                'rekap' => $rekap,
                'siswa' => $siswa,
                'jumlah_siswa' => $jumlahSiswa
            ]);
            exit;
        }

        // 📄 Kirim ke view tabel kebiasaan
        include $this->basePath . '/resources/views/components/tabel_biasa.php';
    }

    /**
     * 🔍 Deteksi apakah request datang dari AJAX
     */
    private function isAjax() {
        return (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) || (isset($_GET['ajax']) && $_GET['ajax'] == 1);
    }
}
