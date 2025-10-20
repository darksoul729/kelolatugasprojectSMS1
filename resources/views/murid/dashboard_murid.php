<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Siswa</title>
  <!-- ✅ Perbaiki spasi di CDN Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Tambahkan CDN Heroicons -->
  <script src="https://cdn.jsdelivr.net/npm/heroicons@2.1.5/24/outline.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/heroicons@2.1.5/20/solid.min.js"></script>
  <style>
    /* Opsional: Jika kamu ingin menggunakan ikon sebagai inline SVG, kamu bisa mengatur ukurannya di sini */
    .hero-icon {
      width: 1em;
      height: 1em;
      display: inline;
      vertical-align: middle;
    }
    .fade-in { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
  <script>
    // Fungsi untuk membuka modal
    function openModal() {
        document.getElementById('logout-modal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        document.getElementById('logout-modal').classList.add('hidden');
    }

    // Auto-hide notification
    document.addEventListener('DOMContentLoaded', () => {
        const notif = document.getElementById('notifMessage');
        if (notif) {
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

  <!-- Header (Fixed) -->
  <header id="dashboard-header" class="fixed top-0 left-0 right-0 bg-white shadow-md z-10">
    <div class="container mx-auto px-4 sm:px-6 py-3 flex justify-between items-center">
      <!-- Ikon Dashboard -->
      <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-800">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
        </svg>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Dashboard Siswa</h1>
      </div>
      <!-- Tombol untuk membuka modal -->
      <button onclick="openModal()"
              class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
         Logout
      </button>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-4 sm:px-6 py-20"> <!-- py-20 untuk offset header fixed -->
    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Daftar Tugas Kamu</h2>

    <?php if (empty($tugas)): ?>
      <div class="bg-white p-8 rounded-xl shadow text-center">
        <p class="text-gray-500 text-lg">Belum ada tugas dari guru.</p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($tugas as $t): ?>
          <?php
            // 🔹 Warna status tugas (disesuaikan dengan Tailwind standar)
            $statusTugasColor = match ($t['status_tugas'] ?? '') {
              'aktif' => 'bg-green-100 text-green-700',
              'selesai' => 'bg-blue-100 text-blue-700',
              'nonaktif' => 'bg-gray-100 text-gray-600',
              default => 'bg-yellow-100 text-yellow-700',
            };

            // 🔹 Warna status pengumpulan
            $statusKumpulClass = match ($t['status_pengumpulan'] ?? '') {
              'selesai' => 'text-green-700 bg-green-100',
              'terlambat' => 'text-red-700 bg-red-100',
              default => 'text-gray-600 bg-gray-100'
            };
          ?>

          <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all border border-gray-100 flex flex-col h-full">
            <div class="flex justify-between items-start mb-2"> <!-- items-start agar judul tetap atas -->
              <h3 class="text-lg font-semibold text-blue-600 flex-1 pr-2"> <!-- flex-1 dan pr-2 agar tidak overlap dengan badge -->
                <?= htmlspecialchars($t['judul_tugas']) ?>
              </h3>
              <span class="text-xs font-semibold px-3 py-1 rounded-full inline-flex items-center <?= $statusTugasColor ?>">
                <?= ucfirst($t['status_tugas'] ?? 'Tidak diketahui') ?>
              </span>
            </div>

            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
              <?= htmlspecialchars($t['deskripsi']) ?>
            </p>

            <div class="text-sm text-gray-500 space-y-1 mb-4"> <!-- mb-4 untuk jarak sebelum status pengumpulan -->
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <strong>Guru:</strong> <?= htmlspecialchars($t['nama_guru']) ?>
              </div>
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                </svg>
                <strong>Kategori:</strong> <?= htmlspecialchars($t['nama_kategori']) ?>
              </div>
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2 text-orange-600"> <!-- Warna khusus untuk deadline -->
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <strong>Deadline:</strong>
                <span class="text-orange-600 font-medium ml-1"><?= htmlspecialchars($t['tanggal_deadline']) ?></span> <!-- ml-1 untuk sedikit jarak -->
              </div>
            </div>

            <!-- Status pengumpulan -->
            <p class="text-sm font-medium px-3 py-1 inline-block rounded-full <?= $statusKumpulClass ?> mb-4"> <!-- mb-4 untuk jarak sebelum tombol -->
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline mr-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.25V11.25a9 9 0 0 0-9-9Z" />
              </svg>
              <?= $t['status_pengumpulan']
                    ? 'Sudah dikumpulkan ('.ucfirst($t['status_pengumpulan']).')'
                    : 'Belum dikumpulkan' ?>
            </p>

            <!-- Tombol detail -->
            <a href="?route=murid/tugas/detail&id=<?= $t['id_tugas'] ?>"
               class="mt-auto inline-block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              Lihat Detail
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <!-- Modal Logout -->
  <div id="logout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari akun ini?</p>
        <div class="flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 text-gray-600 font-medium rounded hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-gray-300">
                Batal
            </button>
            <a href="?route=auth/logout"
               class="px-4 py-2 bg-red-500 text-white font-medium rounded hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Logout
            </a>
        </div>
    </div>
  </div>


</body>
</html>