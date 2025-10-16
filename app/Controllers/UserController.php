<?php
// app/Controllers/UserController.php
require_once __DIR__ . '/../Models/User.php'; // ← tambahkan slash di sini

class UserController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function index() {
        if ($_SESSION['user']['peran'] !== 'admin') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Akses ditolak.'];
            header("Location: ?route=home");
            exit;
        }

        $users = $this->userModel->all();
        include '../views/admin/users/index.php'; // pastikan path view benar
    }

    public function show($id) {
        if ($_SESSION['user']['peran'] !== 'admin') {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Akses ditolak.'];
            header("Location: ?route=home");
            exit;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Pengguna tidak ditemukan.'];
            header("Location: ?route=admin/users");
            exit;
        }
        include '../views/admin/users/show.php';
    }
}