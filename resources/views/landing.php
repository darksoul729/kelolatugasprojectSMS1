<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jurnal Tujuh KAIH — Kebiasaan Anak Indonesia Hebat</title>
  <link rel="icon" href="../../public//img//smpn21.jpg" type="images/jpg/jpeg">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    
    /* Parallax Styles */
    .parallax-container {
      position: relative;
      overflow: hidden;
    }
    
    .parallax-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 120%;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      transform: translateZ(0);
      will-change: transform;
      z-index: -1;
    }
    
    .parallax-content {
      position: relative;
      z-index: 1;
    }
    
    .parallax-section {
      position: relative;
      overflow: hidden;
    }
    
    .floating-element {
      animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
      100% { transform: translateY(0px); }
    }
    
    .parallax-card {
      transition: transform 0.5s ease, box-shadow 0.5s ease;
      transform-style: preserve-3d;
    }
    
    .parallax-card:hover {
      transform: translateY(-10px) rotateX(5deg);
      box-shadow: 0 20px 30px rgba(0, 0, 0, 0.15);
    }
    
    .stagger-animation > * {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }
    
    .animate-in {
      opacity: 1;
      transform: translateY(0);
    }
    
    /* Improved Parallax */
    .parallax-layer {
      position: absolute;
      width: 100%;
      height: 120%;
      will-change: transform;
    }
    
    .parallax-layer-1 {
      z-index: -3;
      transform: translateZ(-300px) scale(2);
    }
    
    .parallax-layer-2 {
      z-index: -2;
      transform: translateZ(-150px) scale(1.5);
    }
    
    .parallax-layer-3 {
      z-index: -1;
      transform: translateZ(0) scale(1);
    }
    
    /* Particle Background */
    .particles-container {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: -1;
    }
    
    .particle {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      animation: float-particle 15s infinite linear;
    }
    
    @keyframes float-particle {
      0% {
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
      }
      10% {
        opacity: 1;
      }
      90% {
        opacity: 1;
      }
      100% {
        transform: translateY(-100px) rotate(360deg);
        opacity: 0;
      }
    }
    
    /* Gradient Text */
    .gradient-text {
      background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    /* Glass Effect */
    .glass-effect {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f5f9;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #3b82f6;
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #2563eb;
    }
  </style>
</head>
 

<body class="font-sans bg-gray-50 text-gray-800 overflow-x-hidden">

<?php if (!empty($_SESSION['message'])): ?>
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
        <a href="#footer" class="font-medium text-gray-600 hover:text-blue-600 transition">Kontak</a>
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
      <a href="#footer" class="font-medium text-gray-700 py-3 px-4 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition-colors duration-300 flex items-center">
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
    <!-- Hero Section dengan Parallax -->
    <section class="parallax-container relative bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-28 md:py-36">
      <div class="particles-container" id="particles-hero"></div>
      <div class="parallax-layer parallax-layer-1" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwMCIgaGVpZ2h0PSI4MDAiIHZpZXdCb3g9IjAgMCAxMjAwIDgwMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjEyMDAiIGhlaWdodD0iODAwIiBmaWxsPSIjMzg2N0ZGIi8+CjxwYXRoIGQ9Ik0wIDQwMEwxMjAwIDBIMFY0MDBaIiBmaWxsPSIjMzg1RkVGIi8+CjxwYXRoIGQ9Ik0xMjAwIDQwMEwwIDgwMEgxMjAwVjQwMFoiIGZpbGw9IiM0QjYxRjkiLz4KPC9zdmc+');"></div>
      <div class="parallax-layer parallax-layer-2" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwMCIgaGVpZ2h0PSI4MDAiIHZpZXdCb3g9IjAgMCAxMjAwIDgwMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjEyMDAiIGhlaWdodD0iODAwIiBmaWxsPSJub25lIi8+CjxjaXJjbGUgY3g9IjMwMCIgY3k9IjEwMCIgcj0iODAiIGZpbGw9IiNGRkYiIG9wYWNpdHk9IjAuMSIvPgo8Y2lyY2xlIGN4PSI5MDAiIGN5PSIyMDAiIHI9IjEyMCIgZmlsbD0iI0ZGRiIgb3BhY2l0eT0iMC4xIi8+CjxjaXJjbGUgY3g9IjEwMDAiIGN5PSI1MDAiIHI9IjEwMCIgZmlsbD0iI0ZGRiIgb3BhY2l0eT0iMC4xIi8+CjxjaXJjbGUgY3g9IjIwMCIgY3k9IjYwMCIgcj0iMTUwIiBmaWxsPSIjRkZGIiBvcGFjaXR5PSIwLjEiLz4KPC9zdmc+');"></div>
      <div class="parallax-content container mx-auto px-4 text-center relative z-10">
        <div class="floating-element">
          <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">Bangun Kebiasaan Hebat, <span class="gradient-text">Mulai Hari Ini!</span></h1>
          <p class="max-w-3xl mx-auto text-lg text-blue-100 mb-10">
            Catat & pantau 7 kebiasaan harianmu: Bangun Pagi, Beribadah, Olahraga, Makan Sehat, Belajar, Bermasyarakat, dan Tidur Cepat.
          </p>
          <a href="?route=auth/register" class="inline-block bg-white text-blue-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg text-lg transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transform hover:scale-105 glass-effect">
            Mulai Jurnal Harianmu
          </a>
        </div>
      </div>
    </section>

    <!-- 7 Kebiasaan dengan Parallax -->
    <section id="kebiasaan" class="parallax-section py-20 bg-gray-50 relative">
      <div class="particles-container" id="particles-kebiasaan"></div>
      <div class="parallax-layer parallax-layer-1" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwMCIgaGVpZ2h0PSI4MDAiIHZpZXdCb3g9IjAgMCAxMjAwIDgwMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjEyMDAiIGhlaWdodD0iODAwIiBmaWxsPSIjRjlGQUZGIi8+CjxwYXRoIGQ9Ik0wIDQwMEwxMjAwIDBIMFY0MDBaIiBmaWxsPSIjRjNGN0ZGIi8+CjxwYXRoIGQ9Ik0xMjAwIDQwMEwwIDgwMEgxMjAwVjQwMFoiIGZpbGw9IiNFRkVGRUYiLz4KPC9zdmc+');"></div>
      <div class="parallax-content container mx-auto px-4 relative z-10">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-4 text-gray-800">7 Kebiasaan Anak Indonesia Hebat</h2>
        <p class="max-w-2xl mx-auto text-center text-gray-600 mb-12">
          Setiap hari adalah kesempatan untuk menjadi versi terbaik dirimu.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 max-w-6xl mx-auto stagger-animation">
          <!-- Kebiasaan Card -->
          <div class="parallax-card bg-white p-5 rounded-xl shadow-md border-l-4 border-blue-500">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-sun text-blue-500"></i>
              </div>
              <h3 class="font-bold text-gray-800">1. Bangun Pagi</h3>
            </div>
            <p class="text-sm text-gray-600">Mulai hari dengan semangat sebelum matahari terbit.</p>
          </div>
          <div class="parallax-card bg-white p-5 rounded-xl shadow-md border-l-4 border-purple-500">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-pray text-purple-500"></i>
              </div>
              <h3 class="font-bold text-gray-800">2. Beribadah</h3>
            </div>
            <p class="text-sm text-gray-600">Hubungkan diri dengan Tuhan setiap hari.</p>
          </div>
          <div class="parallax-card bg-white p-5 rounded-xl shadow-md border-l-4 border-green-500">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-running text-green-500"></i>
              </div>
              <h3 class="font-bold text-gray-800">3. Berolahraga</h3>
            </div>
            <p class="text-sm text-gray-600">Jaga tubuh sehat dan pikiran segar.</p>
          </div>
          <div class="parallax-card bg-white p-5 rounded-xl shadow-md border-l-4 border-yellow-500">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-apple-alt text-yellow-500"></i>
              </div>
              <h3 class="font-bold text-gray-800">4. Makan Sehat</h3>
            </div>
            <p class="text-sm text-gray-600">Konsumsi makanan bergizi seimbang.</p>
          </div>
          <div class="parallax-card bg-white p-5 rounded-xl shadow-md border-l-4 border-indigo-500">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-book text-indigo-500"></i>
              </div>
              <h3 class="font-bold text-gray-800">5. Gemar Belajar</h3>
            </div>
            <p class="text-sm text-gray-600">Perluas wawasan dan kuasai ilmu baru.</p>
          </div>
          <div class="parallax-card bg-white p-5 rounded-xl shadow-md border-l-4 border-pink-500">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-users text-pink-500"></i>
              </div>
              <h3 class="font-bold text-gray-800">6. Bermasyarakat</h3>
            </div>
            <p class="text-sm text-gray-600">Bersosialisasi dan berkontribusi positif.</p>
          </div>
          <div class="parallax-card bg-white p-5 rounded-xl shadow-md border-l-4 border-teal-500">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-bed text-teal-500"></i>
              </div>
              <h3 class="font-bold text-gray-800">7. Tidur Cepat</h3>
            </div>
            <p class="text-sm text-gray-600">Istirahat cukup untuk hari esok yang lebih baik.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Manfaat dengan Parallax -->
    <section id="manfaat" class="parallax-section py-20 bg-white relative">
      <div class="particles-container" id="particles-manfaat"></div>
      <div class="parallax-layer parallax-layer-1" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwMCIgaGVpZ2h0PSI4MDAiIHZpZXdCb3g9IjAgMCAxMjAwIDgwMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjEyMDAiIGhlaWdodD0iODAwIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMCA0MDBMMTIwMCAwSDBWNDAwWiIgZmlsbD0iI0Y5RkFGRiIvPgo8cGF0aCBkPSJNMTIwMCA0MDBMMCA4MDBIMTIwMFY0MDBaIiBmaWxsPSIjRjNGN0ZGIi8+Cjwvc3ZnPg==');"></div>
      <div class="parallax-content container mx-auto px-4 text-center relative z-10">
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-gray-800">Mengapa Gunakan Jurnal Tujuh KAIH?</h2>
        <p class="max-w-2xl mx-auto text-gray-600 mb-16">
          Dibuat khusus untuk pelajar Indonesia yang ingin membentuk karakter unggul sejak dini.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto stagger-animation">
          <div class="p-6 parallax-card bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Refleksi Harian</h3>
            <p class="text-gray-600">Evaluasi diri setiap malam dan rayakan progres kecilmu.</p>
          </div>
          <div class="p-6 parallax-card bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Laporan Bulanan</h3>
            <p class="text-gray-600">Lihat perkembangan kebiasaanmu dalam bentuk ringkasan visual.</p>
          </div>
          <div class="p-6 parallax-card bg-white rounded-xl shadow-sm border border-gray-100">
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

    <!-- Footer -->
    <footer id="footer" class="bg-gray-800 text-white py-12">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <!-- Logo & Deskripsi -->
          <div class="md:col-span-2">
            <div class="flex items-center space-x-2 mb-4">
              <img src="../../public/img/smpn21.jpg" alt="Logo" class="w-10 h-10 object-cover rounded">
              <span class="text-xl font-bold">Jurnal Tujuh KAIH</span>
            </div>
            <p class="text-gray-300 mb-4 max-w-md">
              Platform untuk membantu pelajar Indonesia membangun kebiasaan positif melalui pencatatan dan refleksi harian.
            </p>
            <div class="flex space-x-4">
              <a href="#" class="text-gray-300 hover:text-white transition">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="text-gray-300 hover:text-white transition">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#" class="text-gray-300 hover:text-white transition">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="text-gray-300 hover:text-white transition">
                <i class="fab fa-youtube"></i>
              </a>
            </div>
          </div>
          
          <!-- Quick Links -->
          <div>
            <h3 class="font-bold text-lg mb-4">Tautan Cepat</h3>
            <ul class="space-y-2">
              <li><a href="#kebiasaan" class="text-gray-300 hover:text-white transition">7 Kebiasaan</a></li>
              <li><a href="#manfaat" class="text-gray-300 hover:text-white transition">Manfaat</a></li>
              <li><a href="?route=auth/login" class="text-gray-300 hover:text-white transition">Masuk</a></li>
              <li><a href="?route=auth/register" class="text-gray-300 hover:text-white transition">Daftar</a></li>
            </ul>
          </div>
          
          <!-- Kontak -->
          <div>
            <h3 class="font-bold text-lg mb-4">Kontak Kami</h3>
            <div class="space-y-2">
              <p class="text-gray-300 flex items-center">
                <i class="fas fa-envelope mr-2"></i>
                <a href="mailto:admin@jurnaltujuhkaih.id" class="hover:text-white transition">admin@jurnaltujuhkaih.id</a>
              </p>
              <p class="text-gray-300 flex items-center">
                <i class="fas fa-map-marker-alt mr-2"></i>
                Smkti Airlangga Samarinda
              </p>
            </div>
          </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
          <p>© 2025 XI PPLG Smkti Airlangga Samarinda. All rights reserved.</p>
        </div>
      </div>
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

    // Improved Parallax Effect
    document.addEventListener('scroll', function() {
      const scrolled = window.pageYOffset;
      const parallaxLayers = document.querySelectorAll('.parallax-layer');
      
      parallaxLayers.forEach(function(layer) {
        const speed = layer.classList.contains('parallax-layer-1') ? 0.5 : 
                     layer.classList.contains('parallax-layer-2') ? 0.3 : 0.1;
        const yPos = -(scrolled * speed);
        layer.style.transform = `translate3d(0, ${yPos}px, 0)`;
      });
    });

    // Particle Background
    function createParticles(containerId, count) {
      const container = document.getElementById(containerId);
      if (!container) return;
      
      for (let i = 0; i < count; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Random properties
        const size = Math.random() * 10 + 5;
        const left = Math.random() * 100;
        const animationDuration = Math.random() * 20 + 10;
        const animationDelay = Math.random() * 5;
        
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${left}%`;
        particle.style.animationDuration = `${animationDuration}s`;
        particle.style.animationDelay = `${animationDelay}s`;
        
        container.appendChild(particle);
      }
    }
    
    // Create particles for different sections
    createParticles('particles-hero', 15);
    createParticles('particles-kebiasaan', 10);
    createParticles('particles-manfaat', 10);

    // Scroll Animation
    function checkScroll() {
      const elements = document.querySelectorAll('.stagger-animation > *');
      
      elements.forEach((element, index) => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
          setTimeout(() => {
            element.classList.add('animate-in');
          }, index * 100);
        }
      });
    }
    
    window.addEventListener('scroll', checkScroll);
    window.addEventListener('load', checkScroll);
    
    // Initial check on page load
    checkScroll();
  </script>
</body>
</html>