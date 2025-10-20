<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center py-10">

    <div class="w-full max-w-3xl bg-white shadow-lg rounded-2xl p-8">
        <h1 class="text-2xl font-bold text-blue-600 mb-4">
            📘 <?= htmlspecialchars($tugas['judul_tugas']) ?>
        </h1>

        <p class="text-gray-600 mb-4">
            <span class="font-semibold">Guru:</span> <?= htmlspecialchars($tugas['nama_guru']) ?>
        </p>

        <p class="text-gray-600 mb-4">
            <span class="font-semibold">Kategori:</span> <?= htmlspecialchars($tugas['nama_kategori']) ?>
        </p>

        <p class="text-gray-600 mb-4">
            <span class="font-semibold">Kelas:</span> <?= htmlspecialchars($tugas['kelas'] ?? '-') ?>
        </p>

        <div class="border-t border-gray-300 my-4"></div>

        <h2 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi Tugas</h2>
        <p class="text-gray-700 whitespace-pre-line mb-6">
            <?= nl2br(htmlspecialchars($tugas['deskripsi'] ?? 'Tidak ada deskripsi.')) ?>
        </p>

        <h2 class="text-lg font-semibold text-gray-800 mb-2">Instruksi Pengumpulan</h2>
        <p class="text-gray-700 whitespace-pre-line mb-6">
            <?= nl2br(htmlspecialchars($tugas['instruksi_pengumpulan'] ?? 'Tidak ada instruksi.')) ?>
        </p>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <p class="text-gray-700">
                <span class="font-semibold">Tanggal Mulai:</span>
                <?= htmlspecialchars(date('d M Y, H:i', strtotime($tugas['tanggal_mulai']))) ?>
            </p>
            <p class="text-gray-700">
                <span class="font-semibold">Deadline:</span>
                <span class="text-red-600"><?= htmlspecialchars(date('d M Y, H:i', strtotime($tugas['tanggal_deadline']))) ?></span>
            </p>
        </div>

        <p class="text-gray-700 mb-4">
            <span class="font-semibold">Poin Nilai:</span> <?= htmlspecialchars($tugas['poin_nilai']) ?>
        </p>

        <?php if (!empty($tugas['lampiran_guru'])): ?>
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <p class="font-semibold text-blue-600">📎 Lampiran:</p>
                <a href="uploads/<?= htmlspecialchars($tugas['lampiran_guru']) ?>" 
                   class="text-blue-500 underline" target="_blank">
                    <?= htmlspecialchars($tugas['lampiran_guru']) ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="flex justify-between items-center mt-6">
            <a href="?route=murid/tugas" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                ← Kembali
            </a>

            <a href="?route=murid/pengumpulan/add&id=<?= $tugas['id_tugas'] ?>"
               class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Kumpulkan Tugas
            </a>
        </div>
    </div>

</body>
</html>
