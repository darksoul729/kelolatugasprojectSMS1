<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/session_check.php';

// Models
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Tugas.php';
require_once __DIR__ . '/../app/Models/KategoriTugas.php';
require_once __DIR__ . '/../app/Models/PengumpulanTugas.php';
require_once __DIR__ . '/../app/Models/Penilaian.php';

// Controllers
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';
require_once __DIR__ . '/../app/Controllers/TugasController.php';
require_once __DIR__ . '/../app/Controllers/TugasMuridController.php';
require_once __DIR__ . '/../app/Controllers/PengumpulanController.php';
require_once __DIR__ . '/../app/Controllers/PenilaianController.php';



// --- IZINKAN FILE STATIC ---
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('#^/uploads/#', $requestUri)) {
    $filePath = __DIR__ . $requestUri;
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

$route = safeGetRoute();
$route = $_GET['route'] ?? '/';
$authCtrl      = new AuthController($pdo);
$userCtrl      = new UserController($pdo);
$tugasGuruCtrl = new TugasController($pdo);
$tugasSiswaCtrl= new TugasMuridController($pdo);
$kumpulCtrl    = new PengumpulanController($pdo);
$nilaiCtrl     = new PenilaianController($pdo);

switch ($route) {
     case '/':
        require __DIR__ . '/../resources/views/landing.php';
        break;

    case 'home':
        require __DIR__ . '/../resources/views/landing.php';
        break;

    // AUTH
    case 'auth/login': $authCtrl->showLogin(); break;
    case 'auth/doLogin': $authCtrl->login(); break;
    case 'auth/register': $authCtrl->showRegister(); break;
    case 'auth/doRegister': $authCtrl->register(); break;
    case 'auth/logout': $authCtrl->logout(); break;

    // ADMIN
    case 'admin/users':
        $authCtrl->requireRole('admin');
        $userCtrl->index();
        break;

    case 'admin/user/detail':
        $authCtrl->requireRole('admin');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id ? $userCtrl->show($id) : redirectTo('admin/users');
        break;

    // GURU
    case 'guru/dashboard':
        $authCtrl->requireRole('guru');
        $controller = new TugasController($pdo);
        $controller->index();
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

    case 'guru/pengumpulan':
        $authCtrl->requireRole('guru');
        include __DIR__ . '/../resources/views/guru/list_pengumpulan.php';
        break;


    case 'guru/tugas/edit':
    $authCtrl->requireRole('guru');
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $tugasGuruCtrl->editForm($id); // tampilkan form edit
    } else {
        redirectTo('guru/tugas');
    }
    break;

case 'guru/tugas/delete':
    $authCtrl->requireRole('guru');
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $tugasGuruCtrl->delete($id); // hapus tugas
    } else {
        redirectTo('guru/tugas');
    }
    break;


    case 'guru/tugas/update':
    $id = $_GET['id'] ?? null;
    $controller = new TugasController($pdo);
    $controller->update($id);
    break;


    case 'guru/penilaian':
        $authCtrl->requireRole('guru');
        $id_pengumpulan = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id_pengumpulan
            ? $nilaiCtrl->nilai($id_pengumpulan)
            : redirectTo('guru/pengumpulan');
        break;


    

    // MURID
    case 'murid/dashboard':
        $authCtrl->requireRole('siswa');
        $tugasSiswaCtrl->index();
        break;

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

    default:
        http_response_code(404);
        include __DIR__ . '/../resources/views/errors/404.php';
        break;
}
