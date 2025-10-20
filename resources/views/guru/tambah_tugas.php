<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buat Tugas Baru</title>
  <!-- ✅ Perbaiki spasi di CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-4 md:p-6 font-sans">

  <div class="max-w-3xl mx-auto">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Buat Tugas Baru</h1>

    <form 
      action="?route=guru/tugas/tambah" 
      method="POST" 
      enctype="multipart/form-data"
      class="space-y-6 bg-white p-6 rounded-xl shadow-md"
    >
      <!-- Judul -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Tugas <span class="text-red-500">*</span></label>
        <input 
          type="text" 
          name="judul_tugas" 
          required
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
      </div>

      <!-- Deskripsi -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
        <textarea 
          name="deskripsi" 
          rows="3"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        ></textarea>
      </div>

      <!-- Kategori (Hanya muncul jika data kategori tersedia) -->
      <?php if (isset($kategori) && !empty($kategori)): ?>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
          <select 
            name="id_kategori" 
            required
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">— Pilih Kategori —</option>
            <?php foreach ($kategori as $k): ?>
              <option value="<?= (int)$k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php else: ?>
        <!-- Jika kategori tidak ada, kita tetap butuh field 'id_kategori' untuk validasi server-side -->
        <!-- Kita bisa gunakan hidden input atau tampilkan pesan error -->
        <!-- Contoh: Tampilkan pesan error -->
        <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
          <p class="font-medium">Error: Data kategori tidak ditemukan.</p>
          <p>Silakan hubungi administrator untuk menambahkan kategori terlebih dahulu.</p>
        </div>
        <!-- Atau, jika ingin tetap mengizinkan submit dengan kategori kosong (tidak disarankan untuk required di backend): -->
        <!--
        <input type="hidden" name="id_kategori" value="" />
        -->
      <?php endif; ?>

      <!-- Kelas (Selalu muncul) -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas (opsional)</label>
        <select 
          name="kelas" 
          id="kelas"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="">— Pilih Kelas —</option>
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

      <!-- Tanggal Mulai & Deadline -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
          <input 
            type="datetime-local" 
            name="tanggal_mulai"
            value="<?= date('Y-m-d\TH:i') ?>"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Deadline <span class="text-red-500">*</span></label>
          <input 
            type="datetime-local" 
            name="tanggal_deadline" 
            required
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
      </div>

      <!-- Durasi & Poin -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Estimasi (menit)</label>
          <input 
            type="number" 
            name="durasi_estimasi" 
            min="0"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Poin Nilai</label>
          <input 
            type="number" 
            name="poin_nilai" 
            value="100" 
            min="0"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
      </div>

      <!-- Instruksi Pengumpulan -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Instruksi Pengumpulan</label>
        <textarea 
          name="instruksi_pengumpulan" 
          rows="3"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        ></textarea>
      </div>

      <!-- Lampiran -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran Guru (opsional)</label>
        <input 
          type="file" 
          name="lampiran_guru"
          accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png"
          class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
        />
      </div>

      <!-- Status -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Tugas</label>
        <select 
          name="status_tugas"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="aktif">Aktif</option>
          <option value="ditutup">Ditutup</option>
          <option value="dibatalkan">Dibatalkan</option>
        </select>
      </div>

      <!-- Tombol Submit -->
      <div class="pt-4">
        <button 
          type="submit"
          class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
        >
          Simpan Tugas
        </button>
      </div>
    </form>
  </div>

</body>
</html>