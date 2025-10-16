<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Tugas Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-6">Buat Tugas Baru</h1>

    <form action="?route=guru/tugas/tambah" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded-xl shadow-md max-w-lg">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
            <input type="text" name="judul_tugas" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="id_kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
            <input type="date" name="tanggal_deadline" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran (opsional)</label>
            <input type="file" name="lampiran" class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Simpan Tugas
        </button>
    </form>
</body>
</html>
