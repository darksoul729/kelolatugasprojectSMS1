<?php
// app/Controllers/PenilaianController.php
require_once __DIR__ . '/../Models/Penilaian.php';
require_once __DIR__ . '/../Models/PengumpulanTugas.php';

class PenilaianController {
    private $penilaianModel;
    private $pengumpulanModel;

    public function __construct($pdo) {
        $this->penilaianModel = new Penilaian($pdo);
        $this->pengumpulanModel = new PengumpulanTugas($pdo);
    }

    public function nilai($id_pengumpulan) {
        if ($_SESSION['user']['peran'] !== 'guru') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Hanya guru yang bisa memberi nilai.'];
            header("Location: ../guru/list_tugas.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nilai = (int)$_POST['nilai'];
            $komentar = $_POST['komentar'] ?? null;

            if ($nilai < 0 || $nilai > 100) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Nilai harus antara 0–100.'];
                header("Location: ../guru/penilaian.php?id=$id_pengumpulan");
                exit;
            }

            $result = $this->penilaianModel->nilai(
                $id_pengumpulan,
                $_SESSION['user']['id_user'],
                $nilai,
                $komentar
            );

            if ($result['success']) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Penilaian berhasil disimpan.'];
            } else {
                $_SESSION['message'] = ['type' => 'danger', 'text' => $result['message']];
            }
            header("Location: ../guru/pengumpulan.php");
            exit;
        }

        // Ambil data pengumpulan untuk ditampilkan di form
        $pengumpulan = $this->pengumpulanModel->findBySiswaAndTugas(null, null); // Anda bisa buat method khusus di model
        // Contoh: ambil detail via JOIN
        include '../views/guru/penilaian/form.php';
    }
}