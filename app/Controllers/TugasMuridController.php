<?php
// app/Controllers/TugasMuridController.php
require_once __DIR__ . '/../Models/Tugas.php';

class TugasMuridController {
    private $tugasModel;

    public function __construct($pdo) {
        $this->tugasModel = new Tugas($pdo);
    }

    public function index() {
        $user = $_SESSION['user'];
        if ($user['peran'] !== 'siswa') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Hanya siswa yang bisa melihat tugas.'];
            header("Location: ../index.php");
            exit;
        }

        $tugas = $this->tugasModel->allBySiswa($user['id_user']);
        include __DIR__ . '/../../resources/views/murid/dashboard_murid.php';
    }

    public function show($id) {
        $tugas = $this->tugasModel->findById($id);
        if (!$tugas) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Tugas tidak ditemukan.'];
            header("Location: ?route=murid/tugas");
            exit;
        }
        include __DIR__ . '/../../resources/views/murid/detail_tugas.php';
    }
}
