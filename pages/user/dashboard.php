<?php
include '../../includes/session_check.php';
include '../../includes/db_config.php';

if ($_SESSION['role'] !== 'siswa') {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua project milik user
$sql = "SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Siswa</h1>
            <a href="../../includes/logout.php" class="text-red-600 hover:underline">Logout</a>
        </div>

        <p class="mb-6 text-gray-700">Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?>!</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($projects as $project): ?>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <!-- Judul project sebagai link ke todo_list.php -->
                    <a href="todo_list.php?project_id=<?= $project['project_id']; ?>" class="text-xl font-bold text-blue-600 hover:underline mb-2 block">
                        <?= htmlspecialchars($project['judul']); ?>
                    </a>
                    
                    <p class="text-gray-600 mb-2"><?= htmlspecialchars($project['deskripsi']); ?></p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span>Deadline: <?= $project['deadline'] ? htmlspecialchars($project['deadline']) : 'Tidak ada'; ?></span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            <?php if ($project['status'] === 'baru') echo 'bg-yellow-100 text-yellow-800';
                                  elseif ($project['status'] === 'berjalan') echo 'bg-blue-100 text-blue-800';
                                  else echo 'bg-green-100 text-green-800'; ?>">
                            <?= ucfirst($project['status']); ?>
                        </span>
                    </div>

                    <!-- Tambahkan tombol untuk ke halaman todo list -->
                    <div class="flex space-x-3">
                        <a href="to_do_list.php?project_id=<?= $project['project_id']; ?>" class="text-blue-600 hover:underline text-sm">
                            ✏️ Kelola To-Do List
                        </a>
                        <a href="progress_report.php?project_id=<?= $project['project_id']; ?>" class="text-purple-600 hover:underline text-sm">
                            📊 Laporan Progress
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($projects)): ?>
            <div class="text-center py-12">
                <p class="text-gray-500">Belum ada proyek yang terdaftar.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
