<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ambil route dari URL
$currentRoute = $_GET['route'] ?? '';

// Abaikan proteksi jika halaman login/register
$publicRoutes = [
    'auth/login',
    'auth/register',
    'auth/doLogin',
    'auth/doRegister',
    'home'
];

if (!in_array($currentRoute, $publicRoutes)) {
    // Jika belum login, arahkan ke halaman login
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: ?route=auth/login");
        exit;
    }

    // Cek role jika dibutuhkan
    if (isset($require_role) && $_SESSION['role'] !== $require_role) {
        header("Location: ?route=auth/login");
        exit;
    }
}
