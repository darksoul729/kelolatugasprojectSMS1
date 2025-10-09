<?php
// routes/web.php

require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/session_check.php';

// ===== Model =====
require_once __DIR__ . '/../app/Models/Project.php';
require_once __DIR__ . '/../app/Models/ProgressUpdate.php';
require_once __DIR__ . '/../app/Models/Todo.php';
require_once __DIR__ . '/../app/Models/User.php'; // Untuk login/register

// ===== Controller =====
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/TugasMuridController.php';

// ===== Inisialisasi Controller =====
$authController  = new AuthController($pdo);
$tugasController = new TugasMuridController($pdo);

// ===== Routing =====
$route = $_GET['route'] ?? 'home';

switch ($route) {
    // === HALAMAN UTAMA (LANDING PAGE) ===
    case 'home':
        include __DIR__ . '/../index.php';
        break;

    // === AUTENTIKASI ===
    case 'auth/login':
        $authController->showLogin();
        break;

    case 'auth/doLogin':
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
    case 'progress/tambah':
    case 'progress/update':
    case 'progress/delete':
    case 'todo/tambah':
    case 'todo/update':
    case 'todo/delete':
        $authController->requireRole('siswa');
        switch ($route) {
            case 'tugas/list':        $tugasController->index(); break;
            case 'tugas/tambah':      $tugasController->create(); break;
            case 'tugas/store':       $tugasController->store(); break;
            case 'tugas/detail':      $tugasController->detail(); break;

            case 'progress/tambah':   $tugasController->addProgress(); break;
            case 'progress/update':   $tugasController->editProgress(); break;
            case 'progress/delete':   $tugasController->deleteProgress(); break;

            case 'todo/tambah':       $tugasController->addTodo(); break;
            case 'todo/update':       $tugasController->editTodo(); break;
            case 'todo/delete':       $tugasController->deleteTodo(); break;
        }
        break;

    // === DEFAULT 404 ===
    default:
        http_response_code(404);
        echo "404 - Route tidak ditemukan";
        break;
}
