<?php
// app/Controllers/TugasController.php
require_once __DIR__ . '/../Models/Tugas.php';
require_once __DIR__ . '/../Models/KategoriTugas.php';

class TugasController {
    private $pdo;
    private $tugasModel;
    private $kategoriModel;

    public function __construct($pdo) {
        $this->pdo = $pdo; // ✅ tambahkan ini
        $this->tugasModel = new Tugas($pdo);
        $this->kategoriModel = new KategoriTugas($pdo);
    }

    // 🟢 Halaman utama guru (dashboard)
    public function index() {
        $user = $_SESSION['user'];

        if ($user['peran'] !== 'guru') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Hanya guru yang bisa mengakses dashboard ini.'];
            header("Location: ../index.php");
            exit;
        }

        // Ambil semua data untuk dashboard
        $tugas = $this->tugasModel->allByGuru($user['id_user']);
        $kategori = $this->kategoriModel->all();

        // Hitung total siswa dan total tugas
        $stmtSiswa = $this->pdo->query("SELECT COUNT(*) as total FROM users WHERE peran = 'siswa'");
        $totalSiswa = $stmtSiswa->fetch()['total'] ?? 0;
        $totalTugas = count($tugas);

        include '../resources/views/guru/dashboard_guru.php';
    }

    // 🟦 Tambah tugas baru
    public function create() {
        if ($_SESSION['user']['peran'] !== 'guru') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Hanya guru yang bisa membuat tugas.'];
            header("Location: ../resources/views/guru/dashboard_guru.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $lampiran = null;

            // Upload lampiran
            if (!empty($_FILES['lampiran']['name'])) {
                $target_dir = "../public/uploads/tugas/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

                $filename = uniqid() . "_" . basename($_FILES["lampiran"]["name"]);
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($_FILES["lampiran"]["tmp_name"], $target_file)) {
                    $lampiran = $filename;
                }
            }

            // Simpan ke database
            $result = $this->tugasModel->create(
                $data['judul_tugas'],
                $data['deskripsi'],
                $_SESSION['user']['id_user'],
                $data['id_kategori'],
                $data['tanggal_deadline'],
                $data['poin_nilai'] ?? 100,
                $data['instruksi'] ?? null,
                $lampiran
            );

            // Notifikasi hasil
            if ($result['success']) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Tugas berhasil dibuat.'];
            } else {
                $_SESSION['message'] = ['type' => 'danger', 'text' => $result['message']];
            }

            header("Location: ../resources/views/guru/dashboard_guru.php");
            exit;
        }

        // Form tambah tugas
        $kategori = $this->kategoriModel->all();
        include '../resources/views/guru/tambah_tugas.php';
    }

    // 🟨 Lihat detail tugas
    public function show($id) {
        $tugas = $this->tugasModel->findById($id);

        if (!$tugas) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Tugas tidak ditemukan.'];
            header("Location: ../resources/views/guru/dashboard_guru.php");
            exit;
        }

        include '../resources/views/guru/detail_tugas.php';
    }
}
