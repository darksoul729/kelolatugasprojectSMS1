<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Hari Ini</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans flex items-center justify-center">

  <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 max-w-sm w-full text-center animate-fadeIn">
    <!-- Ikon Centang Bundar -->
    <div class="flex justify-center mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-green-500">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
      </svg>
    </div>

    <!-- Pesan -->
    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Laporan Hari Ini Sudah Diisi!</h2>
    <p class="text-gray-500 text-sm md:text-base mb-6">
      Laporan hanya bisa diisi sekali setiap hari.
    </p>

    <!-- Tombol Kembali -->
    <a href="?route=murid/dashboard" 
       class="inline-block w-full px-6 py-3 bg-blue-600 text-white font-medium rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition duration-200">
      Kembali ke Dashboard
    </a>
  </div>

  <!-- Optional: Animasi fade-in -->
  <style>
    .animate-fadeIn {
      animation: fadeIn 0.5s ease-out forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

</body>
</html>
