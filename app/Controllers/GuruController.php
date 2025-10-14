<?php
class GuruController {
    private $pdo;
    private $project;
    private $user;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->project = new Project($pdo);
        $this->user = new User($pdo);
    }

    // === Dashboard Guru ===
    public function dashboard() {
        $users = $this->getAllSiswa();
        $projects = $this->getAllTugasSiswa();
        include __DIR__ . '/../../resources/views/guru/dashboard_guru.php';
    }

    // === Ambil semua siswa ===
    public function getAllSiswa() {
        $stmt = $this->pdo->prepare("
            SELECT user_id AS id, nama_lengkap, username
            FROM users
            WHERE role = 'siswa'
            ORDER BY nama_lengkap ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === Ambil semua tugas siswa ===
    public function getAllTugasSiswa() {
        $stmt = $this->pdo->prepare("
            SELECT p.project_id AS id, p.judul, p.deadline, u.nama_lengkap AS nama_siswa, p.status, p.created_at
            FROM projects p
            JOIN users u ON p.user_id = u.user_id
            ORDER BY p.deadline ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === List tugas untuk satu siswa tertentu ===
    public function listTugas($user_id) {
        if (!$user_id) {
            header("Location: ?route=guru/dashboard");
            exit;
        }

        // Ambil data siswa
        $siswa = $this->user->findById($user_id);
        if (!$siswa) {
            header("Location: ?route=guru/dashboard");
            exit;
        }

        // Ambil tugas siswa
        $stmt = $this->pdo->prepare("
            SELECT project_id AS id, judul, deadline, status, created_at
            FROM projects
            WHERE user_id = :user_id
            ORDER BY deadline ASC
        ");
        $stmt->execute(['user_id' => $user_id]);
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../../resources/views/guru/list_tugas_siswa.php';
    }

    // === Detail tugas tertentu ===
 // Menjadi
public function detailTugas($project_id) {
    $project = $this->project->findById($project_id); // Project.php juga harus mendukung single arg
    if (!$project) {
        header("Location: ?route=guru/dashboard");
        exit;
    }
    include __DIR__ . '/../../resources/views/guru/detail_tugas.php';
}
}
