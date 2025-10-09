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

// Ambil progress updates untuk project ini
$stmt = $pdo->prepare("SELECT * FROM progress_updates WHERE project_id = ? ORDER BY tanggal_update DESC");
$stmt->execute([$project_id]);
$progress_updates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$error = '';

// Handle tambah update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_update'])) {
    $keterangan = trim($_POST['keterangan'] ?? '');
    $foto_dokumentasi = null;

    if (empty($keterangan)) {
        $error = "Keterangan wajib diisi.";
    } else {
        // Upload foto dokumentasi (jika ada)
        if (isset($_FILES['foto_dokumentasi']) && $_FILES['foto_dokumentasi']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_tmp = $_FILES['foto_dokumentasi']['tmp_name'];
            $file_name = $_FILES['foto_dokumentasi']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed_ext)) {
                $new_file_name = uniqid() . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $foto_dokumentasi = '/uploads/' . $new_file_name;
                } else {
                    $error = "Gagal mengunggah gambar.";
                }
            } else {
                $error = "Format gambar tidak didukung.";
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("INSERT INTO progress_updates (project_id, tanggal_update, keterangan, foto_dokumentasi) VALUES (?, NOW(), ?, ?)");
            $stmt->execute([$project_id, $keterangan, $foto_dokumentasi]);
            $message = "✅ Progress berhasil ditambahkan.";
            header("Location: " . $_SERVER['PHP_SELF'] . "?project_id=" . $project_id);
            exit;
        }
    }
}

// Handle edit update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_update'])) {
    $update_id = $_POST['update_id'] ?? null;
    $keterangan = trim($_POST['keterangan'] ?? '');
    $foto_dokumentasi = null;

    if ($update_id && !empty($keterangan)) {
        // Upload foto dokumentasi baru (jika ada)
        if (isset($_FILES['foto_dokumentasi']) && $_FILES['foto_dokumentasi']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_tmp = $_FILES['foto_dokumentasi']['tmp_name'];
            $file_name = $_FILES['foto_dokumentasi']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed_ext)) {
                $new_file_name = uniqid() . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $foto_dokumentasi = '/uploads/' . $new_file_name;
                } else {
                    $error = "Gagal mengunggah gambar.";
                }
            } else {
                $error = "Format gambar tidak didukung.";
            }
        }

        if (!$error) {
            if ($foto_dokumentasi) {
                $stmt = $pdo->prepare("UPDATE progress_updates SET keterangan = ?, foto_dokumentasi = ? WHERE update_id = ? AND project_id = ?");
                $stmt->execute([$keterangan, $foto_dokumentasi, $update_id, $project_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE progress_updates SET keterangan = ? WHERE update_id = ? AND project_id = ?");
                $stmt->execute([$keterangan, $update_id, $project_id]);
            }
            $message = "✅ Progress berhasil diperbarui.";
            header("Location: " . $_SERVER['PHP_SELF'] . "?project_id=" . $project_id);
            exit;
        }
    }
}

// Handle hapus update
if (isset($_GET['delete_update'])) {
    $update_id = $_GET['delete_update'];
    $stmt = $pdo->prepare("DELETE FROM progress_updates WHERE update_id = ? AND project_id = ?");
    $stmt->execute([$update_id, $project_id]);
    $message = "✅ Progress berhasil dihapus.";
    header("Location: " . $_SERVER['PHP_SELF'] . "?project_id=" . $project_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Progress: <?= htmlspecialchars($project['judul']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Laporan Progress: <?= htmlspecialchars($project['judul']); ?></h1>
            <a href="dashboard.php" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="mb-4 text-green-600 text-center"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="mb-4 text-red-600 text-center"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Form Tambah Update -->
        <form method="POST" enctype="multipart/form-data" class="mb-6 p-4 bg-white rounded-lg shadow">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" placeholder="Jelaskan progress yang telah dicapai..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumentasi (Gambar)</label>
                <input type="file" name="foto_dokumentasi" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" name="add_update" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tambah Update</button>
        </form>

        <!-- Daftar Progress Updates -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="font-semibold text-gray-700 mb-4">Riwayat Progress</h3>
            <?php if (count($progress_updates) > 0): ?>
                <ul class="space-y-6">
                    <?php foreach ($progress_updates as $update): ?>
                        <li class="p-4 border rounded-lg">
                            <form method="POST" enctype="multipart/form-data" class="mb-3">
                                <input type="hidden" name="update_id" value="<?= $update['update_id']; ?>">
                                <div class="mb-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                    <textarea name="keterangan" class="w-full px-2 py-1 border rounded" required><?= htmlspecialchars($update['keterangan']); ?></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Baru (Opsional)</label>
                                    <input type="file" name="foto_dokumentasi" accept="image/*" class="w-full text-sm">
                                </div>
                                <button type="submit" name="edit_update" class="text-sm bg-green-600 text-white px-3 py-1 rounded">Ubah</button>
                                <a href="?delete_update=<?= $update['update_id']; ?>&project_id=<?= $project_id; ?>" class="ml-2 text-sm bg-red-600 text-white px-3 py-1 rounded" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </form>
                            <p class="text-sm text-gray-500">Tanggal: <?= $update['tanggal_update']; ?></p>
                            <?php if ($update['foto_dokumentasi']): ?>
                                <div class="mt-2">
                                    <img src="<?= $update['foto_dokumentasi']; ?>" alt="Dokumentasi" class="max-w-xs rounded border">
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 text-sm italic">Belum ada laporan progress.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
