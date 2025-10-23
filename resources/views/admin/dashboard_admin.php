<?php
include __DIR__ . '/../components/edit-modal-user.php'
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin</title>
  <!-- ✅ Perbaiki spasi di CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .fade-in { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
  <script>
    // Fungsi untuk modal logout
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
    }
    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
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
<body class="bg-gray-100 min-h-screen font-sans">

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

  <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <h1 class="text-xl font-bold text-gray-800">Dashboard Admin</h1>
    <div class="flex items-center space-x-4">
      <span class="text-gray-600">Halo, <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin'); ?></span>
      <button onclick="openLogoutModal()" class="text-red-500 hover:text-red-600 font-medium transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded px-1">
        Logout
      </button>
    </div>
  </header>

  <main class="container mx-auto px-4 py-8">

    <!-- Statistik Card -->
    <h2 class="text-xl md:text-2xl font-bold mb-6 text-gray-800">Statistik Pengguna</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <div class="bg-white rounded-xl shadow p-5 text-center border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-sm mb-1">Total Pengguna</h3>
        <p class="text-2xl font-bold text-gray-800"><?= $totalUsers ?></p>
      </div>

      <?php foreach ($roleCounts as $r): ?>
        <?php
          $warna = match($r['peran']) {
            'admin' => 'red',
            'guru' => 'green',
            'siswa' => 'yellow',
            default => 'gray'
          };
        ?>
        <div class="bg-white rounded-xl shadow p-5 text-center border-l-4 border-<?= $warna ?>-500">
          <h3 class="text-gray-500 text-sm mb-1"><?= ucfirst($r['peran']) ?></h3>
          <p class="text-2xl font-bold text-gray-800"><?= $r['jumlah'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Bagian Diagram Batang dihapus -->

    <!-- Form Pencarian -->
    <form method="GET" class="mb-6 flex items-center">
      <input type="hidden" name="route" value="admin/users">
      <input type="text" name="search" placeholder="Cari user..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="flex-grow px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mr-2">
      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Cari</button>
    </form>

    <!-- Tabel User -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full border-collapse border border-gray-200 text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="py-3 px-4 border border-gray-200 text-left">#</th>
            <th class="py-3 px-4 border border-gray-200 text-left">Nama Lengkap</th>
            <th class="py-3 px-4 border border-gray-200 text-left">Email</th>
            <th class="py-3 px-4 border border-gray-200 text-left">Peran</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($users): $no=1; foreach ($users as $u): ?>
          <tr class="hover:bg-gray-50 transition">
            <td class="py-3 px-4 border border-gray-200"><?= $no++ ?></td>
            <td class="py-3 px-4 border border-gray-200"><?= htmlspecialchars($u['nama_lengkap']) ?></td>
            <td class="py-3 px-4 border border-gray-200"><?= htmlspecialchars($u['email']) ?></td>
            <td class="py-3 px-4 border border-gray-200">
                <span class="px-3 py-1 rounded-full text-xs font-medium 
                    <?= $u['peran'] === 'admin' ? 'bg-red-100 text-red-700' :
                       ($u['peran'] === 'guru' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700') ?>">
                    <?= htmlspecialchars(ucfirst($u['peran'])) ?>
                </span>
            </td>
             <td class="py-3 px-4 border border-gray-200 text-center">
        <button onclick='openEditUserModal(<?= json_encode($u) ?>)'
                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-sm">
            Edit
        </button>
    </td>
          </tr>
          <?php endforeach; else: ?>
          <tr>
              <td colspan="4" class="text-center py-6 text-gray-500 border border-gray-200">Tidak ada pengguna ditemukan.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>

  <!-- Modal Logout -->
  <div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari akun ini?</p>
        <div class="flex justify-end gap-3">
            <button onclick="closeLogoutModal()" class="px-4 py-2 text-gray-600 font-medium rounded hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-gray-300">
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