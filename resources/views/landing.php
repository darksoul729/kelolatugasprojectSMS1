

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kelola Tugas — Landing</title>
  <!-- ✅ Perbaiki spasi di CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .navbar-hidden {
      transform: translateY(-100%);
    }
    /* Gaya untuk sidebar overlay */
    .sidebar-overlay {
      transform: translateX(-100%);
      opacity: 0;
      transition: transform 0.4s cubic-bezier(0.22, 0.61, 0.36, 1), opacity 0.4s ease-in-out;
    }
    .sidebar-overlay.active {
      transform: translateX(0);
      opacity: 1;
    }
    /* Gaya untuk backdrop */
    .backdrop {
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.4s ease-in-out, visibility 0.4s ease-in-out;
    }
    .backdrop.active {
      opacity: 1;
      visibility: visible;
    }
    /* Gaya untuk konten utama saat sidebar aktif (blur) */
    .main-content.sidebar-active {
      filter: blur(4px);
      transition: filter 0.4s ease-in-out;
    }
    /* Gaya tombol login/daftar dalam sidebar */
    .sidebar-btn {
      transition: all 0.2s ease;
    }
    .sidebar-btn:active {
      transform: scale(0.98);
    }
    /* Gaya tombol yang berbeda untuk sidebar */
    .sidebar-btn-login {
      background-color: transparent;
      border: 2px solid #3b82f6; /* border biru */
      color: #3b82f6; /* teks biru */
    }
    .sidebar-btn-login:hover {
      background-color: #eff6ff; /* latar saat hover */
      color: #1d4ed8; /* teks lebih gelap */
      border-color: #1d4ed8;
    }
    .sidebar-btn-signup {
      background-color: #3b82f6; /* latar biru */
      color: white; /* teks putih */
    }
    .sidebar-btn-signup:hover {
      background-color: #1d4ed8; /* latar lebih gelap */
      color: white;
    }
  </style>
</head>
<body class="font-sans bg-gray-50 text-gray-800 overflow-x-hidden">
  <!-- Navbar (tetap di tempatnya) -->
  <header id="navbar" class="fixed top-0 left-0 w-full z-30 bg-white shadow-md transition-transform duration-300">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <span class="text-xl font-bold text-gray-800">Kelola Tugas</span>
      </div>
      <nav class="hidden md:flex space-x-8">
        <a href="#fitur" class="font-medium text-gray-600 hover:text-blue-600 transition">Fitur</a>
        <a href="#kontak" class="font-medium text-gray-600 hover:text-blue-600 transition">Kontak</a>
      </nav>
      <div class="hidden md:flex items-center space-x-4">
        <a href="?route=auth/login" class="text-gray-600 hover:text-blue-600 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">Login</a>
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

  <!-- Sidebar Overlay -->
  <div id="mobileSidebar" class="sidebar-overlay fixed top-0 left-0 h-full w-5/6 max-w-sm z-50 bg-gradient-to-b from-blue-50 via-white to-gray-100 shadow-2xl p-6 transform transition-all duration-500 ease-in-out hidden">
    <div class="flex justify-between items-center mb-12">
      <div class="flex items-center space-x-2">
        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <span class="text-xl font-bold text-gray-800">Kelola Tugas</span>
      </div>
      <button id="closeSidebar" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1 w-10 h-10 flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <nav class="flex flex-col space-y-4">
      <a href="#fitur" class="font-medium text-gray-700 py-3 px-4 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
        <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        Fitur
      </a>
      <a href="#kontak" class="font-medium text-gray-700 py-3 px-4 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
        <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        Kontak
      </a>
      <!-- Bagian Tombol Login & Sign Up di Sidebar -->
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
  <div id="mainContent" class="main-content transition-filter duration-400 ease-in-out pt-16"> <!-- Tambahkan pt-16 untuk offset navbar -->
    <section class="relative bg-gray-800 text-white py-32">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-6">Fokus Pada Apa Yang Penting</h1>
        <p class="max-w-3xl mx-auto text-lg text-gray-300 mb-10">
          Aplikasi pengelola tugas berbasis PHP Native: Simpel, cepat, dan efektif untuk mengatur semua pekerjaanmu.
        </p>
        <a href="?route=auth/register" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg text-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          Coba Sekarang Gratis
        </a>
      </div>
    </section>

    <section id="fitur" class="py-20 bg-gray-50">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-gray-800">Kenapa Memilih Kelola Tugas?</h2>
        <p class="max-w-2xl mx-auto text-gray-600 mb-16">
          Kami hadir dengan fitur yang kamu butuhkan, tanpa ribet dan langsung bisa dipakai.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
          <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="w-12 h-12 text-red-500 mx-auto mb-4">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-3 text-gray-800">Status Tugas Jelas</h3>
            <p class="text-gray-600">
              Pantau tugasmu dari <b>Belum Dikerjakan</b>, <b>Sedang Dikerjakan</b>, hingga <b>Selesai</b> dengan warna yang mudah dibaca.
            </p>
          </div>
          <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="w-12 h-12 text-blue-500 mx-auto mb-4">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-3 text-gray-800">Atur Tugas dengan Mudah</h3>
            <p class="text-gray-600">
              Buat, ubah, hapus, dan tambahkan <b>batas waktu</b> untuk setiap tugas agar tidak lupa.
            </p>
          </div>
          <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="w-12 h-12 text-green-500 mx-auto mb-4">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-3 text-gray-800">Dibangun dengan PHP Native</h3>
            <p class="text-gray-600">
              Dibuat tanpa framework ribet. Cocok untuk belajar dan dipakai siapa pun.
            </p>
          </div>
        </div>
      </div>
    </section>

    <footer id="kontak" class="bg-gray-900 text-white py-16 text-center">
      <p class="text-gray-400">© 2025 Kelola Tugas</p>
      <h2 class="text-xl md:text-2xl font-bold my-6">Hubungi Kami</h2>
      <p class="text-gray-300 mb-2">Punya pertanyaan? Kami siap bantu.</p>
      <p>Email: <a href="mailto:support@kelolatugas.id" class="text-blue-400 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-1">support@kelolatugas.id</a></p>
    </footer>
  </div>

  <script>
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
      }, 400); // Sesuaikan dengan durasi transisi
    }

    menuToggle.addEventListener("click", openMobileMenu);
    closeSidebar.addEventListener("click", closeMobileMenu);
    backdrop.addEventListener("click", closeMobileMenu);

    let lastScroll = 0;
    const navbar = document.getElementById("navbar"); // Gunakan ID untuk navbar

    window.addEventListener("scroll", () => {
      let current = window.scrollY;
      if (current > lastScroll && current > 60) {
        navbar.classList.add("navbar-hidden");
      } else {
        navbar.classList.remove("navbar-hidden");
      }
      lastScroll = current;
    });

    // --- Swipe Gesture ---
    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', e => {
      touchStartX = e.changedTouches[0].screenX;
    });

    document.addEventListener('touchend', e => {
      touchEndX = e.changedTouches[0].screenX;
      handleSwipe();
    });

    function handleSwipe() {
      const swipeThreshold = 50;
      if (touchStartX - touchEndX > swipeThreshold) {
        if (mobileSidebar.classList.contains("active")) {
          closeMobileMenu();
        }
      }
    }
  </script>
</body>
</html>