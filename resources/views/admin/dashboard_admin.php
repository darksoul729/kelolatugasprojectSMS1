<?php
// --- Pagination setup ---
$perPage = 10;
$totalData = count($users);
$totalPages = ceil($totalData / $perPage);
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($currentPage - 1) * $perPage;

// Ambil data user untuk halaman ini
$usersToShow = array_slice($users, $offset, $perPage);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* Animasi untuk notifikasi */
    @keyframes slideIn {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }
    
    .notification {
      animation: slideIn 0.3s ease-out;
    }
    
    .notification.fade-out {
      animation: fadeOut 0.5s ease-in forwards;
    }
    
    /* Hover effects */
    .stat-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Gradient backgrounds for stats */
    .gradient-admin { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .gradient-guru { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .gradient-siswa { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .gradient-total { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    
    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }

    /* Smooth modal animation */
    .modal-overlay {
      transition: opacity 0.3s ease;
    }
    
    .modal-overlay.hidden {
      opacity: 0;
      pointer-events: none;
    }
    
    .modal-overlay:not(.hidden) {
      opacity: 1;
    }

    /* Custom scrollbar untuk modal */
    .modal-scrollbar::-webkit-scrollbar {
      width: 6px;
    }

    .modal-scrollbar::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .modal-scrollbar::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }

    .modal-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
  <!-- ✅ Notifikasi yang lebih menarik -->
  <?php if (!empty($_SESSION['message'])): ?>
    <div id="notifMessage" class="notification fixed top-4 left-1/2 transform -translate-x-1/2 max-w-md w-full z-50">
      <div class="flex items-center gap-3 p-4 rounded-xl border shadow-lg
        <?= $_SESSION['message']['type'] === 'success' 
            ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200 text-green-800' 
            : 'bg-gradient-to-r from-red-50 to-pink-50 border-red-200 text-red-800' ?>">
        <div class="flex-shrink-0">
          <?php if ($_SESSION['message']['type'] === 'success'): ?>
            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
              <i class="fas fa-check text-green-600"></i>
            </div>
          <?php else: ?>
            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
              <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
          <?php endif; ?>
        </div>
        <div class="flex-1">
          <p class="font-medium"><?= htmlspecialchars($_SESSION['message']['text']) ?></p>
        </div>
        <button onclick="closeNotification()" class="text-gray-400 hover:text-gray-600">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <!-- Header yang lebih menarik -->
  <header class="bg-white shadow-md py-4 px-4 md:px-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 border-b border-gray-200">
    <div class="flex items-center space-x-3">
      <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
        <i class="fas fa-user-shield text-white"></i>
      </div>
      <div>
        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-500 text-sm">Kelola data pengguna dengan mudah</p>
      </div>
    </div>
    <div class="flex items-center space-x-4">
      <div class="text-right">
        <span class="text-gray-600 text-sm">Halo,</span>
        <p class="font-medium text-gray-800"><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin'); ?></p>
      </div>
      <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
        <i class="fas fa-user text-blue-600"></i>
      </div>
      <button onclick="openLogoutModal()" class="text-red-500 hover:text-red-600 font-medium text-sm transition flex items-center gap-2">
        <i class="fas fa-sign-out-alt"></i>
        <span class="hidden md:inline">Logout</span>
      </button>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-6 md:py-8">
    <!-- Statistik dengan desain lebih menarik -->
    <div class="mb-8">
      <h2 class="text-xl md:text-2xl font-bold mb-4 text-gray-800 flex items-center gap-2">
        <i class="fas fa-chart-bar text-blue-600"></i>
        Statistik Pengguna
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card bg-white rounded-2xl shadow-lg p-5 text-center border-l-4 border-blue-500">
          <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-users text-blue-600 text-lg"></i>
          </div>
          <h3 class="text-gray-500 text-sm mb-1">Total Pengguna</h3>
          <p class="text-2xl md:text-3xl font-bold text-gray-800"><?= $totalUsers ?></p>
        </div>

        <?php foreach ($roleCounts as $r): 
          $warna = match($r['peran']) {
            'admin' => 'red',
            'guru' => 'green',
            'siswa' => 'yellow',
            default => 'gray'
          };
        ?>
        <div class="stat-card bg-white rounded-2xl shadow-lg p-5 text-center border-l-4 border-<?= $warna ?>-500">
          <div class="w-12 h-12 rounded-full bg-<?= $warna ?>-100 flex items-center justify-center mx-auto mb-3">
            <i class="fas <?= 
              $r['peran'] === 'admin' ? 'fa-user-shield' : 
              ($r['peran'] === 'guru' ? 'fa-chalkboard-teacher' : 'fa-user-graduate') 
            ?> text-<?= $warna ?>-600 text-lg"></i>
          </div>
          <h3 class="text-gray-500 text-sm mb-1"><?= ucfirst($r['peran']) ?></h3>
          <p class="text-2xl md:text-3xl font-bold text-gray-800"><?= $r['jumlah'] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Pencarian dan Filter -->
    <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <form method="GET" class="flex flex-col sm:flex-row gap-2">
            <input type="hidden" name="route" value="admin/users">
            <div class="relative flex-grow">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
              <input type="text" name="search" placeholder="Cari berdasarkan nama atau email..."
                     value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                     class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition whitespace-nowrap flex items-center justify-center gap-2">
              <i class="fas fa-search"></i>
              <span>Cari</span>
            </button>
          </form>
        </div>
        <div class="flex items-center gap-2">
          <button id="bulkVerifyBtn" 
                  class="px-4 py-3 bg-green-500 text-white rounded-xl hover:bg-green-600 transition text-sm flex items-center gap-2">
            <i class="fas fa-check-double"></i>
            <span>Verifikasi Terpilih</span>
          </button>
        </div>
      </div>

      <!-- Form Import Excel -->
      <div class="mt-6 border-t pt-6">
        <form action="?route=admin/users/import" method="post" enctype="multipart/form-data" id="importForm">
          <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
              <div class="flex-1">
                <label class="block text-sm font-medium text-blue-800 mb-2">
                  <i class="fas fa-file-excel mr-2"></i>Import Data Siswa dari Excel
                </label>
                <div class="flex flex-col sm:flex-row gap-3">
                  <input type="file" name="excel_file" 
                         accept=".xlsx,.xls,.csv" 
                         required 
                         class="flex-1 px-4 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                  <div class="flex gap-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 whitespace-nowrap">
                      <i class="fas fa-upload"></i>
                      <span>Import Data</span>
                    </button>
                    <a href="?route=admin/users/download_template" 
                       class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition flex items-center gap-2 whitespace-nowrap">
                      <i class="fas fa-download"></i>
                      <span>Download Template</span>
                    </a>
                  </div>
                </div>
                <p class="text-sm text-blue-600 mt-2">
                  Format file: .xlsx, .xls, atau .csv. Template berisi kolom: NO, NAMA LENGKAP, KELAS, EMAIL AKTIF
                </p>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabel Pengguna dengan desain lebih baik -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
          <i class="fas fa-table"></i>
          Daftar Pengguna
        </h3>
        <span class="text-sm text-gray-500">
          Menampilkan <?= count($usersToShow) ?> dari <?= $totalData ?> pengguna
        </span>
      </div>
      
      <div class="overflow-x-auto custom-scrollbar">
        <table class="min-w-full border-collapse text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="py-3 px-4 text-center w-12">
                <input type="checkbox" id="selectAll" class="rounded text-blue-600 focus:ring-blue-500">
              </th>
              <th class="py-3 px-4 text-left font-semibold text-gray-700">Nama Lengkap</th>
              <th class="py-3 px-4 text-left font-semibold text-gray-700">Email</th>
              <th class="py-3 px-4 text-left font-semibold text-gray-700">Peran</th>
              <th class="py-3 px-4 text-center font-semibold text-gray-700">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
          <?php if (count($usersToShow) > 0): ?>
            <?php foreach ($usersToShow as $index => $user): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 text-center">
                <?php if ($user['peran'] === 'belum_verifikasi'): ?>
                  <input type="checkbox" name="verify_ids[]" value="<?= $user['id_user'] ?>" class="verify-checkbox rounded text-blue-600 focus:ring-blue-500">
                <?php else: ?>
                  <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center mx-auto">
                    <i class="fas fa-check text-gray-400 text-xs"></i>
                  </div>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user text-blue-600 text-sm"></i>
                  </div>
                  <div>
                    <p class="font-medium text-gray-800"><?= htmlspecialchars($user['nama_lengkap']) ?></p>
                    <p class="text-xs text-gray-500">ID: <?= htmlspecialchars($user['id_user']) ?></p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3">
                <p class="text-gray-800"><?= htmlspecialchars($user['email']) ?></p>
              </td>
              <td class="px-4 py-3">
                <?php if ($user['peran'] === 'belum_verifikasi'): ?>
                  <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full">
                    <i class="fas fa-clock"></i>
                    Belum Verifikasi
                  </span>
                <?php else: ?>
                  <?php 
                  $roleColors = [
                    'admin' => 'bg-red-100 text-red-700',
                    'guru' => 'bg-green-100 text-green-700', 
                    'siswa' => 'bg-yellow-100 text-yellow-700'
                  ];
                  $colorClass = $roleColors[$user['peran']] ?? 'bg-gray-100 text-gray-700';
                  ?>
                  <span class="inline-flex items-center gap-1 <?= $colorClass ?> text-xs font-semibold px-3 py-1 rounded-full">
                    <i class="fas <?= 
                      $user['peran'] === 'admin' ? 'fa-user-shield' : 
                      ($user['peran'] === 'guru' ? 'fa-chalkboard-teacher' : 'fa-user-graduate') 
                    ?>"></i>
                    <?= ucfirst($user['peran']) ?>
                  </span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3">
                <div class="flex justify-center gap-2">
                  <?php if ($user['peran'] !== 'belum_verifikasi'): ?>
                    <button onclick='fetchUserAndOpenModal(<?= $user["id_user"] ?>)' 
                            class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-2 rounded-lg flex items-center gap-1 transition">
                      <i class="fas fa-edit"></i>
                      <span>Edit</span>
                    </button>
                    <button onclick="openDeleteModal(<?= $user['id_user'] ?>)" 
                            class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-2 rounded-lg flex items-center gap-1 transition">
                      <i class="fas fa-trash"></i>
                      <span>Hapus</span>
                    </button>
                  <?php else: ?>
                    <button onclick="openVerifyModal(<?= $user['id_user'] ?>)" 
                            class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-2 rounded-lg flex items-center gap-1 transition">
                      <i class="fas fa-check"></i>
                      <span>Verifikasi</span>
                    </button>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="px-4 py-8 text-center">
                <div class="flex flex-col items-center justify-center text-gray-500">
                  <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                  <p class="text-lg font-medium">Tidak ada data pengguna</p>
                  <p class="text-sm mt-1">Coba ubah kata kunci pencarian atau filter</p>
                </div>
              </td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination dengan desain lebih baik -->
    <?php if ($totalPages > 1): ?>
      <div class="flex flex-wrap justify-center items-center gap-2 mt-8">
        <?php if ($currentPage > 1): ?>
          <a href="?route=admin/users&page=<?= $currentPage - 1 ?>"
             class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition flex items-center gap-1">
            <i class="fas fa-chevron-left"></i>
            <span>Sebelumnya</span>
          </a>
        <?php endif; ?>
        
        <div class="flex items-center gap-1">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 1 && $i <= $currentPage + 1)): ?>
              <a href="?route=admin/users&page=<?= $i ?>"
                 class="px-4 py-2 rounded-lg border transition text-sm font-medium <?= $i == $currentPage ? 'bg-blue-600 text-white border-blue-600' : 'text-gray-700 border-gray-300 hover:bg-gray-100' ?>">
                <?= $i ?>
              </a>
            <?php elseif ($i == $currentPage - 2 || $i == $currentPage + 2): ?>
              <span class="px-2 text-gray-500">...</span>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
        
        <?php if ($currentPage < $totalPages): ?>
          <a href="?route=admin/users&page=<?= $currentPage + 1 ?>"
             class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition flex items-center gap-1">
            <span>Selanjutnya</span>
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </main>

  <!-- Include Modals -->
  <?php include __DIR__ . '/../components/edit-modal-user.php'; ?>
  <?php include __DIR__ . '/../components/delete-modal-user.php'; ?>
  <?php include __DIR__ . '/../components/verify-modal-user.php'; ?>

  <!-- Modal Hasil Import -->
  <div id="importResultModal" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden transform transition-all">
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fas fa-file-excel text-green-600"></i>
          </div>
          <div>
            <h2 class="text-xl font-semibold text-gray-800">Hasil Import Data Siswa</h2>
            <p class="text-sm text-gray-500" id="importSummaryText"></p>
          </div>
        </div>
        <button onclick="closeImportResultModal()" class="text-gray-400 hover:text-gray-600 transition">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      
      <div class="p-6 overflow-auto max-h-[60vh] modal-scrollbar">
        <!-- Section Success -->
        <div id="successSection" class="mb-6">
          <h3 class="text-lg font-semibold text-green-700 mb-3 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>Data Berhasil Diimport</span>
            <span id="successCount" class="bg-green-100 text-green-800 text-sm px-2 py-1 rounded-full"></span>
          </h3>
          <div class="bg-green-50 border border-green-200 rounded-lg overflow-hidden">
            <table class="min-w-full">
              <thead class="bg-green-100">
                <tr>
                  <th class="py-3 px-4 text-left text-sm font-semibold text-green-800">No</th>
                  <th class="py-3 px-4 text-left text-sm font-semibold text-green-800">Nama Siswa</th>
                  <th class="py-3 px-4 text-left text-sm font-semibold text-green-800">Email</th>
                  <th class="py-3 px-4 text-left text-sm font-semibold text-green-800">Kelas</th>
                  <th class="py-3 px-4 text-left text-sm font-semibold text-green-800">Password</th>
                </tr>
              </thead>
              <tbody id="successTableBody" class="divide-y divide-green-200">
                <!-- Data akan diisi oleh JavaScript -->
              </tbody>
            </table>
          </div>
        </div>

        <!-- Section Errors -->
        <div id="errorSection" class="hidden">
          <h3 class="text-lg font-semibold text-red-700 mb-3 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Data Gagal Diimport</span>
            <span id="errorCount" class="bg-red-100 text-red-800 text-sm px-2 py-1 rounded-full"></span>
          </h3>
          <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <ul id="errorList" class="space-y-2 text-sm text-red-700">
              <!-- Error akan diisi oleh JavaScript -->
            </ul>
          </div>
        </div>

        <!-- Password Warning -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4">
          <div class="flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
            <div>
              <h4 class="font-semibold text-yellow-800">Penting!</h4>
              <p class="text-sm text-yellow-700 mt-1">
                Simpan informasi password di tempat yang aman. Password tidak dapat dilihat lagi setelah modal ini ditutup.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
        <button onclick="closeImportResultModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
          Tutup
        </button>
        <button onclick="downloadPasswordList()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center gap-2">
          <i class="fas fa-download"></i>
          Download Daftar Password
        </button>
      </div>
    </div>
  </div>

  <!-- Logout Modal yang lebih menarik -->
  <div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
          <i class="fas fa-sign-out-alt text-red-600"></i>
        </div>
        <h2 class="text-xl font-semibold text-gray-800">Konfirmasi Logout</h2>
      </div>
      <p class="text-gray-600 mb-6">Apakah Anda yakin ingin logout dari sistem?</p>
      <div class="flex justify-end space-x-3">
        <button onclick="closeLogoutModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
          Batal
        </button>
        <a href="?route=auth/logout" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium flex items-center gap-2">
          <i class="fas fa-sign-out-alt"></i>
          Ya, Logout
        </a>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // Fungsi untuk notifikasi
    function closeNotification() {
      const notif = document.getElementById('notifMessage');
      if (notif) {
        notif.classList.add('fade-out');
        setTimeout(() => notif.remove(), 500);
      }
    }
    
    // Auto-hide notifikasi setelah 5 detik
    document.addEventListener('DOMContentLoaded', () => {
      const notif = document.getElementById('notifMessage');
      if (notif) {
        setTimeout(() => {
          closeNotification();
        }, 5000);
      }
    });

    // Fungsi untuk modal
    function fetchUserAndOpenModal(id) {
      fetch(`?route=admin/users/edit&id=${id}`)
          .then(res => res.json())
          .then(res => {
              if (!res.success) return alert(res.message);
              openEditUserModal(res.data);
          }).catch(err => {
              console.error(err);
              alert('Gagal mengambil data user');
          });
    }

    function openEditUserModal(user) {
      const modal = document.getElementById('edit-user-modal');
      const peranInput = document.getElementById('edit-peran');
      const waliKelasInput = document.getElementById('edit-wali-kelas').parentElement;
      const kelasInput = document.getElementById('edit-kelas').parentElement;

      document.getElementById('edit-user-id').value = user.id_user;
      document.getElementById('edit-nama-lengkap').value = user.nama_lengkap;
      document.getElementById('edit-email').value = user.email;
      peranInput.value = user.peran;
      document.getElementById('edit-wali-kelas').value = user.wali_kelas || '';
      document.getElementById('edit-kelas').value = user.kelas || '';
      document.getElementById('edit-nip-nis').value = user.nip_nis || '';

      function toggleInputs() {
        if (peranInput.value === 'siswa') {
          waliKelasInput.classList.add('hidden');
          kelasInput.classList.remove('hidden');
        } else if (peranInput.value === 'guru') {
          kelasInput.classList.add('hidden');
          waliKelasInput.classList.remove('hidden');
        } else {
          kelasInput.classList.remove('hidden');
          waliKelasInput.classList.remove('hidden');
        }
      }
      toggleInputs();
      peranInput.addEventListener('change', toggleInputs);
      modal.classList.remove('hidden');
    }

    function closeEditUserModal() { document.getElementById('edit-user-modal').classList.add('hidden'); }
    function openDeleteModal(id) {
      const m = document.getElementById('deleteModal');
      document.getElementById('confirmDeleteBtn').href = `?route=admin/users/delete&id=${id}`;
      m.classList.remove('hidden');
    }
    function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); }
    function openLogoutModal() { document.getElementById('logoutModal').classList.remove('hidden'); }
    function closeLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); }

    function openVerifyModal(ids) {
      const modal = document.getElementById('verifyModal');
      const roleSelect = document.getElementById('verify-peran');
      const waliContainer = document.getElementById('waliKelasContainer');

      // Reset modal
      roleSelect.value = 'siswa';
      document.getElementById('verify-wali-kelas').value = '';
      waliContainer.classList.add('hidden');
      document.getElementById('verify-wali-kelas').required = false;

      // Hapus input hidden lama
      document.querySelectorAll('input[name="id_user[]"]').forEach(el => el.remove());

      if (Array.isArray(ids)) {
        ids.forEach(id => {
          const hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = 'id_user[]';
          hidden.value = id;
          modal.querySelector('form').appendChild(hidden);
        });
      } else {
        document.getElementById('verify-user-id').value = ids;
      }

      // Toggle wali kelas saat peran berubah
      roleSelect.onchange = () => {
        if (roleSelect.value === 'guru') {
          waliContainer.classList.remove('hidden');
          document.getElementById('verify-wali-kelas').required = true;
        } else {
          waliContainer.classList.add('hidden');
          document.getElementById('verify-wali-kelas').required = false;
        }
      };
      roleSelect.dispatchEvent(new Event('change'));
      modal.classList.remove('hidden');
    }

    function closeVerifyModal() { document.getElementById('verifyModal').classList.add('hidden'); }

    // Checkbox select all
    document.getElementById('selectAll').addEventListener('change', function() {
      document.querySelectorAll('.verify-checkbox').forEach(cb => cb.checked = this.checked);
    });

    // Bulk verify button
    document.getElementById('bulkVerifyBtn').addEventListener('click', function() {
      const selectedIds = Array.from(document.querySelectorAll('.verify-checkbox:checked'))
                               .map(cb => cb.value);

      if (selectedIds.length === 0) {
        // Notifikasi custom untuk error
        showCustomNotification('Pilih minimal 1 user untuk diverifikasi!', 'error');
        return;
      }

      openVerifyModal(selectedIds);
    });
    
    // Fungsi untuk notifikasi custom
    function showCustomNotification(message, type = 'info') {
      const notification = document.createElement('div');
      notification.className = `notification fixed top-4 left-1/2 transform -translate-x-1/2 max-w-md w-full z-50`;
      
      const bgColor = type === 'error' ? 'bg-gradient-to-r from-red-50 to-pink-50 border-red-200 text-red-800' : 
                     type === 'success' ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200 text-green-800' :
                     'bg-gradient-to-r from-blue-50 to-cyan-50 border-blue-200 text-blue-800';
      
      notification.innerHTML = `
        <div class="flex items-center gap-3 p-4 rounded-xl border shadow-lg ${bgColor}">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 rounded-full ${type === 'error' ? 'bg-red-100' : type === 'success' ? 'bg-green-100' : 'bg-blue-100'} flex items-center justify-center">
              <i class="fas ${type === 'error' ? 'fa-exclamation-triangle text-red-600' : type === 'success' ? 'fa-check text-green-600' : 'fa-info-circle text-blue-600'}"></i>
            </div>
          </div>
          <div class="flex-1">
            <p class="font-medium">${message}</p>
          </div>
          <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
          </button>
        </div>
      `;
      
      document.body.appendChild(notification);
      
      // Auto remove setelah 5 detik
      setTimeout(() => {
        if (notification.parentElement) {
          notification.classList.add('fade-out');
          setTimeout(() => notification.remove(), 500);
        }
      }, 5000);
    }

    // Handle form import submission dengan AJAX
    document.getElementById('importForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      
      // Loading state
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengimport...';
      submitBtn.disabled = true;
      
      fetch(this.action, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showCustomNotification('✅ ' + data.message, 'success');
          if (data.data && data.data.results) {
            // Tampilkan modal hasil import
            showImportResultModal(data.data);
          }
          setTimeout(() => {
            location.reload(); // Refresh halaman setelah 2 detik
          }, 5000);
        } else {
          showCustomNotification('❌ ' + data.message, 'error');
          if (data.data && data.data.errors) {
            console.error('Import errors:', data.data.errors);
          }
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showCustomNotification('❌ Terjadi kesalahan saat import data.', 'error');
      })
      .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
    });

    // Fungsi untuk menampilkan modal hasil import
    function showImportResultModal(data) {
      const modal = document.getElementById('importResultModal');
      const successSection = document.getElementById('successSection');
      const errorSection = document.getElementById('errorSection');
      const successTableBody = document.getElementById('successTableBody');
      const errorList = document.getElementById('errorList');
      const successCount = document.getElementById('successCount');
      const errorCount = document.getElementById('errorCount');
      const importSummaryText = document.getElementById('importSummaryText');

      // Reset konten
      successTableBody.innerHTML = '';
      errorList.innerHTML = '';

      // Tampilkan data berhasil
      if (data.results && data.results.length > 0) {
        successCount.textContent = `${data.imported} data`;
        data.results.forEach((siswa, index) => {
          const row = document.createElement('tr');
          row.className = 'hover:bg-green-100 transition-colors';
          row.innerHTML = `
            <td class="py-3 px-4 text-sm text-gray-700">${index + 1}</td>
            <td class="py-3 px-4 text-sm font-medium text-gray-800">${siswa.nama}</td>
            <td class="py-3 px-4 text-sm text-gray-700">${siswa.email}</td>
            <td class="py-3 px-4 text-sm text-gray-700">${siswa.kelas}</td>
            <td class="py-3 px-4 text-sm font-bold text-red-600">${siswa.password}</td>
          `;
          successTableBody.appendChild(row);
        });
        successSection.classList.remove('hidden');
      } else {
        successSection.classList.add('hidden');
      }

      // Tampilkan error
      if (data.errors && data.errors.length > 0) {
        errorCount.textContent = `${data.errors.length} error`;
        data.errors.forEach((error, index) => {
          const li = document.createElement('li');
          li.className = 'flex items-start gap-2';
          li.innerHTML = `
            <span class="text-red-500 mt-1">•</span>
            <span>${error}</span>
          `;
          errorList.appendChild(li);
        });
        errorSection.classList.remove('hidden');
      } else {
        errorSection.classList.add('hidden');
      }

      // Set summary text
      importSummaryText.textContent = `Total: ${data.imported} berhasil, ${data.skipped} dilewati`;

      // Tampilkan modal
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    // Fungsi untuk menutup modal hasil import
    function closeImportResultModal() {
      const modal = document.getElementById('importResultModal');
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }

    // Fungsi untuk download daftar password
    function downloadPasswordList() {
      const successTableBody = document.getElementById('successTableBody');
      const rows = successTableBody.querySelectorAll('tr');
      
      if (rows.length === 0) {
        showCustomNotification('Tidak ada data untuk didownload', 'warning');
        return;
      }

      let csvContent = 'No,Nama Lengkap,Email,Kelas,Password\n';
      
      rows.forEach((row, index) => {
        const cells = row.querySelectorAll('td');
        const rowData = [
          index + 1,
          cells[1].textContent,
          cells[2].textContent,
          cells[3].textContent,
          cells[4].textContent
        ];
        csvContent += rowData.join(',') + '\n';
      });

      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      const url = URL.createObjectURL(blob);
      
      link.setAttribute('href', url);
      link.setAttribute('download', `daftar_password_siswa_${new Date().toISOString().split('T')[0]}.csv`);
      link.style.visibility = 'hidden';
      
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      
      showCustomNotification('Daftar password berhasil didownload', 'success');
    }

    // Close modal ketika klik di luar konten
    document.getElementById('importResultModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeImportResultModal();
      }
    });

    // Close modal dengan ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeImportResultModal();
      }
    });
  </script>

</body>
</html>