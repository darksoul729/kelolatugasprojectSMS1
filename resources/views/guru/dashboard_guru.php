

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-wrapper { overflow-x: auto; }
    </style>
    <script>
        function openModal() { document.getElementById('logout-modal').classList.remove('hidden'); }
        function closeModal() { document.getElementById('logout-modal').classList.add('hidden'); }
    </script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<!-- Container utama -->
<div class="container mx-auto px-4 py-8">

    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Guru</h1>
            <p class="text-gray-500 text-sm mt-1">
    Selamat datang kembali, <?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? $_SESSION['user']['username'] ?? 'Guru') ?> 👋
</p>

        </div>

        <div class="flex flex-wrap gap-3">
            <a href="?route=guru/tugas/tambah" 
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 shadow transition">
                + Buat Tugas Baru
            </a>
                            <a href="/includes/logout.php" class="px-4 py-2 bg-danger-red text-background-white rounded-lg hover:bg-red-600 transition">Logout</a>

        </div>
    </header>

    <!-- Statistik -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4 border-l-4 border-blue-500">
            <div class="bg-blue-100 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 11.292M12 19.646a8 8 0 110-16.292c0 2.368-.84 4.548-2.236 6.186a8.01 8.01 0 002.236 6.186z"/></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Kategori</p>
                <p class="text-2xl font-bold text-gray-800"><?= count($kategori ?? []) ?></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4 border-l-4 border-green-500">
            <div class="bg-green-100 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Tugas Dibuat</p>
                <p class="text-2xl font-bold text-gray-800"><?= count($tugas ?? []) ?></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4 border-l-4 border-yellow-500">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-yellow-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 20h9M3 20h9m-6-8h6a9 9 0 100-18H9a9 9 0 000 18z"/></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Akun Anda</p>
                <p class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($_SESSION['user']['username'] ?? '-') ?></p>
            </div>
        </div>
    </section>

    <!-- Daftar Tugas -->
    <section>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Daftar Tugas Anda</h2>

        <div class="bg-white rounded-xl shadow table-wrapper">
            <?php if (!empty($tugas)): ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Judul Tugas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Poin</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($tugas as $i => $t): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600"><?= $i + 1 ?></td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($t['judul_tugas']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($t['nama_kategori'] ?? '-') ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($t['tanggal_deadline']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($t['poin_nilai']) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <a href="?route=guru/tugas/detail&id=<?= $t['id_tugas'] ?>"
                                       class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600 transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="px-6 py-10 text-center text-gray-500">
                    Belum ada tugas yang Anda buat.
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- Modal Logout -->
<div id="logout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari akun ini?</p>
        <div class="flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 text-gray-600 font-medium rounded hover:bg-gray-100 transition">
                Batal
            </button>
            <a href="?route=auth/logout" 
               class="px-4 py-2 bg-red-500 text-white font-medium rounded hover:bg-red-600 transition">
                Logout
            </a>
        </div>
    </div>
</div>

</body>
</html>
