<?php
include '../../includes/session_check.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

include '../../includes/db_config.php';

$project_id = $_GET['id'] ?? null;

if (!$project_id) {
    header("Location: dashboard.php");
    exit;
}

// Ambil detail project
$stmt = $pdo->prepare("
    SELECT p.*, u.nama_lengkap AS nama_siswa
    FROM projects p
    JOIN users u ON p.user_id = u.user_id
    WHERE p.project_id = ?
");
$stmt->execute([$project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    header("Location: dashboard.php");
    exit;
}

// Ambil update progres
$updates = $pdo->prepare("SELECT * FROM progress_updates WHERE project_id = ? ORDER BY tanggal_update DESC");
$updates->execute([$project_id]);
$progress_updates = $updates->fetchAll(PDO::FETCH_ASSOC);

// Ambil todo list
$todos = $pdo->prepare("SELECT * FROM todo_list WHERE project_id = ? ORDER BY todo_id ASC");
$todos->execute([$project_id]);
$todo_list = $todos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Project: <?php echo htmlspecialchars($project['judul']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'merah': '#e74c3c',
                        'biru': '#3498db',
                        'hijau': '#2ecc71',
                    }
                }
            }
        }
    </script>
    <style>
        .status-pending { background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; }
        .status-progress { background-color: #3498db; color: white; padding: 4px 8px; border-radius: 4px; }
        .status-done { background-color: #2ecc71; color: white; padding: 4px 8px; border-radius: 4px; }
    </style>
</head>
<body class="font-sans bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Detail Project</h1>
        <a href="dashboard.php" class="text-biru hover:underline">← Kembali ke Dashboard</a>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($project['judul']); ?></h2>
                    <p class="text-gray-600"><?php echo htmlspecialchars($project['nama_siswa']); ?></p>
                </div>
                <span class="status-<?php echo $project['status']; ?> text-sm font-medium">
                    <?php echo ucfirst($project['status']); ?>
                </span>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-700"><strong>Deadline:</strong> <?php echo $project['deadline'] ?? '-'; ?></p>
                    <p class="mt-2 text-gray-700"><?php echo htmlspecialchars($project['deskripsi']); ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-bold text-gray-700">Progress Tugas</h3>
                    <?php
                    $total_todos = count($todo_list);
                    $done_todos = count(array_filter($todo_list, fn($t) => $t['is_done']));
                    $progress = $total_todos > 0 ? ($done_todos / $total_todos) * 100 : 0;
                    ?>
                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-hijau h-2.5 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                    <p class="mt-1 text-sm text-gray-600"><?php echo $done_todos; ?> dari <?php echo $total_todos; ?> tugas selesai</p>
                </div>
            </div>
        </div>

        <!-- Update Progres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Update Progres</h3>
            <?php if ($progress_updates): ?>
                <div class="space-y-4">
                    <?php foreach ($progress_updates as $update): ?>
                        <div class="border-l-4 border-biru pl-4 py-2">
                            <p class="font-medium"><?php echo $update['tanggal_update']; ?></p>
                            <p class="text-gray-700"><?php echo htmlspecialchars($update['keterangan']); ?></p>
                            <?php if ($update['foto_dokumentasi']): ?>
                                <div class="mt-2">
                                    <img src="<?php echo $update['foto_dokumentasi']; ?>" alt="Dokumentasi" class="max-w-xs rounded-lg shadow-sm">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 italic">Belum ada update progres.</p>
            <?php endif; ?>
        </div>

        <!-- Daftar Tugas -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Tugas</h3>
            <?php if ($todo_list): ?>
                <ul class="space-y-2">
                    <?php foreach ($todo_list as $todo): ?>
                        <li class="flex items-center p-2 hover:bg-gray-50 rounded">
                            <span class="<?php echo $todo['is_done'] ? 'text-hijau' : 'text-gray-400'; ?> mr-2">
                                <?php echo $todo['is_done'] ? '✓' : '○'; ?>
                            </span>
                            <span class="<?php echo $todo['is_done'] ? 'line-through text-gray-500' : 'text-gray-700'; ?>">
                                <?php echo htmlspecialchars($todo['deskripsi']); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 italic">Belum ada tugas.</p>
            <?php endif; ?>
        </div>
    </main>

   
</body>
</html>
