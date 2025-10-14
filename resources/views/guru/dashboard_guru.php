<!-- resources/views/guru/dashboard_guru.php -->
<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'guru') {
    header("Location: ?route=auth/login");
    exit;
}

$users = $users ?? [];
$projects = $projects ?? [];
$totalSiswa = count($users);
$totalTugas = count($projects);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openModal() {
            document.getElementById('logout-modal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('logout-modal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mx-auto px-4 py-6">
    <!-- Header dengan tombol logout di kanan -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Guru</h1>
        <button onclick="openModal()"
                class="flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Logout
        </button>
    </div>

    <!-- Statistik Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <!-- Jumlah Siswa -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4 border-l-4 border-blue-500">
            <div class="bg-blue-100 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 11.292M12 19.646a8 8 0 110-16.292c0 2.368-.84 4.548-2.236 6.186a8.01 8.01 0 002.236 6.186z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Siswa</p>
                <p class="text-2xl font-bold text-gray-800"><?= $totalSiswa ?></p>
            </div>
        </div>

        <!-- Jumlah Tugas -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4 border-l-4 border-green-500">
            <div class="bg-green-100 p-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Tugas</p>
                <p class="text-2xl font-bold text-gray-800"><?= $totalTugas ?></p>
            </div>
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="mb-10">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Daftar Siswa</h2>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <?php if (!empty($users)): ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $index => $user): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= $index + 1 ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($user['nama_lengkap']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="?route=guru/tugas/list&user_id=<?= $user['id'] ?>" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 transition">
                                        Lihat Tugas
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="px-6 py-8 text-center text-gray-500">Belum ada siswa terdaftar.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Daftar Tugas Siswa -->
    <div class="mb-10">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Daftar Tugas Siswa</h2>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <?php if (!empty($projects)): ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Tugas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($proj['nama_siswa']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($proj['deadline']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?= $proj['status'] === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= htmlspecialchars($proj['status'] ?? 'Belum selesai') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="?route=guru/tugas/detail&user_id=<?= $proj['user_id'] ?>&project_id=<?= $proj['id'] ?>" 
                                       class="inline-flex items-center px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600 transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="px-6 py-8 text-center text-gray-500">Belum ada tugas yang dikumpulkan.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Logout -->
<div id="logout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari akun ini?</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeModal()"
                    class="px-4 py-2 text-gray-600 font-medium rounded hover:bg-gray-100 transition">
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