<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Kebiasaan Anak</title>
  <script src="https://cdn.tailwindcss.com        "></script>
  <style>
    .form-section {
      @apply border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow;
    }
    .info-box {
      @apply bg-blue-50 border border-blue-100 p-4 rounded-lg mb-6;
    }
    .info-label {
      @apply font-semibold text-blue-800 text-sm;
    }
    .info-value {
      @apply text-blue-900 font-medium;
    }
    .hidden { display: none; }
    /* Modal loading CSS dihapus */
  </style>
</head>
<body class="bg-gray-50 font-sans">

  <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-6 mt-8 mb-12">
    <h1 class="text-2xl font-bold text-center mb-6 text-blue-700">Form Kebiasaan Anak</h1>

    <form id="kebiasaanForm" action="?route=murid/kebiasaan/simpan" method="POST" enctype="multipart/form-data" class="space-y-6">
      <!-- Info Box untuk Nama dan Kelas -->
      <div class="info-box">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="flex flex-col sm:flex-row sm:items-center gap-2">
            <span class="info-label">Nama:</span>
            <span class="info-value break-words"><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? 'Nama Tidak Ditemukan') ?></span>
          </div>
          <div class="flex flex-col sm:flex-row sm:items-center gap-2">
            <span class="info-label">Kelas:</span>
            <span class="info-value break-words"><?= htmlspecialchars($_SESSION['user']['kelas'] ?? 'Kelas Tidak Ditemukan') ?></span>
          </div>
        </div>
      </div>
      <!-- /Info Box -->

      <!-- Bangun Pagi -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-3 text-lg">Kebiasaan Bangun Pagi</h2>
        <label class="flex items-center gap-3 mb-3">
          <input type="checkbox" name="bangun_pagi" class="accent-blue-600 h-5 w-5">
          <span class="text-gray-700">Bangun Pagi</span>
        </label>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Jam Bangun</label>
          <input type="time" name="jam_bangun" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
      </div>

      <!-- Beribadah -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-3 text-lg">Kebiasaan Beribadah</h2>
        <label class="flex items-center gap-3 mb-4">
          <input type="checkbox" name="beribadah" class="accent-blue-600 h-5 w-5">
          <span class="text-gray-700">Beribadah</span>
        </label>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
  <div>
    <label for="agama" class="block text-sm font-medium text-gray-600 mb-1">Agama</label>
    <select name="agama" id="agama" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">Pilih Agama...</option>
        <option value="Islam">Islam</option>
        <option value="Kristen">Kristen</option>
        <option value="Katolik">Katolik</option>
        <option value="Hindu">Hindu</option>
        <option value="Buddha">Buddha</option>
        <option value="Konghucu">Konghucu</option>
        <option value="Lainnya">Lainnya</option>
    </select>
  </div>
  <div id="ibadahLainnyaContainer" class="hidden">
    <label for="ibadah_lainnya" class="block text-sm font-medium text-gray-600 mb-1">Ibadah Lainnya</label>
    <input type="text" id="ibadah_lainnya" name="ibadah_lainnya" placeholder="Jelaskan ibadah lainnya" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    <p id="ibadahWarning" class="text-sm text-red-500 mt-1 hidden">Mohon isi ibadah lainnya atau pilih opsi lain.</p>
  </div>
</div>

        <div id="sholatContainer" class="mt-4 hidden">
          <label class="block text-sm font-medium text-gray-600 mb-2">Sholat Wajib (jika beragama Islam)</label>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            <label class="flex items-center gap-2">
              <input type="checkbox" name="sholat_subuh" class="accent-blue-600 h-4 w-4">
              <span class="text-gray-700">Subuh</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" name="sholat_dzuhur" class="accent-blue-600 h-4 w-4">
              <span class="text-gray-700">Dzuhur</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" name="sholat_ashar" class="accent-blue-600 h-4 w-4">
              <span class="text-gray-700">Ashar</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" name="sholat_maghrib" class="accent-blue-600 h-4 w-4">
              <span class="text-gray-700">Maghrib</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" name="sholat_isya" class="accent-blue-600 h-4 w-4">
              <span class="text-gray-700">Isya</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Olahraga -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-3 text-lg">Kebiasaan Berolahraga</h2>
        <label class="flex items-center gap-3 mb-4">
          <input type="checkbox" name="berolahraga" class="accent-blue-600 h-5 w-5">
          <span class="text-gray-700">Berolahraga</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Jam Mulai</label>
            <input type="time" name="jam_olahraga_mulai" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Mulai">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Jam Selesai</label>
            <input type="time" name="jam_olahraga_selesai" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Selesai">
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Foto Olahraga</label>
          <!-- Atribut 'required' dihapus -->
          <input type="file" name="foto_olahraga" accept="image/*" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
      </div>

      <!-- Makan Sehat -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-3 text-lg">Kebiasaan Makan Sehat</h2>
        <label class="flex items-center gap-3 mb-4">
          <input type="checkbox" name="makan_sehat" class="accent-blue-600 h-5 w-5">
          <span class="text-gray-700">Makan Sehat</span>
        </label>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Makan Pagi</label>
            <input type="text" name="makan_pagi" placeholder="Menu pagi" class="border border-gray-300 p-2 rounded-lg w-full mb-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <label class="block text-sm font-medium text-gray-600 mb-1">Foto</label>
            <!-- Atribut 'required' dihapus -->
            <input type="file" name="foto_makan_pagi" accept="image/*" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Makan Siang</label>
            <input type="text" name="makan_siang" placeholder="Menu siang" class="border border-gray-300 p-2 rounded-lg w-full mb-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <label class="block text-sm font-medium text-gray-600 mb-1">Foto</label>
            <!-- Atribut 'required' dihapus -->
            <input type="file" name="foto_makan_siang" accept="image/*" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Makan Malam</label>
            <input type="text" name="makan_malam" placeholder="Menu malam" class="border border-gray-300 p-2 rounded-lg w-full mb-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <label class="block text-sm font-medium text-gray-600 mb-1">Foto</label>
            <!-- Atribut 'required' dihapus -->
            <input type="file" name="foto_makan_malam" accept="image/*" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
        </div>
      </div>

      <!-- Gemar Belajar -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-3 text-lg">Gemar Belajar</h2>
        <label class="flex items-center gap-3 mb-4">
          <input type="checkbox" name="gemar_belajar" class="accent-blue-600 h-5 w-5">
          <span class="text-gray-700">Gemar Belajar</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Jam Mulai</label>
            <input type="time" name="jam_belajar_mulai" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Mulai">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Jam Selesai</label>
            <input type="time" name="jam_belajar_selesai" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Selesai">
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Materi yang Dipelajari</label>
          <input type="text" name="materi_belajar" placeholder="Contoh: Matematika Bab 3" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
      </div>

      <!-- Bermasyarakat -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-3 text-lg">Kegiatan Bermasyarakat</h2>
        <label class="flex items-center gap-3 mb-4">
          <input type="checkbox" name="bermasyarakat" class="accent-blue-600 h-5 w-5">
          <span class="text-gray-700">Bermasyarakat</span>
        </label>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-600 mb-1">Nama Kegiatan</label>
          <input type="text" name="kegiatan_masyarakat" placeholder="Contoh: Gotong Royong" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-600 mb-1">Keterangan</label>
          <textarea name="ket_masyarakat" placeholder="Ceritakan kegiatanmu..." class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Foto Kegiatan</label>
          <!-- Atribut 'required' dihapus -->
          <input type="file" name="foto_masyarakat" accept="image/*" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
      </div>

      <!-- Tidur Cepat -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-3 text-lg">Kebiasaan Tidur Cepat</h2>
        <label class="flex items-center gap-3 mb-4">
          <input type="checkbox" name="tidur_cepat" class="accent-blue-600 h-5 w-5">
          <span class="text-gray-700">Tidur Cepat</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Jam Tidur</label>
            <input type="time" name="jam_tidur" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Keterangan</label>
            <input type="text" name="ket_tidur" placeholder="Contoh: Tidur di kamar sendiri" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
        </div>
      </div>

     

      <!-- Tombol Submit dan Kembali -->
      <div class="flex justify-between items-center pt-6">
        <button type="button" onclick="window.location.href='?route=murid/dashboard'"
                class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 font-medium">
          Kembali ke Dashboard
        </button>
        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium">
          Simpan Kebiasaan
        </button>
      </div>
      <!-- /Tombol Submit dan Kembali -->

    </form>
  </div>

  <!-- Modal Loading dihapus -->

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const agamaSelect = document.getElementById('agama');
      const sholatContainer = document.getElementById('sholatContainer');
      const ibadahLainnyaContainer = document.getElementById('ibadahLainnyaContainer');

      // Fungsi untuk menampilkan/menyembunyikan bagian berdasarkan pilihan agama
      function toggleIbadahFields() {
        const selectedValue = agamaSelect.value;
        if (selectedValue === 'Islam') {
          sholatContainer.classList.remove('hidden');
          ibadahLainnyaContainer.classList.add('hidden');
          // Kosongkan field ibadah_lainnya jika Islam dipilih
          document.getElementById('ibadah_lainnya').value = '';
        } else if (selectedValue && selectedValue !== 'Lainnya') {
          sholatContainer.classList.add('hidden');
          ibadahLainnyaContainer.classList.remove('hidden');
          // Kosongkan checkbox sholat jika agama lain dipilih
          document.querySelectorAll('input[name^="sholat_"]').forEach(checkbox => checkbox.checked = false);
        } else {
          sholatContainer.classList.add('hidden');
          ibadahLainnyaContainer.classList.add('hidden');
          document.getElementById('ibadah_lainnya').value = '';
          document.querySelectorAll('input[name^="sholat_"]').forEach(checkbox => checkbox.checked = false);
        }
      }

      // Tambahkan event listener ke dropdown agama
      agamaSelect.addEventListener('change', toggleIbadahFields);

      // Event listener untuk submit form dihapus karena modal loading dihapus
    });
  </script>

</body>
</html>