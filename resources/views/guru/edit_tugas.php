<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas - <?= htmlspecialchars($tugas['judul_tugas']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<div class="max-w-4xl mx-auto my-10 bg-white shadow-lg rounded-xl p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">✏️ Edit Tugas</h1>
        <a href="?route=guru/dashboard" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            ← Kembali
        </a>
    </div>

    <form action="?route=guru/tugas/update&id=<?= $tugas['id_tugas'] ?>" 
          method="POST" enctype="multipart/form-data" class="space-y-5">

        <!-- Judul -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Judul Tugas</label>
            <input type="text" name="judul_tugas" required
                   value="<?= htmlspecialchars($tugas['judul_tugas']) ?>"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($tugas['deskripsi']) ?></textarea>
        </div>

        <!-- Kategori -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Kategori</label>
            <select name="id_kategori" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id_kategori'] ?>" 
                        <?= $k['id_kategori'] == $tugas['id_kategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tanggal -->
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Tanggal Mulai</label>
                <input type="datetime-local" name="tanggal_mulai"
                       value="<?= date('Y-m-d\TH:i', strtotime($tugas['tanggal_mulai'])) ?>"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Deadline</label>
                <input type="datetime-local" name="tanggal_deadline"
                       value="<?= date('Y-m-d\TH:i', strtotime($tugas['tanggal_deadline'])) ?>"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>
        </div>

        <!-- Durasi & Poin -->
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Durasi Estimasi</label>
                <input type="text" name="durasi_estimasi"
                       value="<?= htmlspecialchars($tugas['durasi_estimasi'] ?? '') ?>"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Poin Nilai</label>
                <input type="number" name="poin_nilai"
                       value="<?= htmlspecialchars($tugas['poin_nilai']) ?>"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>
        </div>

        <!-- Instruksi -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Instruksi Pengumpulan</label>
            <textarea name="instruksi_pengumpulan" rows="3"
                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($tugas['instruksi_pengumpulan'] ?? '') ?></textarea>
        </div>

        <!-- Lampiran -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Lampiran</label>
            <?php if (!empty($tugas['lampiran_guru'])): ?>
                <div class="mb-3 bg-gray-50 border rounded-lg p-3">
                    <p class="text-sm text-gray-600 mb-2">Lampiran saat ini:</p>
                    <a href="uploads/tugas/<?= htmlspecialchars($tugas['lampiran_guru']) ?>" 
                       target="_blank" class="text-blue-600 underline">
                       📎 <?= htmlspecialchars($tugas['lampiran_guru']) ?>
                    </a>
                </div>
            <?php endif; ?>
            <input type="file" name="lampiran_guru"
                   class="w-full text-sm border-gray-300 rounded-lg shadow-sm">
            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti lampiran.</p>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Status</label>
            <select name="status_tugas"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="aktif" <?= $tugas['status_tugas'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="nonaktif" <?= $tugas['status_tugas'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>

        <!-- Tombol -->
        <div class="flex justify-end gap-3 pt-5 border-t">
            <a href="?route=guru/dashboard"
               class="px-5 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Batal</a>
            <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                💾 Simpan Perubahan
            </button>
        </div>
    </form>
</div>

</body>
</html>
