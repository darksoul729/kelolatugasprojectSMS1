<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /login.php");
    exit;
}

// Periksa $require_role hanya jika variabel tersebut sudah didefinisikan
if (isset($require_role) && $_SESSION['role'] !== $require_role) {
    header("Location: /login.php");
    exit;
}
?>
