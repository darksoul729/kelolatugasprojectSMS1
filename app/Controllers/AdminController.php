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


public function delete($id_user) {
    $result = $this->userModel->deleteUser($id_user);
    $_SESSION['message'] = [
        'type' => $result['success'] ? 'success' : 'danger',
        'text' => $result['message']
    ];
    header("Location: ?route=admin/users");
    exit;
}

public function verify() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Metode request tidak valid.'
        ];
        header("Location: ?route=admin/users");
        exit;
    }

    // Ambil data dari POST
    $id_user = isset($_POST['id_user']) ? (int) $_POST['id_user'] : null;
    $peran = isset($_POST['peran']) ? $_POST['peran'] : 'siswa';
    $wali_kelas = $peran === 'guru' && isset($_POST['wali_kelas']) ? $_POST['wali_kelas'] : null;

    // Validasi ID
    if (!$id_user) {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'ID user tidak ditemukan.'
        ];
        header("Location: ?route=admin/users");
        exit;
    }

    // Update peran user
    $success = $this->userModel->setPeran($id_user, $peran, $wali_kelas);

    $_SESSION['message'] = [
        'type' => $success ? 'success' : 'danger',
        'text' => $success 
            ? "User berhasil diverifikasi dan menjadi {$peran}" . ($peran === 'guru' ? " dengan Wali Kelas {$wali_kelas}" : "") 
            : "Gagal memverifikasi user."
    ];

    header("Location: ?route=admin/users");
    exit;
}




public function setrole() {
    if (isset($_GET['id']) && isset($_GET['role'])) {
        $id = (int) $_GET['id'];
        $role = $_GET['peran'] === 'guru' ? 'guru' : 'siswa';

        // ambil koneksi dari userModel
        $pdo = $this->userModel->getConnection();
        $stmt = $pdo->prepare("UPDATE users SET peran = ? WHERE id_user = ?");
        $stmt->execute([$role, $id]);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => "Peran pengguna berhasil diubah menjadi {$role}."
        ];
    }

    header('Location: ?route=admin/users');
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
