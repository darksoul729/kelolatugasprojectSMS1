<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Guru</title>
    <!-- ✅ Perbaiki spasi di CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-wrapper { overflow-x: auto; }
        .fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        function openCategoryModal() {
            document.getElementById('category-modal').classList.remove('hidden');
        }
        function closeCategoryModal() {
            document.getElementById('category-modal').classList.add('hidden');
        }
        function openLogoutModal() {
            document.getElementById('logout-modal').classList.remove('hidden');
        }
        function closeLogoutModal() {
            document.getElementById('logout-modal').classList.add('hidden');
        }

        // Auto-hide notification
        document.addEventListener('DOMContentLoaded', () => {
            const notif = document.getElementById('notifMessage');
            if (notif) {
                // Tambahkan sedikit delay sebelum memulai fade-out untuk efek yang lebih halus
                setTimeout(() => {
                    notif.style.opacity = '0';
                    setTimeout(() => notif.remove(), 500); // Hapus setelah animasi fade-out selesai
                }, 5000); // Hilang setelah 5 detik
            }
        });
    </script>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

<?php if (!empty($_SESSION['message'])): ?>
    <!-- Notifikasi Mengambang -->
    <div id="notifMessage" class="fixed top-4 left-1/2 transform -translate-x-1/2 max-w-md w-full z-50 transition-opacity duration-500 opacity-100">
        <div class="flex items-start gap-3 p-4 rounded-lg border shadow-lg
            <?php if ($_SESSION['message']['type'] === 'success'): ?>
                bg-green-50 border-green-200 text-green-700
            <?php else: ?>
                bg-red-50 border-red-200 text-red-700
            <?php endif; ?>">
            <?php if ($_SESSION['message']['type'] === 'success'): ?>
                <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            <?php else: ?>
                <svg class="w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            <?php endif; ?>
            <span class="font-medium text-sm"><?= htmlspecialchars($_SESSION['message']['text']) ?></span>
        </div>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="container mx-auto px-4 py-8">

    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Guru</h1>
            <p class="text-gray-600 mt-1">
                Selamat datang kembali, <?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? $_SESSION['user']['username'] ?? 'Guru') ?> 👋
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="?route=guru/tugas/tambah"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 shadow transition">
                + Buat Tugas Baru
            </a>
<button onclick="openProfileModal()"
        class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
  </svg>
</button>
        </div>
    </header>

    <!-- Statistik Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <!-- Total Kategori (dengan modal) -->
        <div 
            onclick="openCategoryModal()" 
            role="button" 
            tabindex="0"
            class="bg-white p-5 rounded-xl shadow hover:shadow-md transition cursor-pointer border-l-4 border-blue-500"
        >
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 11.292M12 19.646a8 8 0 110-16.292c0 2.368-.84 4.548-2.236 6.186a8.01 8.01 0 002.236 6.186z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Kategori</p>
                    <p class="text-xl font-bold text-gray-800"><?= count($kategori ?? []) ?></p>
                </div>
            </div>
        </div>

        <!-- Total Tugas -->
        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Tugas Dibuat</p>
                    <p class="text-xl font-bold text-gray-800"><?= count($tugas ?? []) ?></p>
                </div>
            </div>
        </div>

        <!-- Akun Anda -->
        <div class="bg-white p-5 rounded-xl shadow border-l-4 border-yellow-500">
            <div class="flex items-center gap-4">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20h9M3 20h9m-6-8h6a9 9 0 100-18H9a9 9 0 000 18z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Akun Anda</p>
                    <p class="text-xl font-bold text-gray-800"><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? '-') ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Daftar Tugas -->
    <section>
        <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4">Daftar Tugas Anda</h2>
        <a href="?route=tabel"> 7 Kebiasaaan Anak</a>
        <div class="bg-white rounded-xl shadow overflow-hidden table-wrapper">
            <?php if (!empty($kelas)): ?>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Siswa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kelas Siswa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catatan Guru</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($kelas as $i => $t): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-gray-600"><?= $i + 1 ?></td>
                                <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($t['user']) ?></td>
                                <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($t['kelas']) ?></td>
                                <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($t['nama_kategori'] ?? '-') ?></td>
                                <td class="px-4 py-3 text-center">
                                    <a href="?route=guru/tugas/detail&id=<?= $t['id_tugas'] ?>"
                                       class="px-3 py-1 bg-green-500 text-white text-xs rounded-md hover:bg-green-600 transition">
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

<!-- Modal: Jumlah Tugas per Kategori -->
<div id="category-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md max-h-[80vh] overflow-y-auto fade-in">
        <div class="p-5 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Jumlah Tugas per Kategori</h3>
                <button onclick="closeCategoryModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-5">
            <?php if (!empty($kategori)): ?>
                <ul class="space-y-3">
                    <?php foreach ($kategori as $kat): ?>
                        <?php
                        $jumlah = 0;
                        if (!empty($tugas)) {
                            foreach ($tugas as $t) {
                                if (($t['id_kategori'] ?? null) == ($kat['id_kategori'] ?? null)) {
                                    $jumlah++;
                                }
                            }
                        }
                        ?>
                        <li class="flex justify-between items-center bg-gray-50 px-4 py-2 rounded-lg">
                            <span class="font-medium text-gray-700"><?= htmlspecialchars($kat['nama_kategori']) ?></span>
                            <span class="bg-blue-100 text-blue-700 text-sm font-semibold px-2 py-1 rounded-full">
                                <?= $jumlah ?> tugas
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Belum ada kategori yang dibuat.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="profile-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
</div>

<script>
  // Buka modal profil & load konten dari file terpisah
  async function openProfileModal() {
    const modal = document.getElementById('profile-modal');
    if (modal.innerHTML.trim() === '') {
      try {
      const response = await fetch('/resources/views/components/profile-modal.php');
        const html = await response.text();
        modal.innerHTML = html;
      } catch (error) {
        console.error('Gagal memuat modal profil:', error);
        modal.innerHTML = '<div class="bg-white p-6 rounded-lg">Gagal memuat profil.</div>';
      }
    }
    modal.classList.remove('hidden');
  }

  // Tutup modal profil
  function closeProfileModal() {
    document.getElementById('profile-modal').classList.add('hidden');
  }

  // Tutup modal saat klik di luar konten
  document.getElementById('profile-modal').addEventListener('click', (e) => {
    if (e.target.id === 'profile-modal') closeProfileModal();
  });
</script>
</body>
</html>