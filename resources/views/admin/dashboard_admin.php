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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin</title>
  <!-- ✅ Perbaiki: hapus spasi ekstra -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

  <!-- Header -->
  <header class="bg-white shadow-md py-3 px-4 md:px-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
    <h1 class="text-lg md:text-xl font-bold text-gray-800">Dashboard Admin</h1>
    <div class="flex items-center space-x-3">
      <span class="text-gray-600 text-sm">
        Halo, <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin'); ?>
      </span>
      <button onclick="openLogoutModal()" class="text-red-500 hover:text-red-600 font-medium text-sm transition">
        Logout
      </button>
    </div>
  </header>

  <!-- Main -->
  <main class="container mx-auto px-4 py-6 md:py-8">

    <!-- Statistik -->
    <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 text-gray-800">Statistik Pengguna</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-6 md:mb-8">
      <div class="bg-white rounded-xl shadow p-4 md:p-5 text-center border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-xs md:text-sm mb-1">Total Pengguna</h3>
        <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $totalUsers ?></p>
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
        <div class="bg-white rounded-xl shadow p-4 md:p-5 text-center border-l-4 border-<?= $warna ?>-500">
          <h3 class="text-gray-500 text-xs md:text-sm mb-1"><?= ucfirst($r['peran']) ?></h3>
          <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $r['jumlah'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pencarian -->
    <form method="GET" class="mb-6 flex flex-col sm:flex-row gap-2">
      <input type="hidden" name="route" value="admin/users">
      <input type="text" name="search" placeholder="Cari user..."
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
             class="flex-grow px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
      <button type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
        Cari
      </button>
    </form>

    <!-- Tabel -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full border-collapse text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="py-2 px-3 md:py-3 md:px-4 text-left">#</th>
            <th class="py-2 px-3 md:py-3 md:px-4 text-left">Nama Lengkap</th>
            <th class="py-2 px-3 md:py-3 md:px-4 text-left">Email</th>
            <th class="py-2 px-3 md:py-3 md:px-4 text-left">Peran</th>
            <th class="py-2 px-3 md:py-3 md:px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($usersToShow as $index => $user): ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="px-3 py-2 md:px-4 md:py-2 text-center"><?= $offset + $index + 1 ?></td>
            <td class="px-3 py-2 md:px-4 md:py-2"><?= htmlspecialchars($user['nama_lengkap']) ?></td>
            <td class="px-3 py-2 md:px-4 md:py-2"><?= htmlspecialchars($user['email']) ?></td>
            <td class="px-3 py-2 md:px-4 md:py-2">
              <?php if ($user['peran'] === 'admin'): ?>
                <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded-full">Admin</span>
              <?php elseif ($user['peran'] === 'guru'): ?>
                <span class="bg-green-100 text-green-600 text-xs font-semibold px-2 py-1 rounded-full">Guru</span>
              <?php elseif ($user['peran'] === 'belum_verifikasi'): ?>
                <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-1 rounded-full">Belum Verif</span>
              <?php else: ?>
                <span class="bg-yellow-100 text-yellow-600 text-xs font-semibold px-2 py-1 rounded-full">Siswa</span>
              <?php endif; ?>
            </td>
            <td class="px-3 py-2 md:px-4 md:py-2 text-center">
              <?php if ($user['peran'] === 'belum_verifikasi'): ?>
                <button onclick="openVerifyModal(<?= $user['id_user'] ?>)"
                        class="bg-green-500 hover:bg-green-600 text-white text-xs md:text-sm px-2 py-1 rounded">
                  Verifikasi
                </button>
              <?php else: ?>
                <div class="flex justify-center gap-1 md:gap-2">
                  <button onclick='fetchUserAndOpenModal(<?= $user["id_user"] ?>)'
                          class="bg-blue-500 hover:bg-blue-600 text-white text-xs md:text-sm px-2 py-1 rounded">
                    Edit
                  </button>
                  <button onclick="openDeleteModal(<?= $user['id_user'] ?>)"
                          class="bg-red-500 hover:bg-red-600 text-white text-xs md:text-sm px-2 py-1 rounded">
                    Hapus
                  </button>
                </div>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <div class="flex flex-wrap justify-center items-center gap-1 md:gap-2 mt-6">
        <?php if ($currentPage > 1): ?>
          <a href="?route=admin/users&page=<?= $currentPage - 1 ?>"
             class="px-2 py-1 md:px-3 md:py-1 rounded border text-gray-700 hover:bg-gray-200 transition text-sm">‹</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?route=admin/users&page=<?= $i ?>"
             class="px-2 py-1 md:px-3 md:py-1 rounded border transition text-sm <?= $i == $currentPage
               ? 'bg-blue-600 text-white border-blue-600'
               : 'text-gray-700 hover:bg-gray-200' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
          <a href="?route=admin/users&page=<?= $currentPage + 1 ?>"
             class="px-2 py-1 md:px-3 md:py-1 rounded border text-gray-700 hover:bg-gray-200 transition text-sm">›</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </main>

  <?php include __DIR__ . '/../components/edit-modal-user.php'; ?>
  <?php include __DIR__ . '/../components/delete-modal-user.php'; ?>
  <?php include __DIR__ . '/../components/verify-modal-user.php'; ?>

  <!-- Logout Modal -->
  <div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-80 p-5">
      <h2 class="text-lg font-semibold text-gray-800 mb-3">Konfirmasi Logout</h2>
      <p class="text-gray-600 mb-5">Apakah Anda yakin ingin logout?</p>
      <div class="flex justify-end space-x-2">
        <button onclick="closeLogoutModal()" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400 transition text-sm">Batal</button>
        <a href="?route=auth/logout" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm">Ya, Logout</a>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // ... (script tetap sama, tidak perlu diubah)
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
      document.getElementById('edit-status-aktif').value = user.status_aktif;

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

    function openVerifyModal(id) {
      const m = document.getElementById('verifyModal');
      const roleSelect = document.getElementById('verify-role');
      const waliContainer = document.getElementById('waliKelasContainer');
      document.getElementById('verify-user-id').value = id;
      m.classList.remove('hidden');

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
    }
    function closeVerifyModal() { document.getElementById('verifyModal').classList.add('hidden'); }
  </script>
</body>
</html>