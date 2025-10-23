<?php
// app/Controllers/UserController.php

require_once __DIR__ . '/../Models/User.php';


class AdminController {
    private $userModel;
    private $basePath;

    public function __construct($pdo) {
        // Base path fleksibel lintas OS (Windows, Linux, macOS)
        $this->basePath = dirname(__DIR__, 2);
        $this->userModel = new User($pdo);
    }

    /**
 * 🟡 Edit User (mengambil data untuk modal)
 */
public function edit($id_user) {
    $user = $this->userModel->findById($id_user);
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Pengguna tidak ditemukan.']);
        exit;
    }
    // Kembalikan data user sebagai JSON agar bisa di-fill modal JS
    echo json_encode(['success' => true, 'data' => $user]);
    exit;
}

/**
 * 🟢 Update user (submit dari modal)
 */
public function update($id_user, $postData) {
    $result = $this->userModel->editUser($id_user, $postData);
    $_SESSION['message'] = [
        'type' => $result['success'] ? 'success' : 'danger',
        'text' => $result['message']
    ];
    header("Location: ?route=admin/users");
    exit;
}

    /**
     * 🟢 Dashboard Admin - menampilkan daftar semua user
     */
 public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] !== 'admin') {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.'
            ];
            header("Location: ?route=auth/login");
            exit;
        }

        $keyword = $_GET['search'] ?? null;

        if ($keyword) {
            $users = $this->userModel->search($keyword);
        } else {
            $users = $this->userModel->all();
        }

        $totalUsers = $this->userModel->countAll();
        $roleCounts = $this->userModel->countByRole();

        $viewPath = $this->basePath . '/resources/views/admin/dashboard_admin.php';
        if (!file_exists($viewPath)) {
            die("❌ View tidak ditemukan: $viewPath");
        }

        include $viewPath;
    }

    


    /**
     * 🟡 Detail User
     */
    public function show($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] !== 'admin') {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.'
            ];
            header("Location: ?route=auth/login");
            exit;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Pengguna tidak ditemukan.'
            ];
            header("Location: ?route=admin/users");
            exit;
        }

        $viewPath = $this->basePath . '/resources/views/admin/detail_user.php';
        if (!file_exists($viewPath)) {
            die("❌ View tidak ditemukan: $viewPath");
        }

        include $viewPath;
    }
}
