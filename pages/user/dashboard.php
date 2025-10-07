<?php
include '../../includes/session_check.php';

// Opsional: cek role
if ($_SESSION['role'] !== 'siswa') {
    header("Location: /login.php");
    exit;
}


$sql =  "SELECT * FROM projects WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
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


    <h2>Daftar Proyek Kamu</h2>

    <?php if ($result->num_rows>0):?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Deadline</th>
                <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()):?>
        <tr>
            <td><?= htmlspecialchars($row['judul'])?></td>
            <td><?= htmlspecialchars($row['deskripsi'])?></td>
            <td><?= htmlspecialchars($row['deadline'])?></td>
            <td><?= htmlspecialchars($row['status'])?></td>
    </tr>
    <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>Tidak ada proyek yang terdaftar.</p>
        <?php endif;?>

        <br>
    <a href="../../includes/logout.php">Logout</a>
</body>
</html>
