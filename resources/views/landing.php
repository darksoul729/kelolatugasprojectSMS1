<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <favicon rel="jpg" href="../../public/img/smpn21.jpg">
  <title>Jurnal Tujuh KAIH — Kebiasaan Anak Indonesia Hebat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .navbar-hidden {
      transform: translateY(-100%);
    }
    .sidebar-overlay {
      transform: translateX(-100%);
      opacity: 0;
      transition: transform 0.4s cubic-bezier(0.22, 0.61, 0.36, 1), opacity 0.4s ease-in-out;
    }
    .sidebar-overlay.active {
      transform: translateX(0);
      opacity: 1;
    }
    .backdrop {
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.4s ease-in-out, visibility 0.4s ease-in-out;
    }
    .backdrop.active {
      opacity: 1;
      visibility: visible;
    }
    .main-content.sidebar-active {
      filter: blur(4px);
      transition: filter 0.4s ease-in-out;
    }
    .sidebar-btn {
      transition: all 0.2s ease;
    }
    .sidebar-btn:active {
      transform: scale(0.98);
    }
    .sidebar-btn-login {
      background-color: transparent;
      border: 2px solid #3b82f6;
      color: #3b82f6;
    }
    .sidebar-btn-login:hover {
      background-color: #eff6ff;
      color: #1d4ed8;
      border-color: #1d4ed8;
    }
    .sidebar-btn-signup {
      background-color: #3b82f6;
      color: white;
    }
    .sidebar-btn-signup:hover {
      background-color: #1d4ed8;
      color: white;
    }
  </style>
</head>
<body class="font-sans bg-gray-50 text-gray-800 overflow-x-hidden">
   <!-- Notifikasi -->
<?php if (isset($_SESSION['message'])): ?>
    <?php
        $type = $_SESSION['message']['type'];
        $classes = match ($type) {
            'danger'  => 'bg-red-50 border-red-200 text-red-700',
            'success' => 'bg-green-50 border-green-200 text-green-700',
            'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
            default   => 'bg-gray-50 border-gray-200 text-gray-700',
        };
    ?>
    <div class="mb-6 p-3 rounded-lg border <?= $classes ?>">
        <span class="font-medium"><?= htmlspecialchars($_SESSION['message']['text']) ?></span>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>
  <!-- Navbar -->
  <header id="navbar" class="fixed top-0 left-0 w-full z-30 bg-white shadow-md transition-transform duration-300">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <img src="../../public/img/smpn21.jpg" alt="Logo" class="w-8 h-8 object-cover rounded">
        <span class="text-xl font-bold text-gray-800">Jurnal Tujuh KAIH</span>
      </div>
      <nav class="hidden md:flex space-x-8">
        <a href="#kebiasaan" class="font-medium text-gray-600 hover:text-blue-600 transition">7 Kebiasaan</a>
        <a href="#manfaat" class="font-medium text-gray-600 hover:text-blue-600 transition">Manfaat</a>
        <a href="#kontak" class="font-medium text-gray-600 hover:text-blue-600 transition">Kontak</a>
      </nav>
      <div class="hidden md:flex items-center space-x-4">
        <a href="?route=auth/login" class="text-gray-600 hover:text-blue-600 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">Masuk</a>
        <a href="?route=auth/register" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Daftar</a>
      </div>
      <button id="menuToggle" class="md:hidden text-gray-600 text-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 rounded p-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>
  </header>

  <!-- Backdrop -->
  <div id="backdrop" class="backdrop fixed inset-0 bg-black bg-opacity-60 z-40 hidden"></div>

  <!-- Sidebar Mobile -->
  <div id="mobileSidebar" class="sidebar-overlay fixed top-0 left-0 h-full w-5/6 max-w-sm z-50 bg-gradient-to-b from-blue-50 via-white to-gray-100 shadow-2xl p-6 transform transition-all duration-500 ease-in-out hidden">
    <div class="flex justify-between items-center mb-12">
      <div class="flex items-center space-x-2">
        <img src="../../public/img/smpn21.jpg" alt="Logo" class="w-8 h-8 object-cover rounded">
        <span class="text-xl font-bold text-gray-800">Jurnal Tujuh KAIH</span>
      </div>
      <button id="closeSidebar" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1 w-10 h-10 flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <nav class="flex flex-col space-y-4">
      <a href="#kebiasaan" class="font-medium text-gray-700 py-3 px-4 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition-colors duration-300 flex items-center">
        <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        7 Kebiasaan
      </a>
      <a href="#manfaat" class="font-medium text-gray-700 py-3 px-4 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition-colors duration-300 flex items-center">
        <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        Manfaat
      </a>
      <a href="#kontak" class="font-medium text-gray-700 py-3 px-4 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition-colors duration-300 flex items-center">
        <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        Kontak
      </a>
      <div class="pt-8 pb-4 border-t border-gray-200 border-opacity-50 space-y-4">
        <a href="?route=auth/login" class="block sidebar-btn sidebar-btn-login py-3 text-center rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500">
          Masuk ke Akun
        </a>
        <a href="?route=auth/register" class="block sidebar-btn sidebar-btn-signup py-3 text-center rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500">
          Buat Akun Gratis
        </a>
      </div>
    </nav>
  </div>

  <!-- Konten Utama -->
  <div id="mainContent" class="main-content transition-filter duration-400 ease-in-out pt-16">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-28">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">Bangun Kebiasaan Hebat, Mulai Hari Ini!</h1>
        <p class="max-w-3xl mx-auto text-lg text-blue-100 mb-10">
          Catat & pantau 7 kebiasaan harianmu: Bangun Pagi, Beribadah, Olahraga, Makan Sehat, Belajar, Bermasyarakat, dan Tidur Cepat.
        </p>
        <a href="?route=auth/register" class="inline-block bg-white text-blue-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg text-lg transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2">
          Mulai Jurnal Harianmu
        </a>
      </div>
    </section>

    <!-- 7 Kebiasaan -->
    <section id="kebiasaan" class="py-20 bg-gray-50">
      <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-4 text-gray-800">7 Kebiasaan Anak Indonesia Hebat</h2>
        <p class="max-w-2xl mx-auto text-center text-gray-600 mb-12">
          Setiap hari adalah kesempatan untuk menjadi versi terbaik dirimu.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 max-w-6xl mx-auto">
          <!-- Kebiasaan Card -->
          <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-blue-500">
            <h3 class="font-bold text-gray-800">1. Bangun Pagi</h3>
            <p class="text-sm text-gray-600 mt-1">Mulai hari dengan semangat sebelum matahari terbit.</p>
          </div>
          <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-purple-500">
            <h3 class="font-bold text-gray-800">2. Beribadah</h3>
            <p class="text-sm text-gray-600 mt-1">Hubungkan diri dengan Tuhan setiap hari.</p>
          </div>
          <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-green-500">
            <h3 class="font-bold text-gray-800">3. Berolahraga</h3>
            <p class="text-sm text-gray-600 mt-1">Jaga tubuh sehat dan pikiran segar.</p>
          </div>
          <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-yellow-500">
            <h3 class="font-bold text-gray-800">4. Makan Sehat</h3>
            <p class="text-sm text-gray-600 mt-1">Konsumsi makanan bergizi seimbang.</p>
          </div>
          <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-indigo-500">
            <h3 class="font-bold text-gray-800">5. Gemar Belajar</h3>
            <p class="text-sm text-gray-600 mt-1">Perluas wawasan dan kuasai ilmu baru.</p>
          </div>
          <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-pink-500">
            <h3 class="font-bold text-gray-800">6. Bermasyarakat</h3>
            <p class="text-sm text-gray-600 mt-1">Bersosialisasi dan berkontribusi positif.</p>
          </div>
          <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-teal-500">
            <h3 class="font-bold text-gray-800">7. Tidur Cepat</h3>
            <p class="text-sm text-gray-600 mt-1">Istirahat cukup untuk hari esok yang lebih baik.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Manfaat -->
    <section id="manfaat" class="py-20 bg-white">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-gray-800">Mengapa Gunakan Jurnal Tujuh KAIH?</h2>
        <p class="max-w-2xl mx-auto text-gray-600 mb-16">
          Dibuat khusus untuk pelajar Indonesia yang ingin membentuk karakter unggul sejak dini.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
          <div class="p-6">
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Refleksi Harian</h3>
            <p class="text-gray-600">Evaluasi diri setiap malam dan rayakan progres kecilmu.</p>
          </div>
          <div class="p-6">
            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Laporan Bulanan</h3>
            <p class="text-gray-600">Lihat perkembangan kebiasaanmu dalam bentuk ringkasan visual.</p>
          </div>
          <div class="p-6">
            <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Tanpa Ribet</h3>
            <p class="text-gray-600">Antarmuka sederhana, cepat, dan mudah digunakan oleh siapa saja.</p>
          </div>
        </div>
      </div>
    </section>

 <!-- Kontak -->
<section id="kontak" class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6 md:p-8">
      <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Butuh Bantuan?</h2>
        <p class="text-gray-600 mt-2">Kami siap mendukung perjalananmu menjadi anak Indonesia hebat.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Form Kontak -->
        <div>
          <form action="?route=landing/kirim_pesan" method="POST" class="space-y-4">
            <div>
              <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
              <input type="text" id="nama" name="nama" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input type="email" id="email" name="email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            <div>
              <label for="pesan" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
              <textarea id="pesan" name="pesan" rows="4" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"></textarea>
            </div>
            <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              Kirim Pesan
            </button>
          </form>
        </div>

        <!-- Info Kontak -->
        <div class="flex flex-col justify-center">
          <div class="bg-blue-50 rounded-lg p-5 border border-blue-100">
            <h3 class="font-semibold text-gray-800 mb-3">Hubungi Kami Langsung</h3>
            <p class="text-gray-600 text-sm mb-2">
              Email: 
              <a href="mailto:admin@jurnaltujuhkaih.id" class="text-blue-600 hover:underline font-medium">
                admin@jurnaltujuhkaih.id
              </a>
            </p>
            <p class="text-gray-500 text-xs mt-6">
              © 2025 XI PPLG Smkti Airlangga Samarinda
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
  </div>

  <!-- Script tetap sama -->
  <script>
    // ... (script tetap seperti sebelumnya, tidak diubah)
    const menuToggle = document.getElementById("menuToggle");
    const closeSidebar = document.getElementById("closeSidebar");
    const mobileSidebar = document.getElementById("mobileSidebar");
    const backdrop = document.getElementById("backdrop");
    const mainContent = document.getElementById("mainContent");

    function openMobileMenu() {
      mainContent.classList.add("sidebar-active");
      mobileSidebar.classList.remove("hidden");
      setTimeout(() => {
        mobileSidebar.classList.add("active");
        backdrop.classList.add("active");
        document.body.style.overflow = 'hidden';
      }, 10);
    }

    function closeMobileMenu() {
      mobileSidebar.classList.remove("active");
      backdrop.classList.remove("active");
      setTimeout(() => {
        mobileSidebar.classList.add("hidden");
        mainContent.classList.remove("sidebar-active");
        document.body.style.overflow = '';
      }, 400);
    }

    menuToggle.addEventListener("click", openMobileMenu);
    closeSidebar.addEventListener("click", closeMobileMenu);
    backdrop.addEventListener("click", closeMobileMenu);

    let lastScroll = 0;
    const navbar = document.getElementById("navbar");
    window.addEventListener("scroll", () => {
      let current = window.scrollY;
      if (current > lastScroll && current > 60) {
        navbar.classList.add("navbar-hidden");
      } else {
        navbar.classList.remove("navbar-hidden");
      }
      lastScroll = current;
    });

    let touchStartX = 0;
    let touchEndX = 0;
    document.addEventListener('touchstart', e => touchStartX = e.changedTouches[0].screenX);
    document.addEventListener('touchend', e => {
      touchEndX = e.changedTouches[0].screenX;
      if (touchStartX - touchEndX > 50 && mobileSidebar.classList.contains("active")) {
        closeMobileMenu();
      }
    });
  </script>
</body>
</html>