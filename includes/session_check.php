<?php
// includes/session_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthHelper {
    public static function requireRole($role) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] !== $role) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Akses ditolak.'];
            header("Location: ?route=auth/login");
            exit;
        }
    }
}

// Tambahkan ke AuthController sebagai method
// Tapi agar mudah, kita tambahkan di AuthController langsung