<!-- resources/views/guru/detail_tugas.php -->
<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'guru') {
    header("Location: ?route=auth/login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Tugas Siswa</title>
    <!-- Perbaiki URL: hapus spasi ekstra -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="?route=guru/dashboard" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-8">Detail Tugas</h1>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($project['judul'] ?? '—') ?></h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    <?= $project['status'] === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                    <?= htmlspecialchars($project['status'] ?? 'Belum selesai') ?>
                </span>
            </div>

            <?php if (!empty($project['nama_siswa'])): ?>
                <div class="mb-4 flex items-center text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Siswa: <strong><?= htmlspecialchars($project['nama_siswa']) ?></strong></span>
                </div>
            <?php endif; ?>

            <div class="space-y-4 text-gray-700">
                <div>
                    <p class="font-medium text-gray-900">Deskripsi</p>
                    <p class="mt-1"><?= nl2br(htmlspecialchars($project['deskripsi'] ?? 'Tidak ada deskripsi.')) ?></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                    <div>
                        <p class="text-sm text-gray-500">Deadline</p>
                        <p class="font-medium"><?= htmlspecialchars($project['deadline'] ?? '—') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dibuat pada</p>
                        <p class="font-medium"><?= htmlspecialchars($project['created_at'] ?? '—') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>