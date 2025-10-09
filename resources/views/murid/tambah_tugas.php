<!-- views/tugas_murid/tambah_tugas.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>📝 Tambah Tugas Baru</h1>

    <form action="?route=tugas/store" method="POST">
        <label>Judul:</label><br>
        <input type="text" name="judul" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" required></textarea><br><br>

        <label>Deadline:</label><br>
        <input type="date" name="deadline" required><br><br>

        <button type="submit">Simpan</button>
        <a href="?route=tugas/list">Batal</a>
    </form>
</body>
</html>
