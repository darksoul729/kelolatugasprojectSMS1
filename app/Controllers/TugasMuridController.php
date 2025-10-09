<?php
class TugasMuridController {
    private $pdo;
    private $project;
    private $progress;
    private $todo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->project = new Project($pdo);
        $this->progress = new ProgressUpdate($pdo);
        $this->todo = new Todo($pdo);
    }

    // === LIST SEMUA TUGAS ===
    public function index() {
        $user_id = $_SESSION['user_id'];
        $projects = $this->project->allByUser($user_id);
        include __DIR__ . '/../../resources/views/murid/list_tugas.php';
    }

    // === TAMBAH TUGAS ===
    public function create() {
        include __DIR__ . '/../../resources/views/murid/tambah_tugas.php';
    }

    public function store() {
        $user_id = $_SESSION['user_id'];
        $judul = $_POST['judul'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $deadline = $_POST['deadline'] ?? '';

        if (trim($judul) === '' || trim($deskripsi) === '' || trim($deadline) === '') {
            $_SESSION['error'] = 'Semua kolom wajib diisi.';
            header("Location: ?route=tugas/tambah");
            exit;
        }

        $this->project->create($user_id, $judul, $deskripsi, $deadline);
        header("Location: ?route=tugas/list");
        exit;
    }

    // === DETAIL TUGAS ===
  public function detail() {
    $user_id = $_SESSION['user_id'] ?? null;
    $project_id = $_GET['project_id'] ?? null;

    if (!$user_id || !$project_id) {
        header("Location: ?route=tugas/list");
        exit;
    }

    $project = $this->project->findById($project_id, $user_id);
    if (!$project) {
        header("Location: ?route=tugas/list");
        exit;
    }

    $progress_updates = $this->progress->allByProject($project_id);
    $todos = $this->todo->allByProject($project_id);

    include __DIR__ . '/../../resources/views/murid/detail_tugas.php';
}


    // === PROGRESS CRUD ===
    public function addProgress() {
        $project_id = $_POST['project_id'] ?? null;
        $keterangan = $_POST['keterangan'] ?? '';

        if ($project_id && trim($keterangan) !== '') {
            $this->progress->create($project_id, $keterangan, null);
        }

        header("Location: ?route=tugas/detail&project_id=" . $project_id);
        exit;
    }

    public function editProgress() {
        $update_id = $_POST['update_id'] ?? null;
        $project_id = $_POST['project_id'] ?? null;
        $keterangan = $_POST['keterangan'] ?? '';

        if ($update_id && $project_id && trim($keterangan) !== '') {
            $this->progress->update($update_id, $keterangan);
        }

        header("Location: ?route=tugas/detail&project_id=" . $project_id);
        exit;
    }

    public function deleteProgress() {
        $update_id = $_GET['update_id'] ?? null;
        $project_id = $_GET['project_id'] ?? null;

        if ($update_id && $project_id) {
            $this->progress->delete($update_id, $project_id);
        }

        header("Location: ?route=tugas/detail&project_id=" . $project_id);
        exit;
    }

    // === TODO CRUD ===
    public function addTodo() {
        $project_id = $_POST['project_id'] ?? null;
        $deskripsi = $_POST['deskripsi'] ?? '';
        $is_done = isset($_POST['is_done']) ? 1 : 0;

        if ($project_id && trim($deskripsi) !== '') {
            $this->todo->create($project_id, $deskripsi, $is_done);
        }

        header("Location: ?route=tugas/detail&project_id=" . $project_id);
        exit;
    }

    public function editTodo() {
        $todo_id = $_POST['todo_id'] ?? null;
        $project_id = $_POST['project_id'] ?? null;
        $deskripsi = $_POST['deskripsi'] ?? '';
        $is_done = isset($_POST['is_done']) ? 1 : 0;

        if ($todo_id && $project_id) {
            $this->todo->update($todo_id, $project_id, $deskripsi, $is_done);
        }

        header("Location: ?route=tugas/detail&project_id=" . $project_id);
        exit;
    }

    public function deleteTodo() {
        $todo_id = $_GET['todo_id'] ?? null;
        $project_id = $_GET['project_id'] ?? null;

        if ($todo_id && $project_id) {
            $this->todo->delete($todo_id, $project_id);
        }

        header("Location: ?route=tugas/detail&project_id=" . $project_id);
        exit;
    }
}
