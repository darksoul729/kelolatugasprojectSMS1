<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Beranda Siswa</title>

  <!-- ✅ Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    #notifMessage {
      transition: opacity 0.5s ease-in-out;
    }
    
    .welcome-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .feature-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .tooltip {
      position: relative;
      display: inline-block;
    }
    
    .tooltip .tooltip-text {
      visibility: hidden;
      width: 200px;
      background-color: #1f2937;
      color: #fff;
      text-align: center;
      border-radius: 6px;
      padding: 8px;
      position: absolute;
      z-index: 1;
      bottom: 125%;
      left: 50%;
      margin-left: -100px;
      opacity: 0;
      transition: opacity 0.3s;
      font-size: 0.75rem;
    }
    
    .tooltip:hover .tooltip-text {
      visibility: visible;
      opacity: 1;
    }
    
    .tooltip .tooltip-text::after {
      content: "";
      position: absolute;
      top: 100%;
      left: 50%;
      margin-left: -5px;
      border-width: 5px;
      border-style: solid;
      border-color: #1f2937 transparent transparent transparent;
    }
  </style>

  <script>
    function openModal() {
      document.getElementById('logout-modal').classList.remove('hidden');
    }
    function closeModal() {
      document.getElementById('logout-modal').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
      const notif = document.getElementById('notifMessage');
      if (notif) {
        setTimeout(() => {
          notif.style.opacity = '0';
          setTimeout(() => notif.remove(), 500);
        }, 5000);
      }
      
      // Tampilkan panduan untuk pengguna baru
      const isNewUser = localStorage.getItem('isNewUser') === 'true' || 
                         new URLSearchParams(window.location.search).has('new_user');
      
      if (isNewUser) {
        showWelcomeGuide();
        localStorage.setItem('isNewUser', 'false');
      }
    });
    
    function showWelcomeGuide() {
      const guide = document.getElementById('welcome-guide');
      if (guide) {
        guide.classList.remove('hidden');
      }
    }
    
    function closeWelcomeGuide() {
      const guide = document.getElementById('welcome-guide');
      if (guide) {
        guide.classList.add('hidden');
      }
    }
  </script>
</head>

<body class="min-h-screen font-sans bg-gray-50 relative">

  <!-- ✅ Notifikasi -->
  <?php if (!empty($_SESSION['message'])): ?>
  <div id="notifMessage" class="fixed top-4 left-1/2 transform -translate-x-1/2 max-w-md w-full z-50 opacity-100">
    <div class="flex items-start gap-3 p-4 rounded-lg border shadow-lg
        <?php if ($_SESSION['message']['type'] === 'success'): ?>
            bg-green-50 border-green-200 text-green-700
        <?php else: ?>
            bg-red-50 border-red-200 text-red-700
        <?php endif; ?>">
      <?php if ($_SESSION['message']['type'] === 'success'): ?>
        <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      <?php else: ?>
        <svg class="w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667
            1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464
            0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      <?php endif; ?>
      <span class="font-medium text-sm"><?= htmlspecialchars($_SESSION['message']['text']) ?></span>
    </div>
  </div>
  <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <!-- ✅ Header -->
  <header class="fixed top-0 left-0 right-0 bg-white shadow-sm z-10">
    <div class="container mx-auto px-4 sm:px-6 py-3 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <!-- Ikon Grid -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Z
            M3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18
            a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25Z
            M13.5 6a2.25 2.25 0 0 1 2.25-2.25H18
            A2.25 2.25 0 0 1 20.25 6v2.25
            A2.25 2.25 0 0 1 18 10.5h-2.25
            a2.25 2.25 0 0 1-2.25-2.25V6Z" />
        </svg>
        <h1 class="text-lg sm:text-xl font-bold text-gray-800">Beranda Siswa</h1>
      </div>

      <div class="flex items-center space-x-3">
        <!-- Tombol Panduan -->
        <button onclick="showWelcomeGuide()" class="tooltip">
          <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
            </svg>
          </div>
          <span class="tooltip-text">Klik untuk melihat panduan penggunaan</span>
        </button>
        
        <!-- Tombol Profil -->
        <button onclick="openProfileModal()"
          class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition tooltip">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75
                a7.488 7.488 0 0 0-5.982 2.975m11.963 0
                a9 9 0 1 0-11.963 0m11.963 0
                A8.966 8.966 0 0 1 12 21
                a8.966 8.966 0 0 1-5.982-2.275M15 9.75
                a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
          </svg>
          <span class="tooltip-text">Kelola profil dan pengaturan akun</span>
        </button>
      </div>
    </div>
  </header>

  <!-- ✅ Main -->
  <main class="pt-20 pb-24 px-4 sm:px-6 min-h-screen">
    <!-- ✅ Kartu Selamat Datang -->
    <div class="welcome-card max-w-4xl mx-auto rounded-2xl p-6 text-white mb-8 shadow-lg">
      <div class="flex flex-col md:flex-row items-center">
        <div class="flex-1 mb-6 md:mb-0 md:pr-6">
          <h2 class="text-2xl font-bold mb-2">Selamat Datang di Aplikasi 7 Kebiasaan Baik!</h2>
          <p class="opacity-90 mb-4">
            Mari mulai perjalanan membangun kebiasaan positif yang akan meningkatkan produktivitas dan kualitas hidup Anda.
          </p>
          <button onclick="showWelcomeGuide()" class="bg-white text-purple-700 px-4 py-2 rounded-lg font-medium hover:bg-opacity-90 transition">
            Lihat Panduan
          </button>
        </div>
        <div class="flex-1">
          <div class="bg-white bg-opacity-20 rounded-xl p-4">
            <h3 class="font-bold mb-2">Mulai dengan 3 langkah mudah:</h3>
            <ol class="list-decimal pl-5 space-y-2 text-sm">
              <li>Klik "Tambah Kebiasaan" untuk mencatat kebiasaan harian</li>
              <li>Isi form dengan jujur dan konsisten setiap hari</li>
              <li>Lihat perkembangan Anda di "Rekap Bulanan"</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Form 7 Kebiasaan Baik</h2>
        <p class="text-gray-600 text-sm sm:text-base max-w-2xl mx-auto">
          Bangun kebiasaan positif setiap hari dan rasakan peningkatan produktivitasmu yang luar biasa!
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- ✅ Kartu Utama -->
        <div class="feature-card bg-white rounded-xl p-6 shadow-sm border border-gray-200">
          <div class="flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244
                     l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364
                     l1.757-1.757m13.35-.622 1.757-1.757
                     a4.5 4.5 0 0 0-6.364-6.364
                     l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">7 Kebiasaan Baik</h3>
            <p class="text-gray-600 text-sm mb-5 px-2">
              Catat dan lacak kebiasaan harianmu untuk membangun disiplin diri yang kuat.
            </p>
            <a href="?route=murid/kebiasaan/tambah"
               class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition">
              Tambah Kebiasaan
            </a>
          </div>
        </div>

        <!-- ✅ Kartu Rekap -->
        <div class="feature-card bg-white rounded-xl p-6 shadow-sm border border-gray-200">
          <div class="flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-full bg-green-50 flex items-center justify-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Rekap Bulanan</h3>
            <p class="text-gray-600 text-sm mb-5 px-2">
              Lihat perkembangan dan statistik kebiasaan baik Anda dalam bentuk grafik yang mudah dipahami.
            </p>
            <a href="?route=murid/rekap" class="w-full bg-green-600 text-white py-2.5 rounded-lg font-medium hover:bg-green-700 transition">
              Lihat Rekap
            </a>
          </div>
        </div>
      </div>

      <!-- ✅ Fitur Tambahan -->
      <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg p-4 border border-gray-200 text-center">
          <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
          </div>
          <h4 class="font-medium text-gray-800">Statistik</h4>
          <p class="text-xs text-gray-600 mt-1">Pantau perkembangan kebiasaan baik Anda</p>
        </div>
        
        <div class="bg-white rounded-lg p-4 border border-gray-200 text-center">
          <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
          </div>
          <h4 class="font-medium text-gray-800">Konsistensi</h4>
          <p class="text-xs text-gray-600 mt-1">Tingkatkan konsistensi kebiasaan baik</p>
        </div>
        
        <div class="bg-white rounded-lg p-4 border border-gray-200 text-center">
          <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
          </div>
          <h4 class="font-medium text-gray-800">Motivasi</h4>
          <p class="text-xs text-gray-600 mt-1">Dapatkan motivasi untuk terus berkembang</p>
        </div>
      </div>
    </div>
  </main>

  <!-- ✅ Modal Panduan untuk Pengguna Baru -->
  <div id="welcome-guide" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 relative">
      <button onclick="closeWelcomeGuide()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      
      <div class="text-center mb-6">
        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Selamat Datang!</h3>
        <p class="text-gray-600">Mari kita kenali fitur-fitur aplikasi 7 Kebiasaan Baik</p>
      </div>
      
      <div class="space-y-4 mb-6">
        <div class="flex items-start">
          <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center flex-shrink-0 mr-3 mt-1">
            <span class="text-sm font-medium">1</span>
          </div>
          <div>
            <h4 class="font-medium text-gray-800">Tambah Kebiasaan</h4>
            <p class="text-sm text-gray-600">Klik tombol "Tambah Kebiasaan" untuk mencatat kebiasaan harian Anda. Lakukan setiap hari untuk membangun disiplin.</p>
          </div>
        </div>
        
        <div class="flex items-start">
          <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center flex-shrink-0 mr-3 mt-1">
            <span class="text-sm font-medium">2</span>
          </div>
          <div>
            <h4 class="font-medium text-gray-800">Lihat Rekap</h4>
            <p class="text-sm text-gray-600">Periksa "Rekap Bulanan" untuk melihat perkembangan dan statistik kebiasaan baik Anda.</p>
          </div>
        </div>
        
        <div class="flex items-start">
          <div class="w-8 h-8 rounded-full bg-purple-600 text-white flex items-center justify-center flex-shrink-0 mr-3 mt-1">
            <span class="text-sm font-medium">3</span>
          </div>
          <div>
            <h4 class="font-medium text-gray-800">Kelola Profil</h4>
            <p class="text-sm text-gray-600">Klik ikon profil di kanan atas untuk mengatur akun dan melihat informasi pribadi Anda.</p>
          </div>
        </div>
      </div>
      
      <button onclick="closeWelcomeGuide()" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
        Mulai Sekarang
      </button>
    </div>
  </div>

  <!-- ✅ Modal Profil -->
  <div id="profile-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4"></div>

  <script>
    async function openProfileModal() {
      const modal = document.getElementById('profile-modal');
      if (modal.innerHTML.trim() === '') {
        try {
          const response = await fetch('/resources/views/components/profile-modal.php');
          const html = await response.text();
          modal.innerHTML = html;
        } catch (error) {
          console.error('Gagal memuat modal profil:', error);
          modal.innerHTML = '<div class="bg-white p-6 rounded-lg shadow">Gagal memuat profil.</div>';
        }
      }
      modal.classList.remove('hidden');
    }

    function closeProfileModal() {
      document.getElementById('profile-modal').classList.add('hidden');
    }

    document.getElementById('profile-modal').addEventListener('click', (e) => {
      if (e.target.id === 'profile-modal') closeProfileModal();
    });
  </script>
</body>
</html>