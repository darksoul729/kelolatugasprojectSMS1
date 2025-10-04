<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Kelola Tugas — Landing</title>
  <link rel="stylesheet" href="assets/css/index.css">

</head>
<body>

  <!-- Navbar -->
  <header class="navbar">
    <div class="logo">Kelola Tugas</div>
    <nav>
      <ul>
        <li><a href="#fitur">Fitur</a></li>
        <li><a href="#kontak">Kontak</a></li>
      </ul>
    </nav>
    <a href="#mulai" class="btn-nav">Mulai Aplikasi</a>
  </header>

  <!-- Hero buat bgian paling dpan -->
  <section class="hero">
    <h1>Fokus Pada Apa Yang Penting</h1>
    <p>Aplikasi Kelola Tugas berbasis CodeIgniter 4: Simpel, Cepat, dan Efektif
      untuk melacak setiap daftar pekerjaan Anda, dari pending hingga done.</p>
    <a href="#coba" class="btn-cta">Coba Sekarang Gratis</a>
  </section>

  <!-- kenapa section -->
  <section id="fitur" class="fitur">
    <h2>Kenapa Memilih Kelola Tugas?</h2>
    <p>Kami menyediakan fitur dasar yang Anda butuhkan tanpa kerumitan yang tidak perlu.</p>

    <div class="fitur-container">
      <div class="card merah">
        <h3>3 Status Tugas Fleksibel</h3>
        <p>Lacak setiap tahapan pekerjaan: <b>Pending</b> (Merah),
        <b>Progress</b> (Biru), dan <b>Done</b> (Hijau).</p>
      </div>

      <div class="card biru">
        <h3>Manajemen CRUD Penuh</h3>
        <p>Buat, Baca, Ubah, dan Hapus tugas. Tambahkan <b>deadline</b> agar tidak melewatkan waktu penting.</p>
      </div>

      <div class="card hijau">
        <h3>Dirancang untuk Pelajar/Pemula</h3>
        <p>Dibangun dengan CodeIgniter 4 sebagai proyek latihan, sehingga mudah dipelajari.</p>
      </div>
    </div>
  </section>


  <!-- <footer id="kontak">
    <p>© 2025 Kelola Tugas</p>
    <h2>Kontak Kami</h2>
        <p>Untuk pertanyaan atau dukungan, silakan hubungi kami di:</p>
        <p>Email:
  </footer> -->    
 <!-- buat hide pas ngescroll -->
  <script>
    let lastScroll = 0;
    const header = document.querySelector(".navbar");

    window.addEventListener("scroll", () => {
      let current = window.scrollY;
      if (current > lastScroll && current > 60) {
        header.style.transform = "translateY(-100%)";
      } else {
        header.style.transform = "translateY(0)";
      }
      lastScroll = current;
    });
  </script>
</body>
</html>
