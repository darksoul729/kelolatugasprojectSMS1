<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          animation: {
            float: 'float 6s ease-in-out infinite',
            pulseSlow: 'pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite',
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-12px)' },
            }
          }
        }
      }
    }
  </script>
  <style type="text/tailwindcss">
    @layer utilities {
      .bg-gradient-main {
        background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 50%, #f0f9ff 100%);
      }
      .bg-card-custom {
        background-color: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }
      .input-group { position: relative; margin-bottom: 1.5rem; }
      .input-icon {
        position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
        width: 1.25rem; height: 1.25rem; color: #9ca3af; pointer-events: none;
      }
      .input-field { 
        width: 100%; padding-left: 3rem; padding-top: 0.75rem; padding-bottom: 0.75rem;
        border: 2px solid #d1d5db; border-radius: 0.5rem;
        background-color: white;
        transition: border-color 0.2s, box-shadow 0.2s;
      }
      .input-field:focus {
        outline: none; border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      }
      .input-label {
        display: block; margin-bottom: 0.25rem;
        font-size: 0.875rem; font-weight: 500; color: #374151;
      }
      .btn-gradient {
        width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem;
        background: linear-gradient(to right, #2563eb, #0891b2);
        color: white; font-weight: 500; transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }
      .btn-gradient:hover {
        background: linear-gradient(to right, #1d4ed8, #0e7490);
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
      }
      .btn-outline {
        width: 100%; padding: 0.75rem 1rem;
        border-radius: 0.5rem; border: 2px solid #d1d5db;
        background-color: white; color: #374151;
        font-weight: 500; transition: all 0.3s ease;
      }
      .btn-outline:hover { background-color: #f9fafb; border-color: #9ca3af; }
    }
  </style>
</head>

<body class="h-full bg-gradient-main flex items-center justify-center p-6 overflow-hidden relative">

  <!-- Efek Bubble Background -->
  <div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float"></div>
    <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float" style="animation-delay: -2s;"></div>
    <div class="absolute bottom-1/4 left-1/2 w-60 h-60 bg-sky-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulseSlow"></div>
  </div>

  <!-- Kartu Dua Kolom -->
  <div class="bg-card-custom rounded-2xl shadow-xl overflow-hidden w-full max-w-5xl grid md:grid-cols-2">
    
    <!-- Bagian Form -->
    <div class="p-8 bg-white bg-opacity-100 relative">
      <h2 class="text-3xl font-bold text-center mb-2 text-gray-800">Buat Akun</h2>
      <p class="text-center text-gray-600 mb-6">Ayo mulai perjalananmu!</p>

      <!-- Tampilkan pesan dari session -->
      <?php if (isset($_SESSION['message'])): ?>
        <div class="bg-<?= $_SESSION['message']['type'] === 'danger' ? 'red' : 'green' ?>-100 text-<?= $_SESSION['message']['type'] === 'danger' ? 'red' : 'green' ?>-700 p-3 rounded-md mb-4 text-center">
          <?= htmlspecialchars($_SESSION['message']['text']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
      <?php endif; ?>

      <form method="POST" action="?route=auth/doRegister">
        <div class="input-group">
          <label for="nama_lengkap" class="input-label">Nama Lengkap</label>
          <div class="relative">
            <div class="input-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <input type="text" name="nama_lengkap" id="nama_lengkap" class="input-field" placeholder="Nama Lengkap Anda" required>
          </div>
        </div>

        <div class="input-group">
          <label for="username" class="input-label">Username</label>
          <div class="relative">
            <div class="input-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <input type="text" name="username" id="username" class="input-field" placeholder="Username Anda" required>
          </div>
        </div>

        <div class="input-group">
          <label for="email" class="input-label">Email</label>
          <div class="relative">
            <div class="input-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
            </div>
            <input type="email" name="email" id="email" class="input-field" placeholder="email@contoh.com" required>
          </div>
        </div>

        <div class="input-group">
          <label for="password" class="input-label">Password</label>
          <div class="relative">
            <div class="input-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
            <input type="password" name="password" id="password" class="input-field" placeholder="••••••••" required>
          </div>
        </div>

        <div class="input-group">
          <label for="confirm_password" class="input-label">Konfirmasi Password</label>
          <div class="relative">
            <div class="input-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
            <input type="password" name="confirm_password" id="confirm_password" class="input-field" placeholder="••••••••" required>
          </div>
        </div>

        <!-- Role (opsional: default siswa) -->
        <input type="hidden" name="peran" value="siswa">

        <button type="submit" class="btn-gradient">Daftar Sekarang</button>
      </form>

      <div class="mt-6">
        <a href="?route=home" class="btn-outline">← Kembali ke Beranda</a>
      </div>

      <p class="text-center mt-6 text-gray-600">
        Sudah punya akun?
        <a href="?route=auth/login" class="text-blue-600 font-medium hover:underline transition">Masuk di sini</a>
      </p>
    </div>

    <!-- Bagian Dekorasi -->
    <div class="relative hidden md:flex items-center justify-center bg-gradient-to-tr from-blue-100 via-cyan-100 to-white overflow-hidden">
      <div class="absolute inset-0 flex justify-center items-center">
        <div class="absolute w-64 h-64 bg-blue-200 rounded-full opacity-20 blur-3xl animate-float"></div>
        <div class="absolute w-80 h-80 bg-cyan-300 rounded-full opacity-20 blur-3xl animate-pulseSlow" style="animation-delay:-3s;"></div>
      </div>
      <div class="relative z-10 text-center p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang!</h3>
        <p class="text-gray-600 max-w-xs">Bergabunglah dan kelola tugas sekolahmu dengan mudah.</p>
      </div>
    </div>
  </div>
</body>
</html>