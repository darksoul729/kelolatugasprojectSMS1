<?php
include '../../includes/db_config.php';
session_start();

// Cek login & role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses tambah tugas pakai PDO
if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];

    try {
        $stmt = $pdo->prepare("INSERT INTO projects (user_id,judul, deskripsi, deadline) VALUES (:user_id,:judul, :deskripsi, :deadline)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':judul' => $judul,
            ':deskripsi' => $deskripsi,
            ':deadline' => $deadline
        ]);
        echo "<script>alert('Tugas berhasil ditambahkan!'); window.location='dashboard.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal menambah tugas: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6fc;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(90deg, #4b49ac, #3f3d56);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        header h2 {
            margin: 0;
            font-size: 20px;
        }

        header a {
            color: white;
            text-decoration: none;
            background: #6366f1;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        header a:hover {
            background: #4f46e5;
        }

        .container {
            max-width: 600px;
            background: white;
            margin: 60px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h3 {
            text-align: center;
            color: #3f3d56;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #444;
            font-weight: 500;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        button {
            width: 100%;
            background: #4b49ac;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #3f3d56;
        }

        footer {
            text-align: center;
            color: #888;
            font-size: 14px;
            padding: 20px 0;
        }
    </style>
</head>
<body>

<header>
    <h2>Tambah Tugas</h2>
    <a href="dashboard.php">← Kembali ke Dashboard</a>
</header>

<div class="container">
    <h3>Form Tambah Tugas</h3>
    <form method="POST">
        <label for="judul">Judul Tugas</label>
        <input type="text" id="judul" name="judul" required>

        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>

        <label for="deadline">Deadline</label>
        <input type="date" id="deadline" name="deadline" required>

        <button type="submit" name="submit">Tambah Tugas</button>
    </form>
</div>

<footer>
    © 2025 Sistem Manajemen Tugas Siswa
</footer>

</body>
</html>