<?php
require '../../includes/db_config.php'; // koneksi ke database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deskripsi = $_POST['deskripsi'] ?? '';
    $project_id = $_POST['project_id'] ?? '';
    $is_done = isset($_POST['is_done']) ? 1 : 0;

    if (!empty($deskripsi) && !empty($project_id)) {
        $stmt = $pdo->prepare("INSERT INTO todo_list (deskripsi, project_id, is_done) VALUES (:deskripsi, :project_id, :is_done)");
        $stmt->execute([
            ':deskripsi' => $deskripsi,
            ':project_id' => $project_id,
            ':is_done' => $is_done
        ]);
        $message = "✅ To-do berhasil ditambahkan.";
    } else {
        $message = "❌ Deskripsi dan Project wajib diisi.";
    }
}

// Ambil daftar project
$projects = $pdo->query("SELECT project_id, judul FROM projects ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah To-do List</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f7f7f7;
        }

        .form {
            background: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            max-width: 500px;
            margin: 40px auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        button {
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #333;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            color: green;
        }
    </style>
</head>
<body>

    <form class="form" method="POST" action="">
        <h2>Tambah To-Do</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <label for="deskripsi">Deskripsi Tugas:</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi tugas..." required></textarea>

        <label for="project_id">Pilih Proyek:</label>
        <select name="project_id" id="project_id" required>
            <option value="">-- Pilih Project --</option>
            <?php foreach ($projects as $project): ?>
                <option value="<?= $project['project_id'] ?>"><?= htmlspecialchars($project['judul']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>
            <input type="checkbox" name="is_done" value="1"> Sudah selesai?
        </label>

        <button type="submit">Tambah To-do</button>
    </form>

</body>
</html>
