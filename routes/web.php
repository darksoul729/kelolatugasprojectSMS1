<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$basePath = dirname(__DIR__); // dari folder /routes ke root proyek

require_once $basePath . '/includes/db_config.php';
require_once $basePath . '/includes/session_check.php';

// Models
require_once $basePath . '/app/Models/User.php';
require_once $basePath . '/app/Models/Tugas.php';
require_once $basePath . '/app/Models/KategoriTugas.php';
require_once $basePath . '/app/Models/PengumpulanTugas.php';
require_once $basePath . '/app/Models/Penilaian.php';

// Controllers
require_once $basePath . '/app/Controllers/AuthController.php';
require_once $basePath . '/app/Controllers/AdminController.php';
require_once $basePath . '/app/Controllers/TugasController.php';
require_once $basePath . '/app/Controllers/TugasMuridController.php';
require_once $basePath . '/app/Controllers/PengumpulanController.php';
require_once $basePath . '/app/Controllers/PenilaianController.php';


// --- IZINKAN FILE STATIC (upload) ---
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('#^/uploads/#', $requestUri)) {
    $filePath = $basePath . '/public' . $requestUri;
    if (file_exists($filePath)) {
        return readfile($filePath);
    } else {
        http_response_code(404);
        echo "File tidak ditemukan.";
        exit;
    }
}

// Helper
function safeGetRoute(): string {
    $r = filter_input(INPUT_GET, 'route');
    return $r ?: 'home';
}

function redirectTo(string $route) {
    header("Location: ?route={$route}");
    exit;
}

// Inisialisasi Controller
$route           = safeGetRoute();
$authCtrl        = new AuthController($pdo);
$userCtrl        = new AdminController($pdo);
$tugasGuruCtrl   = new TugasController($pdo);
$tugasSiswaCtrl  = new TugasMuridController($pdo);
$kumpulCtrl      = new PengumpulanController($pdo);
$nilaiCtrl       = new PenilaianController($pdo);


// ==============================
//  BLOKIR AKSES LOGIN/REGISTER UNTUK YANG SUDAH LOGIN
// ==============================
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['peran'];

    // Daftar route khusus guest
    $guestOnlyRoutes = ['/', 'home','auth/login', 'auth/register', 'auth/doLogin', 'auth/doRegister'];

    if (in_array($route, $guestOnlyRoutes)) {
        // Arahkan sesuai peran
        switch ($role) {
            case 'admin':
                header("Location: ?route=admin/users");
                exit;
            case 'guru':
                header("Location: ?route=guru/dashboard");
                exit;
            case 'siswa':
                header("Location: ?route=murid/dashboard");
                exit;
            default:
                header("Location: ?route=home");
                exit;
        }
    }
}


// === ROUTER ===
switch ($route) {
    /** ==============================
     *  LANDING / HOME
     *  ============================== */
    case '/':
    case 'home':
        require $basePath . '/resources/views/landing.php';
        break;


    /** ==============================
     *  AUTHENTICATION
     *  ============================== */
    case 'auth/login': $authCtrl->showLogin(); break;
    case 'auth/doLogin': $authCtrl->login(); break;
    case 'auth/register': $authCtrl->showRegister(); break;
    case 'auth/doRegister': $authCtrl->register(); break;
    case 'auth/logout': $authCtrl->logout(); break;


    /** ==============================
     *  ADMIN PANEL
     *  ============================== */
    case 'admin/users':
        $authCtrl->requireRole('admin');
        $userCtrl->index();
        break;

    case 'admin/user/detail':
        $authCtrl->requireRole('admin');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id ? $userCtrl->show($id) : redirectTo('admin/users');
        break;


    /** ==============================
     *  GURU DASHBOARD
     *  ============================== */
    case 'guru/dashboard':
        $authCtrl->requireRole('guru');
        $tugasGuruCtrl->index();
        break;

    case 'guru/tugas':
        $authCtrl->requireRole('guru');
        $tugasGuruCtrl->index();
        break;

    case 'guru/tugas/tambah':
        $authCtrl->requireRole('guru');
        $tugasGuruCtrl->create();
        break;

    case 'guru/tugas/detail':
        $authCtrl->requireRole('guru');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id ? $tugasGuruCtrl->show($id) : redirectTo('guru/tugas');
        break;

    case 'guru/tugas/edit':
        $authCtrl->requireRole('guru');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id ? $tugasGuruCtrl->editForm($id) : redirectTo('guru/tugas');
        break;

    case 'guru/tugas/update':
        $authCtrl->requireRole('guru');
        $id = $_GET['id'] ?? null;
        $tugasGuruCtrl->update($id);
        break;

    case 'guru/tugas/delete':
        $authCtrl->requireRole('guru');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id ? $tugasGuruCtrl->delete($id) : redirectTo('guru/tugas');
        break;

    case 'guru/pengumpulan':
        $authCtrl->requireRole('guru');
        include $basePath . '/resources/views/guru/list_pengumpulan.php';
        break;

    case 'guru/penilaian':
        $authCtrl->requireRole('guru');
        $id_pengumpulan = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id_pengumpulan
            ? $nilaiCtrl->nilai($id_pengumpulan)
            : redirectTo('guru/pengumpulan');
        break;


    /** ==============================
     *  MURID DASHBOARD
     *  ============================== */
    case 'murid/dashboard':
    case 'murid/tugas':
        $authCtrl->requireRole('siswa');
        $tugasSiswaCtrl->index();
        break;

    case 'murid/tugas/detail':
        $authCtrl->requireRole('siswa');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id ? $tugasSiswaCtrl->show($id) : redirectTo('murid/tugas');
        break;

    case 'murid/kumpul':
        $authCtrl->requireRole('siswa');
        $id_tugas = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id_tugas ? $kumpulCtrl->submit($id_tugas) : redirectTo('murid/tugas');
        break;


    /** ==============================
     *  404 HALAMAN TIDAK DITEMUKAN
     *  ============================== */
    default:
        http_response_code(404);
        include $basePath . '/resources/views/errors/404.php';
        break;
}
