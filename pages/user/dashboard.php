<?php
include '../../includes/session_check.php';

// Opsional: cek role
if ($_SESSION['role'] !== 'siswa') {
    header("Location: /login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa</title>
</head>
<body>
    <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Ini adalah halaman dashboard siswa.</p>

    <a href="../../includes/logout.php">Logout</a>
</body>
</html>
