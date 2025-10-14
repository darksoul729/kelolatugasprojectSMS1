<!-- resources/views/guru/list_tugas_siswa.php -->
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
    <title>Daftar Tugas Siswa</title>
    <!-- Gunakan hanya satu CDN, tanpa spasi -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mx-auto px-4 py-8">
    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="?route=guru/dashboard" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Judul Halaman -->
    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        Daftar Tugas Siswa: <span class="text-blue-600"></span>
    </h1>

    <!-- Daftar Tugas -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <?php if (!empty($projects)): ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Tugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($projects as $index => $proj): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= $index + 1 ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($proj['judul']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($proj['deadline']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $proj['status'] === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?= htmlspecialchars($proj['status'] ?? 'Belum selesai') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="?route=guru/tugas/detail&project_id=<?= $proj['id'] ?>&user_id=<?= $user['id'] ?>" 
                                   class="inline-flex items-center px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600 transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="px-6 py-12 text-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Belum ada tugas yang dikumpulkan oleh siswa ini.
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>