<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
 <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background-color: #f1f5f9;
      margin: 0;
      padding: 0;
    }

    /* Header */
    .navbar {
      background-color: #1e90ff;
      color: white;
      padding: 12px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar h1 {
      font-size: 18px;
      margin: 0;
    }

    .navbar button {
      background-color: #dc3545;
      border: none;
      color: white;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }

    .navbar button:hover {
      background-color: #b02a37;
    }

    /* Container */
    .container {
      background-color: white;
      max-width: 800px;
      margin: 30px auto;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 20px 30px;
    }

    .container h2 {
      font-size: 18px;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-progress {
      background-color: #2dd4bf;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
    }

    .btn-progress:hover {
      background-color: #14b8a6;
    }

    .form-section {
      margin-top: 20px;
    }

    .form-section label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }

    .form-section input[type="date"],
    .form-section input[type="time"],
    .form-section input[type="text"],
    .form-section textarea {
      width: 100%;
      padding: 8px;
      border-radius: 6px;
      border: 1px solid #d1d5db;
      font-size: 14px;
    }

    .accordion {
      background-color: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 6px;
      margin-top: 10px;
      overflow: hidden;
    }

    .accordion-header {
      padding: 12px 15px;
      font-weight: 600;
      cursor: pointer;
      background-color: #f3f4f6;
      border-bottom: 1px solid #e5e7eb;
    }

    .accordion-content {
      display: none;
      padding: 15px;
      background-color: white;
    }

    .accordion-content input[type="checkbox"] {
      margin-bottom: 10px;
    }

    .accordion.active .accordion-content {
      display: block;
    }

    .note {
      font-size: 13px;
      color: #6b7280;
    }
  </style>
<body>
      <div class="navbar">
    <h1>Dashboard Siswa</h1>
    <button>Logout</button>
  </div>

  <div class="container">
    <button class="btn-progress">📊 Lihat Grafik Progres Bulan Ini</button>

    <div class="form-section">
      <h2>📝 Buat atau Perbarui Laporan Harian</h2>

      <label for="tanggal">Pilih Tanggal Laporan:</label>
      <input type="date" id="tanggal">
    </div>

    <div class="accordion">
      <div class="accordion-header">1. Kebiasaan Bangun Pagi</div>
      <div class="accordion-content">
        <label><input type="checkbox"> Saya melakukan kebiasaan ini</label>
        <label>Pukul Berapa Bangun?</label>
        <input type="time" placeholder="--:--">
        <label>Keterangan</label>
        <input type="text" placeholder="Contoh: Langsung merapikan tempat tidur">
      </div>
    </div>

    <div class="accordion">
      <div class="accordion-header">2. Kebiasaan Beribadah</div>
      <div class="accordion-content">
        <label><input type="checkbox"> Saya melakukan kebiasaan ini</label>
        <label>Jenis Ibadah</label>
        <input type="text" placeholder="Contoh: Shalat Subuh, Dzikir pagi">
        <label>Keterangan</label>
        <textarea rows="2" placeholder="Tambahkan catatan kegiatan ibadah"></textarea>
      </div>
    </div>

    <div class="accordion">
      <div class="accordion-header">3. Kebiasaan Berolahraga</div>
      <div class="accordion-content">
        <label><input type="checkbox"> Saya melakukan kebiasaan ini</label>
        <label>Jenis Olahraga</label>
        <input type="text" placeholder="Contoh: Lari pagi, push up">
        <label>Durasi</label>
        <input type="text" placeholder="Contoh: 30 menit">
      </div>
    </div>

    <div class="accordion">
      <div class="accordion-header">4. Kebiasaan Makan Sehat</div>
      <div class="accordion-content">
        <label><input type="checkbox"> Saya melakukan kebiasaan ini</label>
        <label>Menu Makanan</label>
        <input type="text" placeholder="Contoh: Sayur, buah, susu, air putih">
        <label>Keterangan</label>
        <textarea rows="2" placeholder="Tuliskan kebiasaan makan sehat hari ini"></textarea>
      </div>
    </div>

    <div class="accordion">
      <div class="accordion-header">5. Kebiasaan Gemar Belajar</div>
      <div class="accordion-content">
        <label><input type="checkbox"> Saya melakukan kebiasaan ini</label>
        <label>Materi Belajar</label>
        <input type="text" placeholder="Contoh: Matematika, Bahasa Inggris">
        <label>Durasi</label>
        <input type="text" placeholder="Contoh: 1 jam">
      </div>
    </div>

    <p class="note" style="margin-top: 20px;">Isi laporan harian dengan jujur sesuai aktivitas yang kamu lakukan.</p>
  </div>

  <script>
    // Script untuk fungsi accordion
    const accordions = document.querySelectorAll('.accordion-header');
    accordions.forEach(header => {
      header.addEventListener('click', () => {
        const parent = header.parentElement;
        parent.classList.toggle('active');
      });
    });
  </script>
</body>
</html>