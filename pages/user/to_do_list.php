<?php
include '../../includes/session_check.php';
include '../../includes/db_config.php';

if ($_SESSION['role'] !== 'siswa') {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$project_id = $_GET['project_id'] ?? null;

if (!$project_id) {
    header("Location: dashboard.php");
    exit;
}

// Ambil detail project
$stmt = $pdo->prepare("SELECT judul FROM projects WHERE project_id = ? AND user_id = ?");
$stmt->execute([$project_id, $user_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    header("Location: dashboard.php");
    exit;
}

// Ambil todo list untuk project ini (tanpa ORDER BY created_at)
$stmt = $pdo->prepare("SELECT * FROM todo_list WHERE project_id = ? ORDER BY todo_id ASC");
$stmt->execute([$project_id]);
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$error = '';

// Handle tambah todo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_todo'])) {
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $is_done = isset($_POST['is_done']) ? 1 : 0;

    if (empty($deskripsi)) {
        $error = "Deskripsi wajib diisi.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO todo_list (deskripsi, project_id, is_done) VALUES (?, ?, ?)");
        $stmt->execute([$deskripsi, $project_id, $is_done]);
        $message = "✅ To-do berhasil ditambahkan.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?project_id=" . $project_id);
        exit;
    }
}

// Handle edit todo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_todo'])) {
    $todo_id = $_POST['todo_id'] ?? null;
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $is_done = isset($_POST['is_done']) ? 1 : 0;

    if ($todo_id && !empty($deskripsi)) {
        $stmt = $pdo->prepare("UPDATE todo_list SET deskripsi = ?, is_done = ? WHERE todo_id = ? AND project_id = ?");
        $stmt->execute([$deskripsi, $is_done, $todo_id, $project_id]);
        $message = "✅ To-do berhasil diperbarui.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?project_id=" . $project_id);
        exit;
    }
}

// Handle hapus todo
if (isset($_GET['delete_todo'])) {
    $todo_id = $_GET['delete_todo'];
    $stmt = $pdo->prepare("DELETE FROM todo_list WHERE todo_id = ? AND project_id = ?");
    $stmt->execute([$todo_id, $project_id]);
    $message = "✅ To-do berhasil dihapus.";
    header("Location: " . $_SERVER['PHP_SELF'] . "?project_id=" . $project_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List: <?= htmlspecialchars($project['judul']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">To-Do List: <?= htmlspecialchars($project['judul']); ?></h1>
            <a href="dashboard.php" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="mb-4 text-green-600 text-center"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="mb-4 text-red-600 text-center"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Form Tambah Todo -->
        <form method="POST" class="mb-6 p-4 bg-white rounded-lg shadow">
            <div class="flex gap-2">
                <input type="text" name="deskripsi" placeholder="Tambah tugas..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                <button type="submit" name="add_todo" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tambah</button>
            </div>
        </form>

        <!-- Daftar Todo -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="font-semibold text-gray-700 mb-4">Daftar Tugas</h3>
            <?php if (count($todos) > 0): ?>
                <ul class="space-y-4">
                    <?php foreach ($todos as $todo): ?>
                        <li class="p-4 border rounded-lg">
                            <form method="POST" class="flex justify-between items-center">
                                <input type="hidden" name="todo_id" value="<?= $todo['todo_id']; ?>">
                                <input type="text" name="deskripsi" value="<?= htmlspecialchars($todo['deskripsi']); ?>" class="flex-1 px-2 py-1 border rounded" required>
                                <label class="ml-3 flex items-center">
                                    <input type="checkbox" name="is_done" value="1" class="rounded text-blue-600" <?= $todo['is_done'] ? 'checked' : ''; ?>>
                                    <span class="ml-1 text-sm">Selesai</span>
                                </label>
                                <button type="submit" name="edit_todo" class="ml-2 text-sm bg-green-600 text-white px-3 py-1 rounded">Ubah</button>
                                <a href="?delete_todo=<?= $todo['todo_id']; ?>&project_id=<?= $project_id; ?>" class="ml-2 text-sm bg-red-600 text-white px-3 py-1 rounded" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 text-sm italic">Belum ada tugas.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
