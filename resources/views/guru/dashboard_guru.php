<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Guru</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .fade-in { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
  </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

<!-- ✅ Notifikasi -->
<?php if (!empty($_SESSION['message'])): ?>
  <div id="notifMessage" class="fixed top-4 left-1/2 transform -translate-x-1/2 max-w-md w-full z-50">
    <div class="flex items-center gap-3 p-4 rounded-lg border shadow-lg
      <?= $_SESSION['message']['type'] === 'success' 
          ? 'bg-green-50 border-green-200 text-green-700' 
          : 'bg-red-50 border-red-200 text-red-700' ?>">
      <?php if ($_SESSION['message']['type'] === 'success'): ?>
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
        </svg>
      <?php else: ?>
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M6.938 4h10.124A2 2 0 0120 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z" />
        </svg>
      <?php endif; ?>
      <span class="font-medium text-sm"><?= htmlspecialchars($_SESSION['message']['text']) ?></span>
    </div>
  </div>
  <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="container mx-auto px-4 py-6 md:py-8">

  <!-- 🧭 Header -->
  <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Dashboard Guru</h1>
      <p class="text-gray-600 text-sm md:text-base mt-1">
        Selamat datang kembali, <span class="font-medium text-blue-600"><?= htmlspecialchars($user['nama_lengkap'] ?? 'Guru') ?></span>
      </p>
    </div>
    <button onclick="openProfileModal()" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
    </button>
  </header>

  <!-- 📊 Statistik Cards -->
  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    
    <!-- Akun -->
    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-blue-500 flex items-center gap-4 hover:shadow-lg transition">
      <div class="bg-blue-100 p-3 rounded-lg">
        <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7.97 7.97 0 0112 15a7.97 7.97 0 016.879 2.804M12 15a9 9 0 100-18 9 9 0 000 18z" />
        </svg>
      </div>
      <div>
        <p class="text-gray-500 text-xs">Akun Anda</p>
        <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($user['nama_lengkap'] ?? '-') ?></p>
      </div>
    </div>

    <!-- Kelas Wali -->
    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500 flex items-center gap-4 hover:shadow-lg transition">
      <div class="bg-green-100 p-3 rounded-lg">
        <svg class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9 6 9-6M3 17l9 6 9-6" />
        </svg>
      </div>
      <div>
        <p class="text-gray-500 text-xs">Wali Kelas</p>
        <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($wali_kelas ?? '-') ?></p>
      </div>
    </div>

  

    <!-- Bulan Aktif -->
    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-purple-500 flex items-center gap-4 hover:shadow-lg transition">
      <div class="bg-purple-100 p-3 rounded-lg">
        <svg class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
        </svg>
      </div>
      <div>
        <p class="text-gray-500 text-xs">Bulan Aktif</p>
        <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($bulan ?? '-') ?></p>
      </div>
    </div>
  </section>

  <!-- 🧾 Tabs -->
  <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
    <div class="flex flex-col sm:flex-row">
      <button id="tab-rekap" class="flex-1 text-center px-4 py-3 font-medium bg-blue-500 text-white">
        Ringkasan Kebiasaan
      </button>
      <button id="tab-siswa" class="flex-1 text-center px-4 py-3 font-medium bg-gray-100 text-gray-700">
        Daftar Siswa
      </button>
    </div>
  </div>

  <!-- 🗂️ Tab Content -->
  <div id="sheet-rekap" class="fade-in">
    <?php include_once __DIR__ . '/../components/tabel_biasa.php'; ?>
  </div>
  <div id="sheet-siswa" class="fade-in hidden">
    <?php include_once __DIR__ . '/../components/tabel_siswa.php'; ?>
  </div>

</div>

<!-- 🧍 Modal Profil (tetap ada) -->
<div id="profile-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"></div>

<script>
  async function openProfileModal() {
    const modal = document.getElementById('profile-modal');
    if (modal.children.length === 0) {
      try {
        const res = await fetch('/resources/views/components/profile-modal.php');
        const html = await res.text();
        modal.innerHTML = html;
      } catch {
        modal.innerHTML = '<div class="bg-white p-6 rounded-lg">Gagal memuat profil.</div>';
      }
    }
    modal.classList.remove('hidden');
  }
  function closeProfileModal() {
    document.getElementById('profile-modal').classList.add('hidden');
  }

  document.addEventListener('DOMContentLoaded', () => {
    const notif = document.getElementById('notifMessage');
    if (notif) setTimeout(() => notif.remove(), 5000);

    const tabs = {
      rekap: { btn: document.getElementById('tab-rekap'), sheet: document.getElementById('sheet-rekap') },
      siswa: { btn: document.getElementById('tab-siswa'), sheet: document.getElementById('sheet-siswa') },
    };

    Object.keys(tabs).forEach(key => {
      tabs[key].btn.addEventListener('click', () => {
        Object.keys(tabs).forEach(k => {
          tabs[k].btn.classList.toggle('bg-blue-500', k === key);
          tabs[k].btn.classList.toggle('text-white', k === key);
          tabs[k].btn.classList.toggle('bg-gray-100', k !== key);
          tabs[k].btn.classList.toggle('text-gray-700', k !== key);
          tabs[k].sheet.classList.toggle('hidden', k !== key);
        });
      });
    });

    document.getElementById('profile-modal').addEventListener('click', (e) => {
      if (e.target.id === 'profile-modal') closeProfileModal();
    });
  });
</script>

</body>
</html>
