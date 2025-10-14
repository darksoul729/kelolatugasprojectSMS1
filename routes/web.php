<?php
// routes/web.php
// Routing utama aplikasi - versi yang diperbaiki dan lebih tahan terhadap error

// ===== Inisialisasi sesi dengan aman (hanya sekali per request) =====
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===== Includes =====
require_once __DIR__ . '/../includes/db_config.php';
// Jika includes/session_check.php hanya berisi pengecekan session, tidak perlu lagi memanggil session_start() di dalamnya.
// Pastikan file itu sendiri menggunakan `if (session_status() === PHP_SESSION_NONE) session_start();`
// Jika kamu tetap ingin include, pastikan di dalamnya ada pengecekan seperti di atas.
require_once __DIR__ . '/../includes/session_check.php';

// ===== Model =====
require_once __DIR__ . '/../app/Models/Project.php';
require_once __DIR__ . '/../app/Models/ProgressUpdate.php';
require_once __DIR__ . '/../app/Models/Todo.php';
require_once __DIR__ . '/../app/Models/User.php';

// ===== Controller =====
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/TugasMuridController.php';
require_once __DIR__ . '/../app/Controllers/GuruController.php';

// ===== Helper kecil =====
function safeGetRoute(): string {
    // Ambil route dari query string dengan sanitasi sederhana
    $r = filter_input(INPUT_GET, 'route', FILTER_SANITIZE_STRING);
    return $r ?: 'home';
}

function redirectTo(string $route) {
    header("Location: ?route={$route}");
    exit;
}

// Normalisasi route: support beberapa alias (legacy/ragam penamaan)
function normalizeRoute(string $route): string {
    $map = [
        // legacy / camelCase -> new kebab route
        'tugas/addTodo'     => 'todo/tambah',
        'tugas/updateTodo'  => 'todo/update',
        'tugas/deleteTodo'  => 'todo/delete',
        'tugas/addProgress' => 'progress/tambah',
        'tugas/detailTugas' => 'guru/tugas/detail',
        // ... tambahkan alias lain jika diperlukan
    ];

    // trim and lower
    $r = trim($route);
    if (isset($map[$r])) return $map[$r];
    return $r;
}

// ===== Inisialisasi Controller =====
// Pastikan controller menerima $pdo (dari db_config)
$authController  = new AuthController($pdo);
$tugasController = new TugasMuridController($pdo);
$guruController  = new GuruController($pdo);

// ===== Routing =====
$route = normalizeRoute(safeGetRoute());

switch ($route) {

    // === HALAMAN UTAMA ===
    case 'home':
        include __DIR__ . '/../index.php';
        break;

    // === AUTENTIKASI ===
    case 'auth/login':
        $authController->showLogin();
        break;

    case 'auth/doLogin':
        // pastikan method POST ditangani di controller
        $authController->doLogin();
        break;

    case 'auth/register':
        $authController->showRegister();
        break;

    case 'auth/doRegister':
        $authController->doRegister();
        break;

    case 'auth/logout':
        $authController->logout();
        break;

    // === MURID: TUGAS ===
    case 'tugas/list':
    case 'tugas/tambah':
    case 'tugas/store':
    case 'tugas/detail':
    case 'tugas/delete':
    case 'progress/tambah':
    case 'progress/update':
    case 'progress/delete':
    case 'todo/tambah':
    case 'todo/update':
    case 'todo/delete':
        // pastikan user terautentikasi & role siswa
        $authController->requireRole('siswa');

        switch ($route) {
            case 'tugas/list':
                $tugasController->index();
                break;

            case 'tugas/tambah':
                $tugasController->create();
                break;

            case 'tugas/store':
                $tugasController->store();
                break;

            case 'tugas/detail':
                // validasi project_id
                $project_id = filter_input(INPUT_GET, 'project_id', FILTER_VALIDATE_INT);
                if (!$project_id) {
                    // jika tidak ada, kembali ke list tugas
                    redirectTo('tugas/list');
                }
                // kirim param ke controller (metode detail perlu menerima param dari GET)
                $tugasController->detail($project_id);
                break;

            case 'tugas/delete':
                $project_id = filter_input(INPUT_GET, 'project_id', FILTER_VALIDATE_INT);
                if (!$project_id) redirectTo('tugas/list');
                $tugasController->delete($project_id);
                break;

            case 'progress/tambah':
                $tugasController->addProgress();
                break;

            case 'progress/update':
                $tugasController->editProgress();
                break;

            case 'progress/delete':
                $tugasController->deleteProgress();
                break;

            case 'todo/tambah':
                $tugasController->addTodo();
                break;

            case 'todo/update':
                $tugasController->editTodo();
                break;

            case 'todo/delete':
                $tugasController->deleteTodo();
                break;

            default:
                http_response_code(404);
                echo "404 - Route tugas tidak ditemukan";
                break;
        }
        break;

    // === GURU: DASHBOARD & TUGAS ===
    case 'guru/dashboard':
        $authController->requireRole('guru');
        $guruController->dashboard();
        break;

    case 'guru/tugas/list':
        $authController->requireRole('guru');
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);
        if ($user_id) {
            $guruController->listTugas($user_id);
        } else {
            redirectTo('guru/dashboard');
        }
        break;

    case 'guru/tugas/detail':
        $authController->requireRole('guru');
        $project_id = filter_input(INPUT_GET, 'project_id', FILTER_VALIDATE_INT);
        if ($project_id) {
            // pastikan method menerima param
            $guruController->detailTugas($project_id);
        } else {
            redirectTo('guru/dashboard');
        }
        break;

    // === ADMIN: DASHBOARD (redirect jika bukan admin) ===
    case 'admin/dashboard':
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            redirectTo('auth/login');
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            // Redirect sesuai role
            $role = $_SESSION['role'] ?? null;
            switch ($role) {
                case 'guru':
                    redirectTo('guru/dashboard');
                    break;
                case 'siswa':
                    redirectTo('tugas/list');
                    break;
                default:
                    redirectTo('home');
                    break;
            }
        }

        include __DIR__ . '/../resources/views/admin/dashboard.php';
        break;

    // === DEFAULT 404 ===
    default:
        http_response_code(404);
        echo "404 - Route tidak ditemukan";
        break;
}
