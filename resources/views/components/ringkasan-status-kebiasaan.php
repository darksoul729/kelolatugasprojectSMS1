<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ringkasan Kebiasaan Kelas <?= htmlspecialchars($kelas) ?></title>
  <!-- ✅ Perbaiki: hapus spasi ekstra di CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

  <!-- Header -->
  <header class="sticky top-0 z-50 bg-white shadow-md py-4 px-4 md:px-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
    <h1 class="text-xl md:text-2xl font-bold">
      Ringkasan Kebiasaan Bulan <?= date('F Y') ?> - Kelas <?= htmlspecialchars($kelas) ?>
    </h1>
    <a href="?route=guru/dashboard" class="px-3 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition text-sm whitespace-nowrap">
      Kembali ke Dashboard
    </a>
  </header>

  <main class="container mx-auto px-4 md:px-6 py-6 md:py-8">
    <div class="bg-white rounded-xl shadow-md p-4 md:p-6 mb-6">
      <h2 class="text-xl md:text-2xl font-semibold text-gray-800">Kelas <?= htmlspecialchars($kelas) ?></h2>
      <p class="text-gray-500 text-sm">Bulan <?= date('F Y') ?></p>
    </div>

    <!-- Table Wrapper -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full border-collapse text-sm">
        <thead class="bg-gray-100 border-b">
          <tr>
            <th class="py-3 px-4 text-left font-semibold text-gray-600">Nama Siswa</th>
            <th class="py-3 px-4 text-center font-semibold text-gray-600">Bangun Pagi</th>
            <th class="py-3 px-4 text-center font-semibold text-gray-600">Beribadah</th>
            <th class="py-3 px-4 text-center font-semibold text-gray-600">Olahraga</th>
            <th class="py-3 px-4 text-center font-semibold text-gray-600">Makan Sehat</th>
            <th class="py-3 px-4 text-center font-semibold text-gray-600">Belajar</th>
            <th class="py-3 px-4 text-center font-semibold text-gray-600">Bermasyarakat</th>
            <th class="py-3 px-4 text-center font-semibold text-gray-600">Tidur Cepat</th>
            <th class="py-3 px-4 text-center font-semibold text-gray-600">Detail</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $kebiasaanList = [
            'bangun_pagi' => 'Bangun Pagi',
            'beribadah' => 'Beribadah',
            'berolahraga' => 'Olahraga',
            'makan_sehat' => 'Makan Sehat',
            'gemar_belajar' => 'Belajar',
            'bermasyarakat' => 'Bermasyarakat',
            'tidur_cepat' => 'Tidur Cepat'
          ];
          ?>

          <?php foreach ($rekap as $nama => $bulanData): ?>
            <?php foreach ($bulanData as $bulan => $item): ?>
              <?php
                $dataJson = [];
                foreach ($kebiasaanList as $field => $label) {
                  $hari = $item[$field]['terbiasa'] ?? 0;
                  if ($hari <= 10) {
                    $status = "Kurang";
                    $warna = "text-red-600 font-semibold";
                  } elseif ($hari <= 20) {
                    $status = "Cukup";
                    $warna = "text-yellow-600 font-semibold";
                  } else {
                    $status = "Sangat Baik";
                    $warna = "text-green-600 font-semibold";
                  }
                  $dataJson[$field] = compact('label', 'hari', 'status', 'warna');
                }
              ?>
              <tr class="hover:bg-gray-50 transition border-b">
                <td class="px-4 py-3 font-medium text-gray-800"><?= htmlspecialchars($nama) ?></td>
                <?php foreach ($kebiasaanList as $field => $label): ?>
                  <td class="px-4 py-3 text-center">
                    <span class="<?= $dataJson[$field]['warna'] ?>"><?= $dataJson[$field]['status'] ?></span>
                  </td>
                <?php endforeach; ?>
                <td class="px-4 py-3 text-center">
                  <button 
                    onclick='bukaDetail("<?= htmlspecialchars($nama) ?>", <?= json_encode($dataJson) ?>)'
                    class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-xs md:text-sm">
                    Detail
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal Detail -->
  <div id="modal-detail" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4">
    <div class="bg-white rounded-xl p-5 w-full max-w-md max-h-[90vh] overflow-y-auto shadow-lg border-t-4 border-blue-600">
      <h2 class="text-lg md:text-xl font-bold mb-4 text-gray-800">Detail Kebiasaan</h2>
      <div id="detail-content" class="text-gray-700 text-sm">Memuat...</div>
      <div class="mt-5 text-right">
        <button 
          onclick="tutupModal()" 
          class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition text-sm">
          Tutup
        </button>
      </div>
    </div>
  </div>

  <script>
    function bukaDetail(nama, data) {
      const modal = document.getElementById('modal-detail');
      const content = document.getElementById('detail-content');
      modal.classList.remove('hidden');

      let html = `<p class="mb-3 text-gray-800">Detail kebiasaan untuk <strong>${nama}</strong></p>`;
      html += `<table class="w-full text-sm border rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="px-2 py-1 text-left">Kebiasaan</th>
            <th class="px-2 py-1 text-center">Hari</th>
            <th class="px-2 py-1 text-center">Status</th>
          </tr>
        </thead>
        <tbody>`;

      for (const key in data) {
        const keb = data[key];
        html += `
          <tr class="border-t hover:bg-gray-50">
            <td class="px-2 py-1">${keb.label}</td>
            <td class="px-2 py-1 text-center">${keb.hari}</td>
            <td class="px-2 py-1 text-center ${keb.warna}">${keb.status}</td>
          </tr>`;
      }

      html += `</tbody></table>`;
      content.innerHTML = html;
    }

    function tutupModal() {
      document.getElementById('modal-detail').classList.add('hidden');
    }
  </script>

</body>
</html>