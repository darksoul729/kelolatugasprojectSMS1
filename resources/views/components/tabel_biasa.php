<?php

if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$bulan = $bulan ?? '';
$kelas = $kelas ?? '';
$siswa = $siswa ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rekap Kebiasaan Bulan <?= e($bulan) ?> - Kelas <?= e($kelas) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-800">

  <!-- Header -->
  <header class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-200 py-4 px-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold text-blue-700">
        Rekap Kebiasaan Bulan <?= e($bulan) ?>
      </h1>
      <p class="text-gray-500 text-sm">Kelas <?= e($kelas) ?></p>
    </div>

    <!-- Filter Bulan & Tahun -->
    <form method="GET" action="" class="flex flex-wrap items-center gap-3">
      <input type="hidden" name="route" value="guru/tugas">

      <!-- Pilih Bulan -->
      <div>
        <label for="bulan" class="text-sm text-gray-600">Bulan:</label>
        <select name="bulan" id="bulan" class="border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500">
          <?php
            $namaBulan = [
              '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
              '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
            ];

            // Ambil bulan yang tersedia dari database
            $stmt = $this->pdo->prepare("
              SELECT DISTINCT MONTH(created_at) AS bulan
              FROM anak_kebiasaan
              WHERE YEAR(created_at) = :tahun
              ORDER BY bulan ASC
            ");
            $stmt->execute([':tahun' => $tahun]);
            $bulanTersedia = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($bulanTersedia)) {
                $bulanTersedia = [date('m')];
            }

            foreach ($bulanTersedia as $num) {
              $num = str_pad($num, 2, '0', STR_PAD_LEFT);
              $selected = (strpos($bulan, $namaBulan[$num]) !== false) ? 'selected' : '';
              echo "<option value='" . e($num) . "' $selected>" . e($namaBulan[$num]) . "</option>";
            }
          ?>
        </select>
      </div>

      <!-- Pilih Tahun -->
      <div>
        <label for="tahun" class="text-sm text-gray-600">Tahun:</label>
        <select name="tahun" id="tahun" class="border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500">
          <?php
            $stmt = $this->pdo->query("SELECT 
                MIN(YEAR(created_at)) AS tahun_awal, 
                MAX(YEAR(created_at)) AS tahun_akhir 
              FROM anak_kebiasaan
            ");
            $tahunRange = $stmt->fetch(PDO::FETCH_ASSOC);

            $tahunAwal = $tahunRange['tahun_awal'] ?? date('Y');
            $tahunAkhir = $tahunRange['tahun_akhir'] ?? date('Y');

            for ($t = $tahunAwal; $t <= $tahunAkhir; $t++) {
              $selected = (strpos($bulan, (string)$t) !== false) ? 'selected' : '';
              echo "<option value='" . e($t) . "' $selected>" . e($t) . "</option>";
            }
          ?>
        </select>
      </div>

      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow-sm">
        Tampilkan
      </button>
    </form>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-4 md:px-8 py-8">
    
    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
      <table class="min-w-full border-collapse text-sm">
        <thead class="bg-blue-50 border-b border-gray-200 text-gray-700">
          <tr>
            <th class="py-3 px-4 text-left font-semibold">Nama Siswa</th>
            <th class="py-3 px-4 text-center font-semibold">Bangun Pagi</th>
            <th class="py-3 px-4 text-center font-semibold">Beribadah</th>
            <th class="py-3 px-4 text-center font-semibold">Olahraga</th>
            <th class="py-3 px-4 text-center font-semibold">Makan Sehat</th>
            <th class="py-3 px-4 text-center font-semibold">Belajar</th>
            <th class="py-3 px-4 text-center font-semibold">Bermasyarakat</th>
            <th class="py-3 px-4 text-center font-semibold">Tidur Cepat</th>
            <th class="py-3 px-4 text-center font-semibold">Detail</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php 
          $kebiasaanList = [
            'total_bangun_pagi' => 'Bangun Pagi',
            'total_beribadah' => 'Beribadah',
            'total_berolahraga' => 'Olahraga',
            'total_makan_sehat' => 'Makan Sehat',
            'total_gemar_belajar' => 'Belajar',
            'total_bermasyarakat' => 'Bermasyarakat',
            'total_tidur_cepat' => 'Tidur Cepat'
          ];
          ?>

          <?php if (empty($rekap)): ?>
            <tr>
              <td colspan="9" class="py-6 text-center text-gray-500 italic">
                Belum ada data kebiasaan untuk bulan ini.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($rekap as $r): ?>
              <?php
                $dataJson = [];
                foreach ($kebiasaanList as $field => $label) {
                  $hari = (int)($r[$field] ?? 0);
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
              <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-medium text-gray-800"><?= e($r['nama_lengkap'] ?? '') ?></td>
                <?php foreach ($kebiasaanList as $field => $label): ?>
                  <td class="px-4 py-3 text-center">
                    <span class="<?= e($dataJson[$field]['warna']) ?>">
                      <?= e($dataJson[$field]['status']) ?>
                    </span>
                  </td>
                <?php endforeach; ?>
                <td class="px-4 py-3 text-center">
                  <button 
                    onclick='bukaDetail("<?= e($r['nama_lengkap']) ?>", <?= json_encode($dataJson) ?>)'
                    class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-xs md:text-sm">
                    Detail
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal Detail -->
  <div id="modal-detail" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl border-t-4 border-blue-600">
      <h2 class="text-lg md:text-xl font-bold mb-4 text-blue-700">Detail Kebiasaan</h2>
      <div id="detail-content" class="text-gray-700 text-sm">Memuat...</div>
      <div class="mt-5 text-right">
        <button 
          onclick="tutupModal()" 
          class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm">
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
        <thead class="bg-blue-50 text-gray-700">
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
