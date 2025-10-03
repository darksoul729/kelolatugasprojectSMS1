<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}
?>
<h1>Selamat Datang, Admin!</h1>
<a href="/logout.php">Logout</a>
