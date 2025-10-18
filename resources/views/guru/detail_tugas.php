<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Tugas - <?= htmlspecialchars($tugas['judul_tugas'] ?? 'Tugas') ?></title>
    <!-- Perbaikan: Hapus spasi di akhir URL CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-icon { width: 1.25rem; height: 1.25rem; flex-shrink: 0; }
        .fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        function openModal() {
            document.getElementById('modalHapus').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('modalHapus').classList.add('hidden');
        }

        // Auto-hide notifikasi
        document.addEventListener('DOMContentLoaded', () => {
            const notif = document.getElementById('notifMessage');
            if (notif) {
                setTimeout(() => {
                    notif.style.opacity = '0';
                    setTimeout(() => notif.remove(), 500);
                }, 3000);
            }
        });
    </script>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

<?php if (!empty($_SESSION['message'])): ?>
    <div id="notifMessage" class="max-w-5xl mx-auto px-4 mt-6 transition-opacity duration-500 opacity-100">
        <div class="flex items-start gap-3 p-4 rounded-lg border 
            <?php if ($_SESSION['message']['type'] === 'success'): ?>
                bg-green-50 border-green-200 text-green-700
            <?php else: ?>
                bg-red-50 border-red-200 text-red-700
            <?php endif; ?>">
            <?php if ($_SESSION['message']['type'] === 'success'): ?>
                <svg class="hero-icon text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            <?php else: ?>
                <svg class="hero-icon text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            <?php endif; ?>
            <span class="font-medium"><?= htmlspecialchars($_SESSION['message']['text']) ?></span>
        </div>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<!-- Container Utama -->
<div class="max-w-4xl mx-auto my-6 px-4">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="bg-blue-600 text-white px-6 py-5 md:px-8 md:py-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold"><?= htmlspecialchars($tugas['judul_tugas']) ?></h1>
                    <p class="text-sm opacity-90 mt-1">
                        Kategori: <span class="font-semibold"><?= htmlspecialchars($tugas['nama_kategori'] ?? '-') ?></span>
                    </p>
                </div>
                <a href="?route=guru/dashboard" 
                   class="bg-white text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-50 transition font-medium shadow">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- Konten -->
        <div class="p-6 md:p-8 space-y-6 text-gray-700">

            <!-- Deskripsi -->
            <section>
                <h2 class="text-lg font-semibold mb-2 text-gray-800">Deskripsi</h2>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 leading-relaxed">
                    <?= nl2br(htmlspecialchars($tugas['deskripsi'] ?? 'Tidak ada deskripsi.')) ?>
                </div>
            </section>

            <!-- Info Tanggal -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <p class="text-gray-500 text-sm">Tanggal Mulai</p>
                    <p class="font-semibold text-gray-800"><?= htmlspecialchars($tugas['tanggal_mulai'] ?? '-') ?></p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <p class="text-gray-500 text-sm">Deadline</p>
                    <p class="font-semibold text-gray-800"><?= htmlspecialchars($tugas['tanggal_deadline'] ?? '-') ?></p>
                </div>
            </section>

            <!-- Info Poin dan Durasi -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <p class="text-gray-500 text-sm">Poin Nilai</p>
                    <p class="font-semibold text-gray-800"><?= htmlspecialchars($tugas['poin_nilai'] ?? '-') ?></p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <p class="text-gray-500 text-sm">Durasi Estimasi</p>
                    <p class="font-semibold text-gray-800"><?= htmlspecialchars($tugas['durasi_estimasi'] ?? '-') ?> menit</p>
                </div>
            </section>

            <!-- Instruksi Pengumpulan -->
            <section>
                <h2 class="text-lg font-semibold mb-2 text-gray-800">Instruksi Pengumpulan</h2>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 leading-relaxed">
                    <?= nl2br(htmlspecialchars($tugas['instruksi_pengumpulan'] ?? '-')) ?>
                </div>
            </section>

            <!-- Lampiran -->
            <?php if (!empty($tugas['lampiran_guru'])): ?>
                <?php $lampiranPath = "/uploads/tugas/" . htmlspecialchars($tugas['lampiran_guru']); ?>
                <section>
                    <h2 class="text-lg font-semibold mb-3 text-gray-800">Lampiran</h2>
                    <a href="<?= $lampiranPath ?>" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <svg class="hero-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Lihat Lampiran
                    </a>
                </section>
            <?php endif; ?>

            <!-- Status -->
            <section>
                <p class="font-semibold text-gray-800 mb-1">Status Tugas</p>
                <span class="px-4 py-1 rounded-full text-sm font-medium
                    <?= $tugas['status_tugas'] === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <?= htmlspecialchars(ucfirst($tugas['status_tugas'] ?? 'tidak diketahui')) ?>
                </span>
            </section>
        </div>

        <!-- Tombol Aksi -->
        <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex flex-wrap justify-end gap-3">
            <a href="?route=guru/tugas/edit&id=<?= $tugas['id_tugas'] ?>" 
               class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
               Edit
            </a>
            <button onclick="openModal()" 
               class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
               Hapus
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="modalHapus" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 fade-in">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Penghapusan</h2>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus tugas ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-end gap-3">
            <button onclick="closeModal()" 
                class="px-4 py-2 text-gray-600 font-medium rounded hover:bg-gray-100 transition">
                Batal
            </button>
            <a href="?route=guru/tugas/delete&id=<?= $tugas['id_tugas'] ?>"
                class="px-4 py-2 bg-red-500 text-white font-medium rounded hover:bg-red-600 transition">
                Ya, Hapus Tugas
            </a>
        </div>
    </div>
</div>

</body>
</html>