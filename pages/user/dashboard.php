<?php
include '../../includes/session_check.php';
include '../../includes/db_config.php';

if ($_SESSION['role'] !== 'siswa') {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM projects WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; margin: 20px; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #f2f2f2; }
        h1 { color: #333; }
        a { text-decoration: none; color: red; }
    </style>
</head>
<body>
    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Ini adalah halaman dashboard siswa.</p>

    <h2>Daftar Proyek Kamu</h2>

    <button onclick="window.location.href='add_task.php'" >Tambah Tugas</button>

    <?php if (count($projects) > 0): ?>
        <table>
            <tr>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Deadline</th>
                <th>Status</th>
            </tr>
            <?php foreach ($projects as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']); ?></td>
                <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                <td><?= htmlspecialchars($row['deadline']); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p><em>Tidak ada proyek yang terdaftar.</em></p>
    <?php endif; ?>

    <br>
    <a href="../../includes/logout.php">Logout</a>
</body>
</html>