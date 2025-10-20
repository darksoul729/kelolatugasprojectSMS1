<?php
// app/Controllers/PengumpulanController.php
require_once __DIR__ . '/../Models/PengumpulanTugas.php';

class PengumpulanController {
    private $pengumpulanModel;

    public function __construct($pdo) {
        $this->pengumpulanModel = new PengumpulanTugas($pdo);
    }

    // Method untuk menampilkan form pengumpulan tugas
    public function index($id_tugas) {
        if ($_SESSION['user']['peran'] !== 'siswa') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Hanya siswa yang bisa mengumpulkan tugas.'];
            header("Location: ../murid/list_tugas.php");
            exit;
        }

        // Ambil data tugas jika diperlukan (misal dari model tugas)
        // require_once __DIR__ . '/../Models/Tugas.php';
        // $tugasModel = new Tugas($this->pengumpulanModel->getPDO());
        // $tugas = $tugasModel->getById($id_tugas);

        // Tampilkan form (bisa pakai include)
        include __DIR__ . '/../Views/form_kumpul_tugas.php';
    }

    public function submit($id_tugas) {
        if ($_SESSION['user']['peran'] !== 'siswa') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Hanya siswa yang bisa mengumpulkan tugas.'];
            header("Location: ../murid/list_tugas.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isi = $_POST['isi_jawaban'] ?? null;
            $catatan = $_POST['catatan_siswa'] ?? null;
            $lampiran = null;

            if (!empty($_FILES['lampiran']['name'])) {
                $target_dir = "../public/uploads/jawaban/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $filename = uniqid() . "_" . basename($_FILES["lampiran"]["name"]);
                $target_file = $target_dir . $filename;
                if (move_uploaded_file($_FILES["lampiran"]["tmp_name"], $target_file)) {
                    $lampiran = $filename;
                }
            }

            $result = $this->pengumpulanModel->submit(
                $id_tugas,
                $_SESSION['user']['id_user'],
                $isi,
                $lampiran,
                $catatan
            );

            if ($result['success']) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Tugas berhasil dikumpulkan!'];
            } else {
                $_SESSION['message'] = ['type' => 'danger', 'text' => $result['message']];
            }
            header("Location: ../murid/list_tugas.php");
            exit;
        }
    }
}
