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

       <!-- Kelas (Edit) -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas (opsional)</label>
    <select 
        name="kelas" 
        id="kelas"
        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
        <option value="">— Pilih Kelas —</option>
        <?php
        $tingkatan = ['X', 'XI', 'XII'];
        $jurusan = ['PPLG', 'DKV', 'MPLB', 'TJKT'];

        foreach ($tingkatan as $t) {
            foreach ($jurusan as $j) {
                $kelasValue = "$t $j";
                $selected = ($tugas['kelas'] === $kelasValue) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($kelasValue) . "' $selected>" . htmlspecialchars($kelasValue) . "</option>";
            }
        }
        ?>
    </select>
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

 <?php
$folderLampiran = $this->basePath . '/public/uploads/tugas/';
$lampiran = $tugas['lampiran_guru'] ?? null;
$imageExtensions = ['jpg','jpeg','png','gif'];
$ext = $lampiran ? strtolower(pathinfo($lampiran, PATHINFO_EXTENSION)) : '';
$fileUrl = $lampiran ? '/../../public/uploads/tugas/' . urlencode($lampiran) : null;
?>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran Guru (opsional)</label>

    <?php if ($lampiran): ?>
        <?php if (in_array($ext, $imageExtensions)): ?>
            <div class="mb-2">
                <img src="<?= $fileUrl ?>" alt="<?= htmlspecialchars($lampiran) ?>" class="w-48 h-32 object-cover rounded-lg shadow">
            </div>
        <?php else: ?>
            <div class="mb-2 p-2 border border-gray-300 rounded-lg">
                <a href="<?= $fileUrl ?>" target="_blank"><?= htmlspecialchars($lampiran) ?></a>
            </div>
        <?php endif; ?>
        <!-- Hidden input untuk menyimpan nama file lama jika tidak diganti -->
        <input type="hidden" name="lampiran_lama" value="<?= htmlspecialchars($lampiran) ?>">
    <?php endif; ?>

    <input type="file" name="lampiran_guru"
           accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png,.gif"
           class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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

        <input type="hidden" name="lampiran_lama" value="<?= htmlspecialchars($tugas['lampiran_guru'] ?? '') ?>">


        <!-- Tombol -->
        <div class="flex justify-end gap-3 pt-5 border-t">
            <a href="?route=guru/dashboard"
               class="px-5 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Batal</a>
            <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

</body>
</html>
