<?php
// Array bulan & hari Indonesia
$bulanIndo = [
    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember',
];
$hariIndo = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
];

$namaBulanInggris = date('F', mktime(0, 0, 0, $bulan, 10));
$namaBulan = $bulanIndo[$namaBulanInggris] ?? $namaBulanInggris;
$jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

$semuaTanggal = [];
for ($i = 1; $i <= $jumlahHari; $i++) {
    $tanggalLengkap = sprintf('%04d-%02d-%02d', $tahun, $bulan, $i);
    $hariInggris = date('l', strtotime($tanggalLengkap));
    $hariIndonesia = $hariIndo[$hariInggris] ?? $hariInggris;
    $semuaTanggal[$tanggalLengkap] = [
        'tanggal' => $tanggalLengkap,
        'hari' => $hariIndonesia,
        'ada_data' => false,
        'data_lengkap' => null,
    ];
}

if (!empty($rekap)) {
    foreach ($rekap as $r) {
        $tgl = $r['tanggal'];
        if (isset($semuaTanggal[$tgl])) {
            $semuaTanggal[$tgl]['ada_data'] = true;
            $semuaTanggal[$tgl]['data_lengkap'] = $r;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="font-sans scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rekap Kebiasaan Bulan <?= $namaBulan ?> <?= $tahun ?></title>
    <!-- ✅ Perbaiki: hapus spasi ekstra di CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="bg-gray-50 min-h-screen">

<div class="container mx-auto px-4 py-6 md:py-8">
    <h2 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mb-5 md:mb-6 text-center">
        Rekap Kebiasaan Bulan <?= $namaBulan ?> <?= $tahun ?>
    </h2>

    <!-- Card Ringkasan Bulanan -->
    <div id="card-ringkasan" class="max-w-2xl mx-auto mb-6 md:mb-8 hidden">
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-blue-600 px-4 py-3 md:px-5 md:py-3">
                <h3 class="text-base md:text-lg font-semibold text-white">
                    Ringkasan Bulan <span id="bulan-ringkasan"></span>
                </h3>
            </div>
            <div class="p-4 md:p-5 space-y-2 md:space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium text-sm md:text-base">Hari dengan data:</span>
                    <span id="hari-ada-data" class="text-gray-900 font-semibold">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium text-sm md:text-base">Rata-rata kebiasaan baik:</span>
                    <span id="rata-persentase" class="text-gray-900 font-semibold">0%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium text-sm md:text-base">Rata-rata kebiasaan “Ya”:</span>
                    <span id="rata-kebiasaan" class="text-gray-900 font-semibold">0 dari 7</span>
                </div>
            </div>
            <div id="kategori-container" class="px-4 pb-4 md:px-5 md:pb-5 hidden">
                <div id="kategori-badge" class="flex items-center justify-center gap-2 py-2 px-3 md:px-4 rounded-lg font-semibold border text-center text-sm md:text-base">
                    <!-- Diisi via JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Responsif -->
    <div class="max-w-full overflow-hidden mb-6 md:mb-8">
        <canvas id="chartKebiasaan" class="w-full max-w-full h-[300px] md:h-[400px] "></canvas>
    </div>

    <!-- Grid Tanggal -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3 md:gap-4">
        <?php foreach ($semuaTanggal as $tanggal => $r): ?>
            <?php
            if ($r['ada_data']) {
                $d = $r['data_lengkap'];
                $kebiasaan = ['bangun_pagi','beribadah','berolahraga','makan_sehat','gemar_belajar','bermasyarakat','tidur_cepat'];
                $total = $terisi = 0;
                foreach ($kebiasaan as $k) {
                    if (isset($d[$k])) {
                        $terisi++;
                        $total += (int)$d[$k];
                    }
                }
                $persentase = $terisi > 0 ? ($total / $terisi) * 100 : 0;

                if ($persentase >= 76) $warnaCard = 'bg-green-100 text-green-800';
                elseif ($persentase >= 51) $warnaCard = 'bg-yellow-100 text-yellow-800';
                elseif ($persentase >= 26) $warnaCard = 'bg-orange-100 text-orange-800';
                else $warnaCard = 'bg-red-100 text-red-800';

                $kursor = 'cursor-pointer hover:shadow-md transition-shadow duration-200';
                $onclick = "onclick=\"bukaModal('$tanggal')\"";
            } else {
                $warnaCard = 'bg-gray-100 text-gray-400';
                $kursor = 'opacity-80';
                $onclick = '';
            }
            ?>
            <div class="<?= $warnaCard ?> <?= $kursor ?> rounded-lg md:rounded-xl shadow-sm p-3 text-center "<?= $onclick ?>>
                <h3 class="text-base md:text-lg font-semibold"><?= date('d', strtotime($r['tanggal'])) ?></h3>
                <p class="text-xs mt-1"><?= ucfirst($r['hari']) ?></p>
                <?php if (!$r['ada_data']): ?>
                    <p class="text-[9px] italic mt-1">Belum ada data</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>



<!-- Floating Action Button -->
<a href="?route=murid/dashboard"
   class="fixed bottom-5 right-5 md:bottom-6 md:right-6 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center z-40"
   title="Kembali ke Dashboard">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.125 1.125 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
    </svg>
</a>

<!-- Modal -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-md max-h-[85vh] overflow-y-auto">
    <div class="p-4 border-b">
        <h3 id="modalTanggal" class="text-base md:text-lg font-semibold text-gray-800"></h3>
    </div>
    <div id="modalContent" class="p-4 space-y-4 text-sm">
        <p class="text-gray-600">Memuat...</p>
    </div>
    <div class="p-4 border-t border-gray-200">
        <button onclick="tutupModal()"
                class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            Tutup
        </button>
    </div>
  </div>
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const semuaTanggal = <?= json_encode($semuaTanggal) ?>;
  const daftarKebiasaan = ["bangun_pagi", "beribadah", "berolahraga", "makan_sehat", "gemar_belajar", "bermasyarakat", "tidur_cepat"];

  // === Hitung data untuk card ringkasan ===
  let hariDenganData = 0;
  let totalPersen = 0;
  let totalKebYa = 0;

  for (const tgl in semuaTanggal) {
    const item = semuaTanggal[tgl];
    if (item.ada_data && item.data_lengkap) {
      hariDenganData++;
      let jumlahYa = 0, terisi = 0;
      daftarKebiasaan.forEach(k => {
        if (item.data_lengkap[k] !== undefined) {
          terisi++;
          if (item.data_lengkap[k] == 1) jumlahYa++;
        }
      });
      totalPersen += (jumlahYa / terisi) * 100;
      totalKebYa += jumlahYa;
    }
  }

  if (hariDenganData > 0) {
    const rataPersen = (totalPersen / hariDenganData).toFixed(1);
    const rataKeb = (totalKebYa / hariDenganData).toFixed(1);

    document.getElementById("bulan-ringkasan").textContent = "<?= $namaBulan . ' ' . $tahun ?>";
    document.getElementById("hari-ada-data").textContent = hariDenganData;
    document.getElementById("rata-persentase").textContent = rataPersen + "%";
    document.getElementById("rata-kebiasaan").textContent = rataKeb;

    // Kategori
    let kategori = "", warna = "", ikon = "";
    if (hariDenganData < 10) {
      kategori = "Kurang"; warna = "text-red-600 bg-red-50 border-red-200"; ikon = `<i class='fa-solid fa-circle-xmark'></i>`;
    } else if (hariDenganData <= 20) {
      kategori = "Cukup"; warna = "text-yellow-600 bg-yellow-50 border-yellow-200"; ikon = `<i class='fa-solid fa-circle-exclamation'></i>`;
    } else {
      kategori = "Sangat Baik"; warna = "text-green-600 bg-green-50 border-green-200"; ikon = `<i class='fa-solid fa-circle-check'></i>`;
    }

    const badge = document.getElementById("kategori-badge");
    badge.innerHTML = `${ikon} Kategori: ${kategori}`;
    badge.className = "flex items-center justify-center gap-2 py-2 px-3 md:px-4 rounded-lg font-semibold border text-center text-sm md:text-base " + warna;
    document.getElementById("kategori-container").classList.remove("hidden");
    document.getElementById("card-ringkasan").classList.remove("hidden");
  }

  // === Chart.js ===
  const hasil = {};
  daftarKebiasaan.forEach(k => hasil[k] = { ya: 0, total: 0 });

  for (const tgl in semuaTanggal) {
    const item = semuaTanggal[tgl];
    if (item.ada_data && item.data_lengkap) {
      daftarKebiasaan.forEach(k => {
        if (item.data_lengkap[k] !== undefined) {
          hasil[k].total++;
          if (item.data_lengkap[k] == 1) hasil[k].ya++;
        }
      });
    }
  }

  const labels = daftarKebiasaan.map(k => k.replace(/_/g, " ").replace(/\b\w/g, l => l.toUpperCase()));
  const dataPersen = daftarKebiasaan.map(k => 
    hasil[k].total > 0 ? (hasil[k].ya / hasil[k].total * 100).toFixed(1) : 0
  );

  const ctx = document.getElementById("chartKebiasaan").getContext("2d");
  new Chart(ctx, {
    type: "bar",
    data: {
      labels,
      datasets: [{
        label: "Persentase Kebiasaan (%)",
        data: dataPersen,
        backgroundColor: [
          'rgba(75, 192, 192, 0.7)',
          'rgba(54, 162, 235, 0.7)',
          'rgba(255, 206, 86, 0.7)',
          'rgba(255, 99, 132, 0.7)',
          'rgba(153, 102, 255, 0.7)',
          'rgba(255, 159, 64, 0.7)',
          'rgba(100, 181, 246, 0.7)'
        ],
        borderRadius: 6,
        borderSkipped: false
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          max: 100,
          title: { display: true, text: "Persentase (%)" }
        },
        x: {
          title: { display: true, text: "Kebiasaan" }
        }
      },
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => `${ctx.parsed.y}%` } }
      }
    }
  });
});

</script>
<script>
function bukaModal(tanggal) {
  const modal = document.getElementById('modal');
  const modalContent = document.getElementById('modalContent');
  const modalTanggal = document.getElementById('modalTanggal');

  modalTanggal.textContent = "Detail Kebiasaan Tanggal " + tanggal;
  modalContent.innerHTML = "<p class='text-gray-600'>Memuat...</p>";
  modal.classList.remove('hidden');

  fetch(`?route=murid/detail_kebiasaan&tanggal=${tanggal}`)
    .then(res => res.json())
    .then(data => {
      if (!data || data.error) {
        modalContent.innerHTML = "<p class='text-gray-500'>Tidak ada data untuk tanggal ini.</p>";
        return;
      }

      // Format data menjadi HTML dengan kelas Tailwind dan Ikon
      // Sesuaikan basePath sesuai dengan konfigurasi web server Anda
      // Jika /public adalah web root, basePath = '/uploads'
      // Jika file diakses via /public/uploads/..., dan /public bukan web root, basePath = '/public/uploads'
      // Berdasarkan error sebelumnya, sepertinya struktur adalah /public/uploads/[folder]/[file]
      // Dan URL untuk mengaksesnya adalah /public/uploads/[folder]/[file]
      // Jadi, basePath = '/public/uploads'
      const basePath = '/public'; // <-- Sesuaikan ini

      let html = `
        <div class="space-y-3">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-700">Bangun Pagi</p>
                    <p class="text-sm text-gray-600">${data.bangun_pagi == 1 ? 'Ya' : 'Tidak'}</p>
                    ${data.jam_bangun ? `<p class="text-xs text-gray-500">Jam: ${data.jam_bangun}</p>` : ''}
                </div>
            </div>
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-700">Beribadah</p>
                    <p class="text-sm text-gray-600">${data.beribadah == 1 ? 'Ya' : 'Tidak'}</p>
                    <p class="text-xs text-gray-500">Agama: ${data.agama || 'Tidak disebutkan'}</p>
                    <!-- Kondisi untuk menampilkan Sholat atau Ibadah Lainnya -->
                    ${data.agama === 'Islam' ?
                        `<div class="ml-1 mt-1 space-y-1">
                            <p class="text-xs text-gray-600 flex items-center"><span class="mr-1">•</span> Subuh: ${data.sholat_subuh == 1 ? 'Ya' : 'Tidak'}</p>
                            <p class="text-xs text-gray-600 flex items-center"><span class="mr-1">•</span> Dzuhur: ${data.sholat_dzuhur == 1 ? 'Ya' : 'Tidak'}</p>
                            <p class="text-xs text-gray-600 flex items-center"><span class="mr-1">•</span> Ashar: ${data.sholat_ashar == 1 ? 'Ya' : 'Tidak'}</p>
                            <p class="text-xs text-gray-600 flex items-center"><span class="mr-1">•</span> Maghrib: ${data.sholat_maghrib == 1 ? 'Ya' : 'Tidak'}</p>
                            <p class="text-xs text-gray-600 flex items-center"><span class="mr-1">•</span> Isya: ${data.sholat_isya == 1 ? 'Ya' : 'Tidak'}</p>
                         </div>`
                        :
                        `<p class="text-xs text-gray-500">Ibadah Lainnya: ${data.ibadah_lainnya || '-'}</p>`
                    }
                </div>
            </div>
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-700">Olahraga</p>
                    <p class="text-sm text-gray-600">${data.berolahraga == 1 ? 'Ya' : 'Tidak'}</p>
                    ${data.jam_olahraga_mulai && data.jam_olahraga_selesai ? `<p class="text-xs text-gray-500">Jam: ${data.jam_olahraga_mulai} - ${data.jam_olahraga_selesai}</p>` : ''}
                    ${data.foto_olahraga ? `<div class="mt-2"><img src="${basePath}/${data.foto_olahraga}" alt="Foto Olahraga" class="w-full h-auto rounded border"></div>` : ''}
                </div>
            </div>
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m.000 0a4.5 4.5 0 0 1 3.42 3.42c.397.396.737.84.972 1.31.187.372.24.776.156 1.15.09-.376.158-.76.2-1.15.133-.86.015-1.738-.351-2.454a4.51 4.51 0 0 0-1.185-1.62M9.53 16.122a15.043 15.043 0 0 1-3.28-3.867m3.28 3.867ZM18 16.122a15.043 15.043 0 0 0 3.28-3.867m-3.895 3.867a15.999 15.999 0 0 1-3.28-3.867m3.895 3.867Z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-700">Makan Sehat</p>
                    <p class="text-sm text-gray-600">${data.makan_sehat == 1 ? 'Ya' : 'Tidak'}</p>
                    <div class="text-xs text-gray-500">
                        ${data.makan_pagi ? `<span>Pagi: ${data.makan_pagi}</span>` : ''}
                        ${data.makan_siang ? `<span> | Siang: ${data.makan_siang}</span>` : ''}
                        ${data.makan_malam ? `<span> | Malam: ${data.makan_malam}</span>` : ''}
                    </div>
                    ${data.foto_makan_pagi ? `<div class="mt-2"><img src="${basePath}/${encodeURIComponent(data.foto_makan_pagi)}" alt="Foto Makan Pagi" class="w-full h-auto rounded border"></div>` : ''}
                    ${data.foto_makan_siang ? `<div class="mt-2"><img src="${basePath}/${encodeURIComponent(data.foto_makan_siang)}" alt="Foto Makan Siang" class="w-full h-auto rounded border"></div>` : ''}
                    ${data.foto_makan_malam ? `<div class="mt-2"><img src="${basePath}/${encodeURIComponent(data.foto_makan_malam)}" alt="Foto Makan Malam" class="w-full h-auto rounded border"></div>` : ''}
                </div>
            </div>
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-700">Gemar Belajar</p>
                    <p class="text-sm text-gray-600">${data.gemar_belajar == 1 ? 'Ya' : 'Tidak'}</p>
                    ${data.materi_belajar ? `<p class="text-xs text-gray-500">Materi: ${data.materi_belajar}</p>` : ''}
                    ${data.jam_belajar_mulai && data.jam_belajar_selesai ? `<p class="text-xs text-gray-500">Jam: ${data.jam_belajar_mulai} - ${data.jam_belajar_selesai}</p>` : ''}
                </div>
            </div>
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-700">Bermasyarakat</p>
                    <p class="text-sm text-gray-600">${data.bermasyarakat == 1 ? 'Ya' : 'Tidak'}</p>
                    ${data.kegiatan_masyarakat ? `<p class="text-xs text-gray-500">Kegiatan: ${data.kegiatan_masyarakat}</p>` : ''}
                    ${data.ket_masyarakat ? `<p class="text-xs text-gray-500">Keterangan: ${data.ket_masyarakat}</p>` : ''}
                    ${data.foto_masyarakat ? `<div class="mt-2"><img src="${basePath}/${encodeURIComponent(data.foto_masyarakat)}" alt="Foto Kegiatan Masyarakat" class="w-full h-auto rounded border"></div>` : ''}
                </div>
            </div>
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9.75c0 1.473-.782 2.25-1.5 2.25h-15a1.5 1.5 0 0 1 0-3h15c.718 0 1.5.778 1.5 2.25ZM9 12a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-3 0v-3A1.5 1.5 0 0 1 9 12Zm7.5-1.5a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-3 0v-3a1.5 1.5 0 0 1 1.5-1.5Z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-700">Tidur Cepat</p>
                    <p class="text-sm text-gray-600">${data.tidur_cepat == 1 ? 'Ya' : 'Tidak'}</p>
                    ${data.jam_tidur ? `<p class="text-xs text-gray-500">Jam: ${data.jam_tidur}</p>` : ''}
                    ${data.ket_tidur ? `<p class="text-xs text-gray-500">Keterangan: ${data.ket_tidur}</p>` : ''}
                </div>
            </div>
        </div>
      `;
      modalContent.innerHTML = html;
    })
    .catch(() => {
      modalContent.innerHTML = "<p class='text-red-500'>Gagal memuat data.</p>";
    });
}

function tutupModal() {
  document.getElementById('modal').classList.add('hidden');
}
</script>
</body>
</html>