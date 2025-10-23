<?php
// app/Controllers/TugasController.php
require_once __DIR__ . '/../Models/Tugas.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/KategoriTugas.php';
require_once __DIR__ . '/../Models/AnakKebiasaan.php';
$basePath = dirname(__DIR__, 2); // dari app/Controllers ke root project
class TugasController {
    private $pdo;
    private $tugasModel;
    private $kategoriModel;
    private $basePath;
    private $userModel;
    private $anakKebiasaaan;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->tugasModel = new Tugas($pdo);
        $this->kategoriModel = new KategoriTugas($pdo);
        $this->anakKebiasaaan = new AnakKebiasaan($pdo);
        $this->userModel = new User($pdo);
        $this->basePath = dirname(__DIR__, 2); // naik 2 level dari /app/Controllers
    }

    /**
     * Dashboard Guru
     */
   public function index() {
    $user = $_SESSION['user'] ?? null;

    if (!$user || $user['peran'] !== 'guru') {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Hanya guru yang dapat mengakses halaman ini.'];
        header("Location: /index.php");
        exit;
    }

    $wali_kelas = $user['wali_kelas'];

    // Ambil data anak sesuai kelas wali
    $stmtAnak = $this->pdo->query("SELECT * FROM anak_kebiasaan WHERE kelas = '$wali_kelas'");
    $anakkebiasaan = $stmtAnak->fetchAll(PDO::FETCH_ASSOC);

    // Ambil tugas sesuai guru yang login
    $stmtTugas = $this->pdo->query("SELECT * FROM tugas WHERE id_guru = {$user['id_user']}");
    $tugas = $stmtTugas->fetchAll(PDO::FETCH_ASSOC);

    // Ambil kategori
    $kategori = $this->kategoriModel->all();


    $siswa = $this->userModel->getSiswaByKelas($wali_kelas);

    // Total siswa di kelas wali
    $stmtSiswa = $this->pdo->query("SELECT COUNT(*) AS total FROM users WHERE peran = 'siswa' AND wali_kelas = '$wali_kelas'");
    $totalSiswa = $stmtSiswa->fetch()['total'] ?? 0;

    $totalTugas = count($tugas);

    include $this->basePath . '/resources/views/guru/dashboard_guru.php';
}


    public function getByKelas($kelas) {
    // Ambil data tugas dari model, bukan dari controller lagi
    $tugas = $this->tugasModel->getByKelas($kelas); 
    include __DIR__.'/../../resources/views/components/tabel_biasa.php';
}



    /**
     * Tambah Tugas
     */
    public function create() {
        $user = $_SESSION['user'] ?? null;

        if (!$user || $user['peran'] !== 'guru') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Akses ditolak.'];
            header("Location: /index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lampiran = null;

            // Upload lampiran
            if (!empty($_FILES['lampiran_guru']['name'])) {
                $target_dir = $this->basePath . '/public/uploads/tugas/';
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

                $filename = uniqid('lampiran_') . "_" . basename($_FILES["lampiran_guru"]["name"]);
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($_FILES["lampiran_guru"]["tmp_name"], $target_file)) {
                    $lampiran = $filename;
                }
            }

           $data = [
                'judul_tugas'           => $_POST['judul_tugas'],
                'deskripsi'             => $_POST['deskripsi'] ?? null,
                'id_guru'               => $user['id_user'],
                'id_kategori'           => $_POST['id_kategori'],
                'tanggal_mulai'         => $_POST['tanggal_mulai'] ?? date('Y-m-d H:i:s'),
                'tanggal_deadline'      => $_POST['tanggal_deadline'],
                'durasi_estimasi'       => $_POST['durasi_estimasi'] ?? null,
                'poin_nilai'            => $_POST['poin_nilai'] ?? 100,
                'instruksi_pengumpulan' => $_POST['instruksi_pengumpulan'] ?? null,
                'lampiran_guru'         => $lampiran,
                'status_tugas'          => $_POST['status_tugas'] ?? 'aktif',

                 // Tambahan baru:
                 'kelas'                 => $_POST['kelas'] ?? null,
                ];              


            $result = $this->tugasModel->create($data);

            $_SESSION['message'] = [
                'type' => $result['success'] ? 'success' : 'danger',
                'text' => $result['success'] ? '✅ Tugas berhasil dibuat.' : '❌ ' . $result['message']
            ];

            header("Location: /routes/web.php?route=guru/dashboard");
            exit;
        }

        $kategori = $this->kategoriModel->all();
        $view = include $this->basePath . '/resources/views/guru/tambah_tugas.php';
    }

    /**
     * Detail Tugas
     */
    public function show($id) {
        $tugas = $this->tugasModel->findById($id);

        if (!$tugas) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Tugas tidak ditemukan.'];
            header("Location: /routes/web.php?route=guru/dashboard");
            exit;
        }

        $view = include $this->basePath . '/resources/views/guru/detail_tugas.php';
    }

    /**
     * Form Edit Tugas
     */
    public function editForm($id) {
        $tugas = $this->tugasModel->findById($id);
        if (!$tugas) {
            echo "<script>alert('Tugas tidak ditemukan.'); window.location='?route=guru/dashboard';</script>";
            return;
        }

        $kategori = $this->kategoriModel->all();
        $view = include $this->basePath . '/resources/views/guru/edit_tugas.php';
    }

    /**
     * Update Tugas
     */
    public function update($id) {
        $user = $_SESSION['user'] ?? null;
        if (!$user || $user['peran'] !== 'guru') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Akses ditolak. Hanya guru yang dapat mengedit tugas.'];
            header("Location: /index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lampiran = $_POST['lampiran_lama'] ?? null;

            if (!empty($_FILES['lampiran_guru']['name'])) {
                $target_dir = $this->basePath . '/public/uploads/tugas/';
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

                $filename = uniqid('lampiran_') . "_" . basename($_FILES["lampiran_guru"]["name"]);
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($_FILES["lampiran_guru"]["tmp_name"], $target_file)) {
                    if (!empty($_POST['lampiran_lama']) && file_exists($target_dir . $_POST['lampiran_lama'])) {
                        unlink($target_dir . $_POST['lampiran_lama']);
                    }
                    $lampiran = $filename;
                }
            }

            $data = [
                'judul_tugas'           => $_POST['judul_tugas'],
                'deskripsi'             => $_POST['deskripsi'] ?? null,
                'id_kategori'           => $_POST['id_kategori'],
                'tanggal_mulai'         => $_POST['tanggal_mulai'],
                'tanggal_deadline'      => $_POST['tanggal_deadline'],
                'durasi_estimasi'       => $_POST['durasi_estimasi'] ?? null,
                'poin_nilai'            => $_POST['poin_nilai'] ?? 100,
                'instruksi_pengumpulan' => $_POST['instruksi_pengumpulan'] ?? null,
                'lampiran_guru'         => $lampiran,
                'status_tugas'          => $_POST['status_tugas'] ?? 'aktif',
                'kelas'                 => $_POST['kelas'] ?? null,
            ];

            $result = $this->tugasModel->update($id, $data);

            $_SESSION['message'] = [
                'type' => $result['success'] ? 'success' : 'danger',
                'text' => $result['success'] ? 'Tugas berhasil diperbarui.' : '' . $result['message']
            ];

            header("Location: /routes/web.php?route=guru/dashboard");
            exit;
        }

        $tugas = $this->tugasModel->findById($id);
        $kategori = $this->kategoriModel->all();
        $view = $this->basePath . '/resources/views/guru/edit_tugas.php';
       
    }

    /**
     * Hapus Tugas
     */
    public function delete($id) {
        $tugas = $this->tugasModel->findById($id);

        if (!$tugas) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Tugas tidak ditemukan.'
            ];
            header("Location: /routes/web.php?route=guru/dashboard");
            exit;
        }

        if (!empty($tugas['lampiran_guru'])) {
            $filePath = $this->basePath . "/public/uploads/tugas/" . $tugas['lampiran_guru'];
            if (file_exists($filePath)) unlink($filePath);
        }

        $this->tugasModel->delete($id);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Tugas berhasil dihapus.'
        ];

        header("Location: /routes/web.php?route=guru/dashboard");
        exit;
    }
}