<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Akun</title>
  <!-- ✅ Perbaiki spasi di CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Tidak perlu konfigurasi animasi jika hanya digunakan di landing page -->
</head>

<body class="h-full bg-gray-50">
  <!-- Background animasi lembut (opsional, bisa dihapus jika tidak ingin efek ini di semua halaman) -->
  <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-100 rounded-full opacity-20"></div>
    <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-cyan-100 rounded-full opacity-20"></div>
    <div class="absolute bottom-1/4 left-1/2 w-60 h-60 bg-sky-100 rounded-full opacity-20"></div>
  </div>

  <!-- Container Flex utama -->
  <div class="min-h-full flex items-center justify-center p-4 md:p-6">
    <!-- Grid untuk Form & Ilustrasi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl w-full">
      
      <!-- Bagian Form -->
      <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
        
        <div class="text-center mb-8">
          <h2 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h2>
          <p class="text-gray-600 mt-2 text-sm md:text-base">Isi data lengkapmu untuk mendaftar</p>
        </div>

        <!-- Notifikasi -->
        <?php if (isset($_SESSION['message'])): ?>
          <div class="mb-6 p-3 rounded-lg border 
            <?php if ($_SESSION['message']['type'] === 'danger'): ?>
              bg-red-50 border-red-200 text-red-700
            <?php else: ?>
              bg-green-50 border-green-200 text-green-700
            <?php endif; ?>">
            <span class="font-medium text-sm"><?= htmlspecialchars($_SESSION['message']['text']) ?></span>
          </div>
          <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="POST" action="?route=auth/doRegister" class="space-y-5">
          <!-- Nama Lengkap -->
          <div>
            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                   placeholder="Nama lengkap Anda" />
          </div>


          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                   placeholder="email@contoh.com" />
          </div>

          <!-- Kelas (opsional) -->
          <div>
            <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas (opsional)</label>
            <select name="kelas" id="kelas"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
              <option value="">-- Pilih Kelas --</option>
              <?php
              $tingkatan = ['X', 'XI', 'XII'];
              $jurusan = ['PPLG', 'DKV', 'MPLB', 'TJKT'];

              foreach ($tingkatan as $t) {
                  foreach ($jurusan as $j) {
                      $kelas = "$t $j";
                      echo "<option value='" . htmlspecialchars($kelas) . "'>" . htmlspecialchars($kelas) . "</option>";
                  }
              }
              ?>
            </select>
          </div>

          <!-- NIP/NIS -->
          <div>
            <label for="nip_nis" class="block text-sm font-medium text-gray-700 mb-1">NIP / NIS</label>
            <input type="text" name="nip_nis" id="nip_nis"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                   placeholder="Masukkan NIP (guru) atau NIS (siswa)" required/>
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" id="password" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                   placeholder="••••••••" />
          </div>

          <!-- Konfirmasi Password -->
          <div>
            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                   placeholder="••••••••" />
          </div>

          <!-- Role tersembunyi -->
          <input type="hidden" name="peran" value="siswa" />

          <!-- Tombol -->
          <button type="submit"
            class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Daftar Sekarang
          </button>
        </form>

        <div class="mt-6 text-center">
          <a href="?route=home" class="text-sm text-gray-600 hover:text-gray-900 transition">← Kembali ke Beranda</a>
        </div>

        <div class="mt-4 text-center text-sm text-gray-600">
          Sudah punya akun?
          <a href="?route=auth/login" class="text-blue-600 font-medium hover:underline">Masuk di sini</a>
        </div>
      </div>

      <!-- Bagian Ilustrasi / Dekoratif (Hanya muncul di md ke atas) -->
      <div class="hidden md:flex items-center justify-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl"> <!-- Tambahkan latar belakang lembut -->
        <div class="max-w-xs w-full text-center">
          <!-- Ikon Tugas -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 mx-auto text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>
          <h3 class="text-lg font-semibold text-gray-700 mt-4">Mulai Kelola Tugasmu!</h3>
          <p class="text-gray-500 text-sm mt-2">
            Buat akun untuk mengakses fitur pengelolaan tugas yang membantumu belajar lebih terorganisir.
          </p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>