
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kelola Tugas — Landing</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'merah': '#e74c3c',
            'biru': '#3498db',
            'hijau': '#2ecc71',
          }
        }
      }
    }
  </script>
  <style>
    .navbar-hidden {
      transform: translateY(-100%);
    }

    .hover-lift {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .card-icon {
      width: 50px;
      height: 50px;
      margin: 0 auto 1rem;
    }
  </style>
</head>
<body class="font-sans bg-gray-50 text-gray-800">

  <!-- Navbar -->

  <header class="fixed top-0 left-0 w-full z-50 bg-white shadow-md transition-transform duration-300">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
      <!-- Brand -->
      <div class="flex items-center space-x-2">
        <svg class="w-8 h-8 text-biru" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <span class="text-xl font-bold text-gray-800">Kelola Tugas</span>
      </div>

      <!-- Desktop Navigation -->
      <nav class="hidden md:flex space-x-8">
        <a href="#fitur" class="font-medium text-gray-600 hover:text-biru transition">Fitur</a>
        <a href="#kontak" class="font-medium text-gray-600 hover:text-biru transition">Kontak</a>
      </nav>

 <div class="hidden md:flex items-center space-x-4">
  <!-- Tombol Login -->
  <button
    type="button"
    class="text-gray-600 hover:text-biru font-medium"
    onclick="window.location.href='/routes/web.php?route=auth/login'">
    Login
  </button>

  <!-- Tombol Daftar -->
  <button
    type="button"
    class="px-5 py-2 bg-gradient-to-r from-biru to-indigo-600 text-white rounded-lg hover:opacity-90 transition"
    onclick="window.location.href='/routes/web.php?route=auth/register'">
    Daftar
  </button>
</div>


      <!-- Mobile Menu Button -->
      <button id="menuToggle" class="md:hidden text-gray-600 text-2xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>


    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white py-4 px-4 shadow-inner">
      <div class="flex flex-col space-y-4">
        <a href="#fitur" class="block py-2 hover:text-biru font-medium">Fitur</a>
        <a href="#kontak" class="block py-2 hover:text-biru font-medium">Kontak</a>
        <div class="pt-3 flex flex-col space-y-3 border-t border-gray-200">
          <button class="w-full py-2 text-center border border-biru text-biru rounded-lg hover:bg-blue-50" onclick="window.location.href='/routes/web.php?route=auth/login'">Login</button>
          <!-- Tombol Daftar hanya muncul di mobile -->
          <button class="w-full py-2 bg-gradient-to-r from-biru to-indigo-600 text-white rounded-lg hover:opacity-90" onclick="window.location.href='/routes/web.php?route=auth/register'">Daftar</button>
        </div>
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section class="relative bg-gradient-to-r from-gray-900 to-gray-800 text-white py-36">
    <div class="container mx-auto px-4 text-center">
      <h1 class="text-4xl md:text-6xl font-bold mb-6">Fokus Pada Apa Yang Penting</h1>
      <p class="max-w-3xl mx-auto text-lg text-gray-300 mb-10">
        Aplikasi pengelola tugas berbasis PHP Native: Simpel, cepat, dan efektif untuk mengatur semua pekerjaanmu.
      </p>
      <a href="#coba" class="inline-block bg-gradient-to-r from-hijau to-green-500 hover:opacity-90 text-white font-medium py-3 px-8 rounded-full text-lg transition">Coba Sekarang Gratis</a>
    </div>
  </section>

  <!-- Fitur -->
  <section id="fitur" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">Kenapa Memilih Kelola Tugas?</h2>
      <p class="max-w-2xl mx-auto text-gray-600 mb-16">
        Kami hadir dengan fitur yang kamu butuhkan, tanpa ribet dan langsung bisa dipakai.
      </p>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        <!-- Card 1 -->
        <div class="bg-white p-8 rounded-xl shadow-lg hover-lift">
          <div class="card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-merah w-full h-full">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-3">Status Tugas Jelas</h3>
          <p class="text-gray-600">
            Pantau tugasmu dari <b>Belum Dikerjakan</b>, <b>Sedang Dikerjakan</b>, hingga <b>Selesai</b> dengan warna yang mudah dibaca.
          </p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-8 rounded-xl shadow-lg hover-lift">
          <div class="card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-biru w-full h-full">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-3">Atur Tugas dengan Mudah</h3>
          <p class="text-gray-600">
            Buat, ubah, hapus, dan tambahkan <b>batas waktu</b> untuk setiap tugas agar tidak lupa.
          </p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-8 rounded-xl shadow-lg hover-lift">
          <div class="card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-hijau w-full h-full">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-3">Dibangun dengan PHP Native</h3>
          <p class="text-gray-600">
            Dibuat tanpa framework ribet. Cocok untuk belajar dan dipakai siapa pun.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="kontak" class="bg-gray-900 text-white py-16 text-center">
    <p class="text-gray-400">© 2025 Kelola Tugas</p>
    <h2 class="text-2xl font-bold my-6">Hubungi Kami</h2>
    <p class="text-gray-300 mb-2">Punya pertanyaan? Kami siap bantu.</p>
    <p>Email: <a href="mailto:support@kelolatugas.id" class="text-biru hover:underline">support@kelolatugas.id</a></p>
  </footer>

  <script>
    // Toggle mobile menu
    const menuToggle = document.getElementById("menuToggle");
    const mobileMenu = document.getElementById("mobileMenu");

    menuToggle.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
    });

    // Navbar hide on scroll
    let lastScroll = 0;
    const navbar = document.querySelector("header");

    window.addEventListener("scroll", () => {
      let current = window.scrollY;
      if (current > lastScroll && current > 60) {
        navbar.classList.add("navbar-hidden");
      } else {
        navbar.classList.remove("navbar-hidden");
      }
      lastScroll = current;
    });
  </script>

</body>
</html>
