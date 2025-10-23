<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Kebiasaan Anak</title>
  <!-- ✅ Perbaiki: hapus spasi ekstra di CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .form-section {
      @apply border border-gray-200 rounded-xl p-4 md:p-6 shadow-sm hover:shadow-md transition-shadow;
    }
    .info-box {
      @apply bg-blue-50 border border-blue-100 p-3 md:p-4 rounded-lg mb-4 md:mb-6;
    }
    .info-label {
      @apply font-semibold text-blue-800 text-xs md:text-sm;
    }
    .info-value {
      @apply text-blue-900 font-medium text-xs md:text-sm;
    }
    .hidden { display: none; }
  </style>
</head>
<body class="bg-gray-50 font-sans">

  <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-4 md:p-6 mt-6 md:mt-8 mb-8 md:mb-12">
    <h1 class="text-xl md:text-2xl font-bold text-center mb-4 md:mb-6 text-blue-700">Form Kebiasaan Anak</h1>

    <form id="kebiasaanForm" action="?route=murid/kebiasaan/simpan" method="POST" enctype="multipart/form-data" class="space-y-4 md:space-y-6">
      <!-- Info Box -->
      <div class="info-box">
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-4">
          <div class="flex flex-col sm:flex-row sm:items-center gap-1 md:gap-2">
            <span class="info-label">Nama:</span>
            <span class="info-value break-words"><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? 'Nama Tidak Ditemukan') ?></span>
          </div>
          <div class="flex flex-col sm:flex-row sm:items-center gap-1 md:gap-2">
            <span class="info-label">Kelas:</span>
            <span class="info-value break-words"><?= htmlspecialchars($_SESSION['user']['kelas'] ?? 'Kelas Tidak Ditemukan') ?></span>
          </div>
        </div>
      </div>

      <!-- Bangun Pagi -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-2 md:mb-3 text-base md:text-lg">Kebiasaan Bangun Pagi</h2>
        <label class="flex items-center gap-2 md:gap-3 mb-2 md:mb-3">
          <input type="checkbox" name="bangun_pagi" class="accent-blue-600 h-4 w-4 md:h-5 md:w-5">
          <span class="text-gray-700 text-sm md:text-base">Bangun Pagi</span>
        </label>
        <div>
          <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Jam Bangun</label>
          <input type="time" name="jam_bangun" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
      </div>

      <!-- Beribadah -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-2 md:mb-3 text-base md:text-lg">Kebiasaan Beribadah</h2>
        <label class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
          <input type="checkbox" name="beribadah" class="accent-blue-600 h-4 w-4 md:h-5 md:w-5">
          <span class="text-gray-700 text-sm md:text-base">Beribadah</span>
        </label>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-3 md:mb-4">
          <div>
            <label for="agama" class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Agama</label>
            <select name="agama" id="agama" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
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
            <label for="ibadah_lainnya" class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Ibadah Lainnya</label>
            <input type="text" id="ibadah_lainnya" name="ibadah_lainnya" placeholder="Jelaskan ibadah lainnya" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
        </div>

        <div id="sholatContainer" class="mt-3 md:mt-4 hidden">
          <label class="block text-xs md:text-sm font-medium text-gray-600 mb-2">Sholat Wajib (jika beragama Islam)</label>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-1 md:gap-2">
            <label class="flex items-center gap-1 md:gap-2">
              <input type="checkbox" name="sholat_subuh" class="accent-blue-600 h-3 w-3 md:h-4 md:w-4">
              <span class="text-gray-700 text-xs md:text-sm">Subuh</span>
            </label>
            <label class="flex items-center gap-1 md:gap-2">
              <input type="checkbox" name="sholat_dzuhur" class="accent-blue-600 h-3 w-3 md:h-4 md:w-4">
              <span class="text-gray-700 text-xs md:text-sm">Dzuhur</span>
            </label>
            <label class="flex items-center gap-1 md:gap-2">
              <input type="checkbox" name="sholat_ashar" class="accent-blue-600 h-3 w-3 md:h-4 md:w-4">
              <span class="text-gray-700 text-xs md:text-sm">Ashar</span>
            </label>
            <label class="flex items-center gap-1 md:gap-2">
              <input type="checkbox" name="sholat_maghrib" class="accent-blue-600 h-3 w-3 md:h-4 md:w-4">
              <span class="text-gray-700 text-xs md:text-sm">Maghrib</span>
            </label>
            <label class="flex items-center gap-1 md:gap-2">
              <input type="checkbox" name="sholat_isya" class="accent-blue-600 h-3 w-3 md:h-4 md:w-4">
              <span class="text-gray-700 text-xs md:text-sm">Isya</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Olahraga -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-2 md:mb-3 text-base md:text-lg">Kebiasaan Berolahraga</h2>
        <label class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
          <input type="checkbox" name="berolahraga" class="accent-blue-600 h-4 w-4 md:h-5 md:w-5">
          <span class="text-gray-700 text-sm md:text-base">Berolahraga</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-3 md:mb-4">
          <div>
            <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Jam Mulai</label>
            <input type="time" name="jam_olahraga_mulai" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
          <div>
            <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Jam Selesai</label>
            <input type="time" name="jam_olahraga_selesai" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
        </div>
      </div>

      <!-- Makan Sehat -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-2 md:mb-3 text-base md:text-lg">Kebiasaan Makan Sehat</h2>
        <label class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
          <input type="checkbox" name="makan_sehat" class="accent-blue-600 h-4 w-4 md:h-5 md:w-5">
          <span class="text-gray-700 text-sm md:text-base">Makan Sehat</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
          <div>
            <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Makan Pagi</label>
            <input type="text" name="makan_pagi" placeholder="Menu pagi" class="border border-gray-300 p-2 rounded-lg w-full mb-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
          <div>
            <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Makan Siang</label>
            <input type="text" name="makan_siang" placeholder="Menu siang" class="border border-gray-300 p-2 rounded-lg w-full mb-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
          <div>
            <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Makan Malam</label>
            <input type="text" name="makan_malam" placeholder="Menu malam" class="border border-gray-300 p-2 rounded-lg w-full mb-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
        </div>
      </div>

      <!-- Gemar Belajar -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-2 md:mb-3 text-base md:text-lg">Gemar Belajar</h2>
        <label class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
          <input type="checkbox" name="gemar_belajar" class="accent-blue-600 h-4 w-4 md:h-5 md:w-5">
          <span class="text-gray-700 text-sm md:text-base">Gemar Belajar</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-3 md:mb-4">
          <div>
            <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Jam Mulai</label>
            <input type="time" name="jam_belajar_mulai" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
          <div>
            <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Jam Selesai</label>
            <input type="time" name="jam_belajar_selesai" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
        </div>
        <div>
          <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Materi yang Dipelajari</label>
          <input type="text" name="materi_belajar" placeholder="Contoh: Matematika Bab 3" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
      </div>

      <!-- Bermasyarakat -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-2 md:mb-3 text-base md:text-lg">Kegiatan Bermasyarakat</h2>
        <label class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
          <input type="checkbox" name="bermasyarakat" class="accent-blue-600 h-4 w-4 md:h-5 md:w-5">
          <span class="text-gray-700 text-sm md:text-base">Bermasyarakat</span>
        </label>
        <div class="mb-3 md:mb-4">
          <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Keterangan</label>
          <textarea name="ket_masyarakat" placeholder="Ceritakan kegiatanmu..." class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" rows="2"></textarea>
        </div>
      </div>

      <!-- Tidur Cepat -->
      <div class="form-section">
        <h2 class="font-semibold text-blue-600 mb-2 md:mb-3 text-base md:text-lg">Kebiasaan Tidur Cepat</h2>
        <label class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
          <input type="checkbox" name="tidur_cepat" class="accent-blue-600 h-4 w-4 md:h-5 md:w-5">
          <span class="text-gray-700 text-sm md:text-base">Tidur Cepat</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
          <div>
            <label class="block text-xs md:text-sm font-medium text-gray-600 mb-1">Jam Tidur</label>
            <input type="time" name="jam_tidur" class="border border-gray-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          </div>
        </div>
      </div>

      <!-- Tombol Submit dan Kembali -->
      <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-4 md:pt-6">
        <button type="button" onclick="window.location.href='?route=murid/dashboard'"
                class="w-full sm:w-auto bg-gray-500 text-white px-4 py-2 md:px-6 md:py-2 rounded-lg hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 font-medium text-sm">
          Kembali ke Dashboard
        </button>
        <button type="button" id="submitBtn"
                class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 md:px-8 md:py-3 rounded-lg hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium text-sm">
          Simpan Kebiasaan
        </button>
      </div>
    </form>
  </div>

  <!-- Modal Konfirmasi -->
  <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl p-5 w-full max-w-md">
      <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi Pengiriman</h3>
      <p class="text-gray-600 mb-5">Apakah Anda yakin ingin menyimpan data kebiasaan hari ini?</p>
      <div class="flex justify-end gap-2">
        <button onclick="closeConfirmModal()"
                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition text-sm">
          Batal
        </button>
        <button onclick="submitForm()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
          Ya, Kirim
        </button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const agamaSelect = document.getElementById('agama');
      const sholatContainer = document.getElementById('sholatContainer');
      const ibadahLainnyaContainer = document.getElementById('ibadahLainnyaContainer');
      const submitBtn = document.getElementById('submitBtn');
      const confirmModal = document.getElementById('confirmModal');

      function toggleIbadahFields() {
        const selectedValue = agamaSelect.value;
        if (selectedValue === 'Islam') {
          sholatContainer.classList.remove('hidden');
          ibadahLainnyaContainer.classList.add('hidden');
          document.getElementById('ibadah_lainnya').value = '';
        } else if (selectedValue && selectedValue !== 'Lainnya') {
          sholatContainer.classList.add('hidden');
          ibadahLainnyaContainer.classList.remove('hidden');
          document.querySelectorAll('input[name^="sholat_"]').forEach(checkbox => checkbox.checked = false);
        } else {
          sholatContainer.classList.add('hidden');
          ibadahLainnyaContainer.classList.add('hidden');
          document.getElementById('ibadah_lainnya').value = '';
          document.querySelectorAll('input[name^="sholat_"]').forEach(checkbox => checkbox.checked = false);
        }
      }

      agamaSelect.addEventListener('change', toggleIbadahFields);

      // Tampilkan modal konfirmasi
      submitBtn.addEventListener('click', () => {
        confirmModal.classList.remove('hidden');
      });

      window.closeConfirmModal = () => {
        confirmModal.classList.add('hidden');
      };

      window.submitForm = () => {
        document.getElementById('kebiasaanForm').submit();
      };
    });
  </script>

</body>
</html>