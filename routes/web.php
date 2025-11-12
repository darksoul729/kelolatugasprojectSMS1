<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$basePath = dirname(__DIR__);

// === CONFIG & SESSION ===
require_once $basePath . '/includes/db_config.php';
require_once $basePath . '/includes/session_check.php';

// === MODELS ===
require_once $basePath . '/app/Models/User.php';
require_once $basePath . '/app/Models/AnakKebiasaan.php';

// === CONTROLLERS ===
require_once $basePath . '/app/Controllers/AuthController.php';
require_once $basePath . '/app/Controllers/AdminController.php';
require_once $basePath . '/app/Controllers/AnakKebiasaanController.php';
require_once $basePath . '/app/Controllers/SiswaController.php';
require_once $basePath . '/app/Controllers/LandingController.php';
require_once $basePath . '/app/Controllers/GuruController.php';




// === HELPER ===
function safeGetRoute(): string {
    $r = filter_input(INPUT_GET, 'route');
    return $r ?: 'home';
}

function redirectTo(string $route) {
    header("Location: ?route={$route}");
    exit;
}

// === INISIALISASI CONTROLLER ===
$route           = safeGetRoute();
$authCtrl        = new AuthController($pdo);
$userCtrl        = new AdminController($pdo);
$kebiasaanCtrl   = new AnakKebiasaanController($pdo);
$siswaCtrl       = new SiswaController($pdo);
$landingCtrl     = new LandingController($pdo);
$guruCtrl        = new GuruController($pdo);
$dashboardSiswa  = new SiswaController($pdo);

// === BLOKIR LOGIN / REGISTER UNTUK YANG SUDAH LOGIN ===
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['peran'];
    $guestOnlyRoutes = ['/', 'home','auth/login', 'auth/register', 'auth/doLogin', 'auth/doRegister'];

    if (in_array($route, $guestOnlyRoutes)) {
        switch ($role) {
            case 'admin': header("Location: ?route=admin/users"); exit;
            case 'guru': header("Location: ?route=guru/dashboard"); exit;
            case 'siswa': header("Location: ?route=murid/dashboard"); exit;
            default: header("Location: ?route=home"); exit;
        }
    }
}

// === ROUTER ===
switch ($route) {
    // HOME
    case '/':
    case 'home':
        require $basePath . '/resources/views/landing.php';
        break;
    
    case 'landing/kirim_pesan':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $landingCtrl->kirim_pesan();
        }
        break;

    // AUTH
    case 'auth/login': $authCtrl->showLogin(); break;
    case 'auth/doLogin': $authCtrl->login(); break;
    case 'auth/register': $authCtrl->showRegister(); break;
    case 'auth/doRegister': $authCtrl->register(); break;
    case 'auth/logout': $authCtrl->logout(); break;
    case 'auth/menunggu-konfirmasi': $authCtrl->showMenungguKonfirmasi(); break;

    // ADMIN PANEL
    case 'admin/users':
        $authCtrl->requireRole('admin');
        $userCtrl->index();
        break;

    case 'admin/users/import':
        $authCtrl->requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userCtrl->importSiswa(); // ✅ PERBAIKAN: method yang benar
        }
        break;
    case 'admin/users/import-guru':
        $authCtrl->requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userCtrl->importGuru(); // ✅ PERBAIKAN: method yang benar
        }
        break;
    case 'admin/users/download_guru_template':
        $authCtrl->requireRole('admin');
        $userCtrl->downloadTemplateGuru();
        break;

    case 'admin/users/download_template':
        $authCtrl->requireRole('admin');
        $userCtrl->downloadTemplate();
        break;

    case 'admin/users/setrole':
        $authCtrl->requireRole('admin');
        $userCtrl->setrole();
        break;

    case 'admin/user/detail':
        $authCtrl->requireRole('admin');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id ? $userCtrl->show($id) : redirectTo('admin/users');
        break;

    case 'admin/users/edit':
        $authCtrl->requireRole('admin');
        $id = $_GET['id'] ?? null;
        if ($id) $userCtrl->edit($id);
        break;

    case 'admin/users/update':
        $authCtrl->requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_user'] ?? null;
            if ($id) $userCtrl->update($id); // ✅ PERBAIKAN: parameter sesuai controller
        }
        break;

    case 'admin/users/delete':
    $authCtrl->requireRole('admin');
    
    // Handle both GET (single delete) and POST (bulk delete)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Bulk delete - panggil tanpa parameter
        $userCtrl->delete();
    } else {
        // Single delete dari URL
        $id = filter_input(INPUT_GET, 'id_user', FILTER_VALIDATE_INT); // Ganti 'id' menjadi 'id_user'
        $id ? $userCtrl->delete($id) : redirectTo('admin/users');
    }
    break;
    
    case 'admin/users/verify':
        $authCtrl->requireRole('admin');
        $userCtrl->verify();
        break;

    // GURU PANEL
    case 'guru/dashboard':
        $authCtrl->requireRole('guru');
        $guruCtrl->index();
        break;
     

    case 'guru/kebiasaan/ringkasan-json':
        $authCtrl->requireRole('guru');
        $kebiasaanCtrl->ringkasanKebiasaanPerBulanGuru();
        break;

    case 'guru/kebiasaan/ringkasan-view':
        $authCtrl->requireRole('guru');
        $kelas = $_GET['kelas'] ?? ($_SESSION['user']['wali_kelas'] ?? null);
        if ($kelas) {
            $kebiasaanCtrl->listKebiasaanByKelas($kelas);
        } else {
            echo "<p>Kelas tidak ditemukan</p>";
        }
        break;

    case 'kebiasaan/anak':
        $authCtrl->requireRole('guru');
        break;    

    // MURID PANEL
    case 'murid/dashboard':
        $authCtrl->requireRole('siswa');
        $dashboardSiswa->index();
        break;
    
    case 'murid/rekap':
        $authCtrl->requireRole('siswa');
        $kebiasaanCtrl->rekapBulanan();
        break;

    case 'murid/detail_kebiasaan':
        $authCtrl->requireRole('siswa');
        $kebiasaanCtrl->getDetailByDate();
        break;

  

    case 'murid/kebiasaan/tambah':
        $authCtrl->requireRole('siswa');
        $kebiasaanCtrl->createForm();
        break;
        
    case 'murid/kebiasaan/simpan':
        $authCtrl->requireRole('siswa');
        $kebiasaanCtrl->store();
        break;

    // KEBIASAAN ANAK INDONESIA - GURU
    case 'kebiasaan_anak':
        $authCtrl->requireRole('guru');
        $kelasWali = $_SESSION['user']['wali_kelas'] ?? '';
        if (trim($kelasWali) === '') {
            $_SESSION['message'] = ['type'=>'warning','text'=>'Guru belum memiliki kelas yang diasuh.'];
            header("Location: /index.php");
            exit;
        }
        $kebiasaan = $kebiasaanCtrl->getAllByKelas($kelasWali);
        include $basePath.'/resources/views/components/tabel_kebiasaan.php';
        break;

    case 'siswa_kelas':
        $authCtrl->requireRole('guru');
        $kelasWali = $_SESSION['user']['wali_kelas'] ?? '';
        if (trim($kelasWali) === '') {
            $_SESSION['message'] = [
                'type' => 'warning',
                'text' => 'Guru belum memiliki kelas yang diasuh.'
            ];
            header("Location: /index.php");
            exit;
        }
        $siswaCtrl->tampilSiswaKelas($kelasWali);
        break;

    // 404
    default:
        http_response_code(404);
        include $basePath . '/resources/views/errors/404.php';
        break;
}