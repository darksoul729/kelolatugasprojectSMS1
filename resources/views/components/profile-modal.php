<?php
session_start();
$user = $_SESSION['user'] ?? [];
?>

<!-- Modal Profil -->
  <div id="profile-modal-content" class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-5">
    <h3 class="text-lg font-semibold text-gray-800">
      <?php if (($user['peran'] ?? '') === 'siswa'): ?>
        Profil Siswa
      <?php elseif (($user['peran'] ?? '') === 'guru'): ?>
        Profil Guru
      <?php endif; ?>
    </h3>
      <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-full p-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Info Profil -->
    <div class="flex flex-col items-center mb-5">
      <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
      </div>
      <p class="font-semibold text-gray-800"><?= htmlspecialchars($user['nama_lengkap'] ?? 'Nama Siswa') ?></p>
      <p class="text-sm text-gray-500"><?= htmlspecialchars($user['kelas'] ?? 'Kelas tidak tersedia') ?></p>
    </div>

  <!-- Detail Info -->
<div class="space-y-3 text-sm border-t border-gray-100 pt-4">
  <?php if (($user['peran'] ?? '') === 'siswa'): ?>
    <div class="flex justify-between">
      <span class="text-gray-600">NIS</span>
      <span class="font-medium text-gray-800">
        <?= htmlspecialchars($user['nip_nis'] ?? '-') ?>
      </span>
    </div>
  <?php elseif (($user['peran'] ?? '') === 'guru'): ?>
    <div class="flex justify-between">
      <span class="text-gray-600">NIP</span>
      <span class="font-medium text-gray-800">
        <?= htmlspecialchars($user['nip_nis'] ?? '-') ?>
      </span>
    </div>
  <?php endif; ?>

  <div class="flex justify-between">
    <span class="text-gray-600">Email</span>
    <span class="font-medium text-gray-800">
      <?= htmlspecialchars($user['email'] ?? '-') ?>
    </span>
  </div>

  <div class="flex justify-between">
    <span class="text-gray-600">Peran</span>
    <span class="font-medium text-gray-800">
      <?= htmlspecialchars(ucfirst($user['peran'] ?? '-')) ?>
    </span>
  </div>

  <?php if (($user['peran'] ?? '') === 'siswa'): ?>
    <div class="flex justify-between">
      <span class="text-gray-600">Kelas</span>
      <span class="font-medium text-gray-800">
        <?= htmlspecialchars($user['wali_kelas'] ?? '-') ?>
      </span>
    </div>
  <?php elseif (($user['peran'] ?? '') === 'guru'): ?>
    <div class="flex justify-between">
      <span class="text-gray-600">Wali Kelas</span>
      <span class="font-medium text-gray-800">
        <?= htmlspecialchars($user['wali_kelas'] ?? '-') ?>
      </span>
    </div>
  <?php endif; ?>
</div>


    <!-- Tombol Aksi -->
    <div class="mt-6 flex justify-end gap-3">
      <button onclick="closeProfileModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg font-medium transition">
        Tutup
      </button>
    <a href="?route=auth/logout"
   class="px-4 py-2 bg-red-500 text-white font-medium rounded hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
  Logout
</a>

    </div>
  </div>
</div>


<script>
  // Buka & tutup modal profil
  function openProfileModal() {
    document.getElementById('profile-modal').classList.remove('hidden');
  }
  function closeProfileModal() {
    document.getElementById('profile-modal').classList.add('hidden');
  }

  // Buka & tutup modal logout
  function openLogoutModal() {
    closeProfileModal(); // Tutup profil dulu
    document.getElementById('logout-modal').classList.remove('hidden');
  }
  function closeLogoutModal() {
    document.getElementById('logout-modal').classList.add('hidden');
  }

  // Tutup modal saat klik di luar konten
  document.getElementById('profile-modal').addEventListener('click', (e) => {
    if (e.target.id === 'profile-modal') closeProfileModal();
  });
  document.getElementById('logout-modal').addEventListener('click', (e) => {
    if (e.target.id === 'logout-modal') closeLogoutModal();
  });
</script>