<?php
// Proteksi halaman: hanya murid
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ?route=auth/login");
    exit;
}

$project = $project ?? null;
$progress_updates = $progress_updates ?? [];
$todos = $todos ?? [];

if (!$project) {
    die("Tugas tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas - <?= htmlspecialchars($project['judul']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .task-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen font-sans">
    <div class="container mx-auto px-4 py-8 max-w-4xl">

        <!-- Header -->
        <header class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold"><?= htmlspecialchars($project['judul']) ?></h1>
                <span class="task-badge mt-2 
                    <?= $project['tipe_tugas'] === 'teori' ? 'bg-blue-100 text-blue-800' : '' ?>
                    <?= $project['tipe_tugas'] === 'praktikum' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                    <?= $project['tipe_tugas'] === 'proyek' ? 'bg-green-100 text-green-800' : '' ?>">
                    <?= ucfirst(htmlspecialchars($project['tipe_tugas'])) ?>
                </span>
            </div>
            <a href="?route=tugas/list" class="text-blue-600 hover:underline flex items-center">
                ← Kembali ke Daftar Tugas
            </a>
        </header>

        <!-- Info Umum -->
        <div class="bg-white rounded-xl shadow p-6 mb-8 card-hover">
            <h3 class="text-lg font-semibold mb-2">Deskripsi</h3>
            <p class="text-gray-600"><?= nl2br(htmlspecialchars($project['deskripsi'])) ?></p>

            <?php if ($project['tipe_tugas'] === 'proyek'): ?>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h3 class="text-lg font-semibold">Deadline</h3>
                    <p class="text-orange-500 font-medium"><?= htmlspecialchars($project['deadline']) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- === PROYEK: Progress + Todo === -->
        <?php if ($project['tipe_tugas'] === 'proyek'): ?>
            <!-- Progress Updates -->
            <div class="bg-white rounded-xl shadow p-6 mb-8 card-hover">
                <h2 class="text-xl font-bold mb-4">Progress</h2>
                <form action="?route=tugas/addProgress" method="POST" class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                    <textarea name="keterangan" placeholder="Deskripsi progress..." class="w-full px-3 py-2 border rounded focus:ring-blue-500 focus:border-blue-500" rows="3" required></textarea>
                    <button type="submit" class="mt-3 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Tambah Progress
                    </button>
                </form>

                <?php if (!empty($progress_updates)): ?>
                    <ul class="space-y-4">
                        <?php foreach ($progress_updates as $update): ?>
                            <li class="p-4 bg-blue-50 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-gray-800"><?= nl2br(htmlspecialchars($update['keterangan'])) ?></p>
                                        <p class="text-xs text-gray-500 mt-2"><?= htmlspecialchars($update['tanggal_update']) ?></p>
                                    </div>
                                    <a href="?route=tugas/deleteProgress&update_id=<?= $update['update_id'] ?>&project_id=<?= $project['id'] ?>" 
                                       onclick="return confirm('Hapus progress ini?')" 
                                       class="text-red-500 hover:text-red-700 ml-3">
                                        Hapus
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-gray-500 italic">Belum ada progress.</p>
                <?php endif; ?>
            </div>

            <!-- Todo List -->
            <div class="bg-white rounded-xl shadow p-6 card-hover">
                <h2 class="text-xl font-bold mb-4">To-Do List</h2>
                <form action="?route=tugas/addTodo" method="POST" class="flex gap-2 mb-4">
                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                    <input type="text" name="deskripsi" placeholder="Tambahkan kegiatan..." class="flex-1 px-4 py-2 border rounded focus:ring-blue-500 focus:border-blue-500" required>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Tambah
                    </button>
                </form>

                <?php if (!empty($todos)): ?>
                    <ul class="space-y-3">
                        <?php foreach ($todos as $todo): ?>
                            <li class="p-3 rounded-lg flex justify-between items-center <?= $todo['is_done'] ? 'bg-green-100' : 'bg-gray-50'; ?>">
                                <form action="?route=tugas/editTodo" method="POST" class="flex items-center flex-1">
                                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                                    <input type="hidden" name="todo_id" value="<?= $todo['todo_id'] ?>">
                                    <input type="hidden" name="deskripsi" value="<?= htmlspecialchars($todo['deskripsi']) ?>">
                                    <input type="checkbox" name="is_done" value="1"
                                           <?= $todo['is_done'] ? 'checked' : '' ?>
                                           onchange="this.form.submit()" class="mr-3">
                                    <span class="<?= $todo['is_done'] ? 'line-through text-gray-500' : 'text-gray-800'; ?>">
                                        <?= htmlspecialchars($todo['deskripsi']) ?>
                                    </span>
                                </form>
                                <a href="?route=tugas/deleteTodo&todo_id=<?= $todo['todo_id'] ?>&project_id=<?= $project['id'] ?>"
                                   onclick="return confirm('Hapus to-do ini?')"
                                   class="text-red-500 hover:text-red-700 ml-3">
                                    Hapus
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-gray-500 italic">Belum ada to-do list.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- === PRAKTIKUM: Todo List === -->
        <?php if ($project['tipe_tugas'] === 'praktikum'): ?>
            <div class="bg-white rounded-xl shadow p-6 card-hover">
                <h2 class="text-xl font-bold mb-4">Langkah Praktikum (To-Do List)</h2>
                <form action="?route=tugas/addTodo" method="POST" class="flex gap-2 mb-4">
                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                    <input type="text" name="deskripsi" placeholder="Tambahkan langkah..." class="flex-1 px-4 py-2 border rounded focus:ring-blue-500 focus:border-blue-500" required>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Tambah
                    </button>
                </form>

                <?php if (!empty($todos)): ?>
                    <ul class="space-y-3">
                        <?php foreach ($todos as $todo): ?>
                            <li class="p-3 rounded-lg flex justify-between items-center <?= $todo['is_done'] ? 'bg-green-100' : 'bg-gray-50'; ?>">
                                <form action="?route=tugas/editTodo" method="POST" class="flex items-center flex-1">
                                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                                    <input type="hidden" name="todo_id" value="<?= $todo['todo_id'] ?>">
                                    <input type="hidden" name="deskripsi" value="<?= htmlspecialchars($todo['deskripsi']) ?>">
                                    <input type="checkbox" name="is_done" value="1"
                                           <?= $todo['is_done'] ? 'checked' : '' ?>
                                           onchange="this.form.submit()" class="mr-3">
                                    <span class="<?= $todo['is_done'] ? 'line-through text-gray-500' : 'text-gray-800'; ?>">
                                        <?= htmlspecialchars($todo['deskripsi']) ?>
                                    </span>
                                </form>
                                <a href="?route=tugas/deleteTodo&todo_id=<?= $todo['todo_id'] ?>&project_id=<?= $project['id'] ?>"
                                   onclick="return confirm('Hapus langkah ini?')"
                                   class="text-red-500 hover:text-red-700 ml-3">
                                    Hapus
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-gray-500 italic">Belum ada langkah praktikum.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- === TEORI: Hanya Deskripsi === -->
        <?php if ($project['tipe_tugas'] === 'teori'): ?>
            <div class="bg-white rounded-xl shadow p-6 card-hover text-center py-10">
                <p class="text-gray-600">Tugas teori hanya berisi deskripsi.</p>
            </div>
        <?php endif; ?>

        <!-- Tombol Hapus Tugas (opsional, bisa dihapus jika tidak diinginkan) -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-right">
            <a href="?route=tugas/delete&project_id=<?= $project['id'] ?>" 
               onclick="return confirm('Yakin ingin menghapus tugas ini? Semua progress dan todo akan hilang!')"
               class="inline-block px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Hapus Tugas Ini
            </a>
        </div>

    </div>
</body>
</html>