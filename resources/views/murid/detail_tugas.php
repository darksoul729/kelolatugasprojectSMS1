<!-- views/tugas_murid/detail_tugas.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tugas</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>📄 Detail Tugas: <?= htmlspecialchars($project['judul']) ?></h1>
    <p><b>Deskripsi:</b> <?= nl2br(htmlspecialchars($project['deskripsi'])) ?></p>
    <p><b>Deadline:</b> <?= htmlspecialchars($project['deadline']) ?></p>
    <hr>

    <h2>📈 Progress</h2>
    <form action="?route=tugas/addProgress" method="POST">
        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
        <textarea name="keterangan" placeholder="Tambah progress..." required></textarea>
        <button type="submit">Tambah</button>
    </form>

    <?php if (!empty($progress_updates)): ?>
        <ul>
            <?php foreach ($progress_updates as $update): ?>
                <li>
                    <?= nl2br(htmlspecialchars($update['keterangan'])) ?>
                    <a href="?route=tugas/deleteProgress&update_id=<?= $update['id'] ?>&project_id=<?= $project['id'] ?>" onclick="return confirm('Hapus progress ini?')">🗑</a>
                </li>
            <?php endforeach ?>
        </ul>
    <?php else: ?>
        <p>Belum ada progress.</p>
    <?php endif; ?>

    <hr>

    <h2>✅ To-Do List</h2>
    <form action="?route=tugas/addTodo" method="POST">
        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
        <input type="text" name="deskripsi" placeholder="Tambahkan kegiatan..." required>
        <button type="submit">Tambah</button>
    </form>

    <?php if (!empty($todos)): ?>
        <ul>
            <?php foreach ($todos as $todo): ?>
                <li>
                    <form action="?route=tugas/editTodo" method="POST" style="display:inline;">
                        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                        <input type="hidden" name="todo_id" value="<?= $todo['id'] ?>">
                        <input type="checkbox" name="is_done" value="1" <?= $todo['is_done'] ? 'checked' : '' ?> onchange="this.form.submit()">
                        <?= htmlspecialchars($todo['deskripsi']) ?>
                    </form>
                    <a href="?route=tugas/deleteTodo&todo_id=<?= $todo['id'] ?>&project_id=<?= $project['id'] ?>" onclick="return confirm('Hapus to-do ini?')">🗑</a>
                </li>
            <?php endforeach ?>
        </ul>
    <?php else: ?>
        <p>Belum ada to-do list.</p>
    <?php endif; ?>

    <hr>
    <a href="?route=tugas/list">← Kembali ke daftar tugas</a>
</body>
</html>
