<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Siswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-blue-50 to-gray-100 min-h-screen font-sans">

  <!-- Header -->
  <header class="bg-blue-700 text-white py-4 shadow-md">
    <div class="container mx-auto px-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold">📘 Dashboard Siswa</h1>
      <a href="?route=auth/logout" 
         class="bg-white text-blue-700 px-4 py-1 rounded-md font-semibold hover:bg-blue-100 transition">
         Logout
      </a>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-6 py-10">
    <h2 class="text-3xl font-bold text-blue-700 mb-6">Daftar Tugas Kamu</h2>

    <?php if (empty($tugas)): ?>
      <div class="bg-white text-center p-8 rounded-xl shadow-sm">
        <p class="text-gray-500 text-lg">Belum ada tugas dari guru. 🎉</p>
      </div>
    <?php else: ?>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($tugas as $t): ?>
          <?php
            // 🔹 Warna status tugas
            $statusTugasColor = match ($t['status_tugas'] ?? '') {
              'aktif' => 'bg-green-100 text-green-700 border-green-300',
              'selesai' => 'bg-blue-100 text-blue-700 border-blue-300',
              'nonaktif' => 'bg-gray-100 text-gray-600 border-gray-300',
              default => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            };

            // 🔹 Warna status pengumpulan
            $statusKumpulClass = match ($t['status_pengumpulan'] ?? '') {
              'selesai' => 'text-green-700 bg-green-100',
              'terlambat' => 'text-red-700 bg-red-100',
              default => 'text-gray-600 bg-gray-100'
            };
          ?>

          <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all border border-gray-100">
            <div class="flex justify-between items-center mb-2">
              <h3 class="text-lg font-semibold text-blue-700">
                <?= htmlspecialchars($t['judul_tugas']) ?>
              </h3>
              <span class="text-xs font-semibold px-3 py-1 rounded-full border <?= $statusTugasColor ?>">
                <?= ucfirst($t['status_tugas'] ?? 'Tidak diketahui') ?>
              </span>
            </div>

            <p class="text-gray-600 text-sm mb-3 line-clamp-3">
              <?= htmlspecialchars($t['deskripsi']) ?>
            </p>

            <div class="text-sm text-gray-500 space-y-1">
              <p>🧑‍🏫 <strong>Guru:</strong> <?= htmlspecialchars($t['nama_guru']) ?></p>
              <p>🏷️ <strong>Kategori:</strong> <?= htmlspecialchars($t['nama_kategori']) ?></p>
              <p>⏰ <strong>Deadline:</strong> 
                <span class="text-orange-600 font-medium"><?= htmlspecialchars($t['tanggal_deadline']) ?></span>
              </p>
            </div>

            <!-- Status pengumpulan -->
            <p class="mt-3 text-sm font-medium px-3 py-1 inline-block rounded-full <?= $statusKumpulClass ?>">
              📄 <?= $t['status_pengumpulan'] 
                    ? 'Sudah dikumpulkan ('.ucfirst($t['status_pengumpulan']).')' 
                    : 'Belum dikumpulkan' ?>
            </p>

            <!-- Tombol detail -->
            <a href="?route=murid/tugas/detail&id=<?= $t['id_tugas'] ?>" 
               class="mt-4 inline-block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
              Lihat Detail
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <!-- Footer -->
  <footer class="text-center text-gray-500 text-sm py-6">
    &copy; <?= date('Y') ?> Sistem Tugas Sekolah • Dibuat dengan 💙 oleh Tika
  </footer>

</body>
</html>
