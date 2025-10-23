<?php
require_once __DIR__ . '/../Models/AnakKebiasaan.php';

class AnakKebiasaanController {
    private $model;
    private $basePath;
    private $pdo;

    public function __construct($pdo) {
        $this->model = new AnakKebiasaan($pdo);
        $this->pdo = $pdo;
        $this->basePath = dirname(__DIR__, 2); // ke root proyek
    }

    /** 
     * Tampilkan form kebiasaan di: resources/views/murid/kebiasaan/form.php 
     */
   public function createForm() {
    // Pastikan user login
    $id_user = $_SESSION['user']['id_user'] ?? null;
    if (!$id_user) {
        $_SESSION['message'] = "Gagal: user belum login.";
        header('Location: /?route=login');
        exit;
    }
    

    // Set timezone WITA
    date_default_timezone_set('Asia/Jakarta');
    $today = date('Y-m-d');

    // Cek apakah user sudah input hari ini
    $cek = $this->model->getByUserAndDate($id_user, $today); // buat method di model
    if ($cek) {
        // Sudah input → tampilkan komponen sudah input
        include $this->basePath . '/resources/views/components/anak-kebiasaan-sudah-input.php';
        return; // hentikan eksekusi
    }

    // Belum submit → tampilkan form input
    include $this->basePath . '/resources/views/murid/kebiasaan/create.php';
}

// Ambil semua data kebiasaan siswa
    public function getAllBySiswa($id_siswa) {
        $stmt = $this->pdo->prepare("SELECT * FROM anak_kebiasaan WHERE id_siswa = ?");
        $stmt->execute([$id_siswa]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


 // Ambil semua kebiasaan anak berdasarkan kelas
    public function getAllByKelas($kelas) {
        
        return $this->model->getAllByKelas($kelas);
    }


    public function rekapBulanan() {
    // Pastikan user login
    $id_user = $_SESSION['user']['id_user'] ?? null;
    if (!$id_user) {
        $_SESSION['message'] = "Gagal: user belum login.";
        header('Location: /?route=login');
        exit;
    }

    

    // Ambil bulan dan tahun dari query string, default bulan & tahun saat ini
    $bulan = $_GET['bulan'] ?? date('m');
    $tahun = $_GET['tahun'] ?? date('Y');

    // Ambil data dari model
    $rekap = $this->model->getRekapBulanan($id_user, $bulan, $tahun);

    // Load view
    include $this->basePath . '/resources/views/murid/kebiasaan/rekap.php';
}


public function getDetailByDate() {
    if (!isset($_GET['tanggal'])) {
        echo json_encode(['error' => 'Tanggal tidak ditemukan']);
        exit;
    }

    $id_user = $_SESSION['user']['id_user'] ?? null;
    $tanggal = $_GET['tanggal'];

    if (!$id_user) {
        echo json_encode(['error' => 'User belum login']);
        exit;
    }

    $detail = $this->model->getDetailByDate($id_user, $tanggal);
    echo json_encode($detail);
}



    /**
     * Simpan data ke database
     */
 public function store() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $id_user = $_SESSION['user']['id_user'] ?? null;
    $nama_lengkap = $_SESSION['user']['nama_lengkap'] ?? '';
    $kelas = $_SESSION['user']['kelas'] ?? '';

    if (!$id_user) {
        $_SESSION['message'] = ['type'=>'error','text'=>'Gagal: user belum login.'];
        header('Location: /?route=login');
        exit;
    }

    date_default_timezone_set('Asia/Jakarta'); // WITA
    $created_at = date('Y-m-d H:i:s');

    // Ambil input, batasi panjang
    $agama = substr($_POST['agama'] ?? '', 0, 50) ?: null;
    $ibadah_lainnya = substr($_POST['ibadah_lainnya'] ?? '', 0, 255) ?: null;
    $materi_belajar = substr($_POST['materi_belajar'] ?? '', 0, 255) ?: null;
    $kegiatan_masyarakat = substr($_POST['kegiatan_masyarakat'] ?? '', 0, 255) ?: null;
    $ket_masyarakat = substr($_POST['ket_masyarakat'] ?? '', 0, 255) ?: null;
    $ket_tidur = substr($_POST['ket_tidur'] ?? '', 0, 255) ?: null;

    // Validasi ENUM agama
    $validAgama = ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'];
    if (!in_array($agama, $validAgama)) $agama = null;

    $data = [
        'id_user'=>$id_user,
        'bangun_pagi'=>isset($_POST['bangun_pagi'])?1:0,
        'jam_bangun'=>$_POST['jam_bangun']??null,
        'beribadah'=>isset($_POST['beribadah'])?1:0,
        'agama'=>$agama,
        'sholat_subuh'=>isset($_POST['sholat_subuh'])?1:0,
        'sholat_dzuhur'=>isset($_POST['sholat_dzuhur'])?1:0,
        'sholat_ashar'=>isset($_POST['sholat_ashar'])?1:0,
        'sholat_maghrib'=>isset($_POST['sholat_maghrib'])?1:0,
        'sholat_isya'=>isset($_POST['sholat_isya'])?1:0,
        'ibadah_lainnya'=>$ibadah_lainnya,
        'berolahraga'=>isset($_POST['berolahraga'])?1:0,
        'jam_olahraga_mulai'=>$_POST['jam_olahraga_mulai']??null,
        'jam_olahraga_selesai'=>$_POST['jam_olahraga_selesai']??null,
        'foto_olahraga'=>$this->uploadFile('foto_olahraga','olahraga'),
        'makan_sehat'=>isset($_POST['makan_sehat'])?1:0,
        'makan_pagi'=>$_POST['makan_pagi']??null,
        'foto_makan_pagi'=>$this->uploadFile('foto_makan_pagi','makan'),
        'makan_siang'=>$_POST['makan_siang']??null,
        'foto_makan_siang'=>$this->uploadFile('foto_makan_siang','makan'),
        'makan_malam'=>$_POST['makan_malam']??null,
        'foto_makan_malam'=>$this->uploadFile('foto_makan_malam','makan'),
        'gemar_belajar'=>isset($_POST['gemar_belajar'])?1:0,
        'jam_belajar_mulai'=>$_POST['jam_belajar_mulai']??null,
        'jam_belajar_selesai'=>$_POST['jam_belajar_selesai']??null,
        'materi_belajar'=>$materi_belajar,
        'bermasyarakat'=>isset($_POST['bermasyarakat'])?1:0,
        'kegiatan_masyarakat'=>$kegiatan_masyarakat,
        'ket_masyarakat'=>$ket_masyarakat,
        'foto_masyarakat'=>$this->uploadFile('foto_masyarakat','masyarakat'),
        'tidur_cepat'=>isset($_POST['tidur_cepat'])?1:0,
        'jam_tidur'=>$_POST['jam_tidur']??null,
        'ket_tidur'=>$ket_tidur,
        'nama_lengkap'=>$nama_lengkap,
        'kelas'=>$kelas,
        'created_at'=>$created_at
    ];

    try {
        $this->model->insert($data);
        $_SESSION['message'] = ['type'=>'success','text'=>'Data kebiasaan berhasil disimpan!'];
        header('Location: ?route=murid/dashboard');
        exit;
    } catch (PDOException $e) {
        // Redirect ke form tanpa menampilkan warning
        $_SESSION['message'] = ['type'=>'error','text'=>'Gagal menyimpan data. Silakan cek input dan coba lagi.'];
        header('Location: ?route=murid/kebiasaan/create');
        exit;
    }
}



    /**
     * Upload file berdasarkan kategori
     */
    private function uploadFile($field, $kategori = 'umum') {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Folder upload (otomatis buat jika belum ada)
        $uploadDir = $this->basePath . '/public/uploads/anak_kebiasaan/' . $kategori . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Nama file unik
        $fileName = time() . '_' . basename($_FILES[$field]['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetFile)) {
            // Path relatif untuk disimpan di database
            return 'uploads/anak_kebiasaan/' . $kategori . '/' . $fileName;
        }

        return null;
    }
public function listKebiasaanByKelas($kelas) {
    $anakkebiasaan = $this->getAllByKelas($kelas);
    $anakkebiasaan = $this->ringkasanKebiasaanPerBulan($anakkebiasaan);

    include $this->basePath . '/resources/views/components/ringkasan-status-kebiasaan.php';
}


  

    // Ringkasan per bulan per siswa
    public function ringkasanKebiasaanPerBulan($data) {
        $bulanIndo = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
            5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
            9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];

        $kebiasaanFields = [
            'bangun_pagi',
            'beribadah',
            'berolahraga',
            'makan_sehat',
            'gemar_belajar',
            'bermasyarakat',
            'tidur_cepat'
        ];

        $result = [];

        foreach ($data as $item) {
            $nama = $item['nama_lengkap'];
            $month = (int) date('n', strtotime($item['created_at']));

            if (!isset($result[$nama])) $result[$nama] = [];
            if (!isset($result[$nama][$month])) {
                $result[$nama][$month] = ['bulan'=>$bulanIndo[$month]];
                foreach ($kebiasaanFields as $field) {
                    $result[$nama][$month][$field] = [
                        'terbiasa'=>1,'cukup'=>0,'kurang'=>0,'total'=>0
                    ];
                }
            }

            foreach ($kebiasaanFields as $field) {
                $nilai = !empty($item[$field]) ? 1 : 0;
                $result[$nama][$month][$field]['total']++;
                $status = $nilai ? 'terbiasa' : 'kurang';
                $result[$nama][$month][$field][$status]++;
            }
        }

        return $result;
    }
}
?>
