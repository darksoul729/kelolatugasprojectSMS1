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
     * ✅ Tampilkan form input kebiasaan harian (murid)
     */
    public function createForm() {
        $id_user = $_SESSION['user']['id_user'] ?? null;
        if (!$id_user) {
            $_SESSION['message'] = "Gagal: user belum login.";
            header('Location: /?route=login');
            exit;
        }

        date_default_timezone_set('Asia/Jakarta');
        $today = date('Y-m-d');

        // Cek apakah user sudah isi hari ini
        $cek = $this->model->getByUserAndDate($id_user, $today);
        if ($cek) {
            include $this->basePath . '/resources/views/components/anak-kebiasaan-sudah-input.php';
            return;
        }

        include $this->basePath . '/resources/views/murid/kebiasaan/create.php';
    }

    /**
     * ✅ Ambil semua data kebiasaan siswa
     */
    public function getAllBySiswa($id_siswa) {
        $stmt = $this->pdo->prepare("SELECT * FROM anak_kebiasaan WHERE id_siswa = ?");
        $stmt->execute([$id_siswa]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ✅ Ambil semua kebiasaan berdasarkan kelas
     */
    public function getAllByKelas($kelas) {
        return $this->model->getAllByKelas($kelas);
    }

    /**
     * ✅ Rekap bulanan untuk siswa (murid)
     */
    public function rekapBulanan() {
        $id_user = $_SESSION['user']['id_user'] ?? null;
        if (!$id_user) {
            $_SESSION['message'] = "Gagal: user belum login.";
            header('Location: /?route=login');
            exit;
        }

        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        $rekap = $this->model->getRekapBulanan($id_user, $bulan, $tahun);

        include $this->basePath . '/resources/views/murid/kebiasaan/rekap.php';
    }

    /**
     * ✅ Ambil detail kebiasaan per tanggal (AJAX)
     */
    public function getDetailByDate() {
        if (!isset($_GET['tanggal'])) {
            echo json_encode(['error' => 'Tanggal tidak ditemukan']);
            exit;
        }

        $id_user = $_SESSION['user']['id_user'] ?? null;
        if (!$id_user) {
            echo json_encode(['error' => 'User belum login']);
            exit;
        }

        $tanggal = $_GET['tanggal'];
        $detail = $this->model->getDetailByDate($id_user, $tanggal);
        echo json_encode($detail);
    }

    /**
     * ✅ Ringkasan bulanan per kelas (guru)
     */
    public function ringkasanKebiasaanPerBulanGuru() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'guru') {
            http_response_code(403);
            echo json_encode(['error' => 'Akses ditolak']);
            return;
        }

        $kelasGuru = $_SESSION['user']['wali_kelas'] ?? null;
        if (!$kelasGuru) {
            echo json_encode(['error' => 'Guru tidak memiliki kelas wali']);
            return;
        }

        $bulan = date('m');
        $tahun = date('Y');
        $data = $this->model->getMonthlySummaryByClass($kelasGuru, $bulan, $tahun);

        $namaBulan = [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
            '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
        ];

        $bulanIndo = $namaBulan[$bulan] . ' ' . $tahun;
        header('Content-Type: application/json');
        echo json_encode([
            'bulan' => $bulanIndo,
            'kelas' => $kelasGuru,
            'rekap' => $data
        ]);
    }

    public function ringkasanKebiasaanPerBulanSiswa() {
        $id_user = $_SESSION['user']['id_user'] ?? null;
        if (!$id_user) {
            http_response_code(403);
            echo json_encode(['error' => 'User belum login']);
            return;
        }

        $bulan = date('m');
        $tahun = date('Y');
        $data = $this->model->getMonthlyStatsByUser($id_user, $bulan, $tahun);

        $namaBulan = [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
            '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
        ];

        $bulanIndo = $namaBulan[$bulan] . ' ' . $tahun;
        header('Content-Type: application/json');
        echo json_encode([
            'bulan' => $bulanIndo,
            'rekap' => $data
        ]);
    }

    /**
     * ✅ Simpan data kebiasaan (murid)
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

    date_default_timezone_set('Asia/Jakarta');
    $created_at = date('Y-m-d H:i:s');

    // Validasi agama
    $validAgama = ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'];
    $agama = in_array($_POST['agama'] ?? '', $validAgama) ? $_POST['agama'] : null;

    // Fungsi bantu untuk parsing jam
    function parseTime($input, $default = null) {
        if (empty($input)) return $default;
        $time = date_create_from_format('H:i', $input);
        if (!$time) return $default;
        return $time->format('H:i:s');
    }

    $data = [
        'id_user' => $id_user,
        'bangun_pagi' => isset($_POST['bangun_pagi']) ? 1 : 0,
        'jam_bangun' => parseTime($_POST['jam_bangun'] ?? null),
        'beribadah' => isset($_POST['beribadah']) ? 1 : 0,
        'agama' => $agama,
        'sholat_subuh' => isset($_POST['sholat_subuh']) ? 1 : 0,
        'sholat_dzuhur' => isset($_POST['sholat_dzuhur']) ? 1 : 0,
        'sholat_ashar' => isset($_POST['sholat_ashar']) ? 1 : 0,
        'sholat_maghrib' => isset($_POST['sholat_maghrib']) ? 1 : 0,
        'sholat_isya' => isset($_POST['sholat_isya']) ? 1 : 0,
        'ibadah_lainnya' => $_POST['ibadah_lainnya'] ?? null,
        'berolahraga' => isset($_POST['berolahraga']) ? 1 : 0,
        'jam_olahraga_mulai' => parseTime($_POST['jam_olahraga_mulai'] ?? null),
        'jam_olahraga_selesai' => parseTime($_POST['jam_olahraga_selesai'] ?? null),
        'makan_sehat' => isset($_POST['makan_sehat']) ? 1 : 0,
        'makan_pagi' => $_POST['makan_pagi'] ?? null,
        'makan_siang' => $_POST['makan_siang'] ?? null,
        'makan_malam' => $_POST['makan_malam'] ?? null,
        'gemar_belajar' => isset($_POST['gemar_belajar']) ? 1 : 0,
        'jam_belajar_mulai' => parseTime($_POST['jam_belajar_mulai'] ?? null),
        'jam_belajar_selesai' => parseTime($_POST['jam_belajar_selesai'] ?? null),
        'materi_belajar' => $_POST['materi_belajar'] ?? null,
        'bermasyarakat' => isset($_POST['bermasyarakat']) ? 1 : 0,
        'ket_masyarakat' => $_POST['ket_masyarakat'] ?? null,
        'tidur_cepat' => isset($_POST['tidur_cepat']) ? 1 : 0,
        'jam_tidur' => parseTime($_POST['jam_tidur'] ?? null),
        'nama_lengkap' => $nama_lengkap,
        'kelas' => $kelas,
        'created_at' => $created_at
    ];

    try {
        $this->model->insert($data);
        $_SESSION['message'] = ['type'=>'success','text'=>'Data kebiasaan berhasil disimpan!'];
        header('Location: ?route=murid/dashboard');
        exit;
    } catch (PDOException $e) {
        $_SESSION['message'] = ['type'=>'error','text'=>'Gagal menyimpan data. Silakan cek input dan coba lagi.'];
        header('Location: ?route=murid/kebiasaan/create');
        exit;
    }
}

    /**
     * ✅ Upload file berdasarkan kategori
     */
    private function uploadFile($field, $kategori = 'umum') {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = $this->basePath . '/public/uploads/anak_kebiasaan/' . $kategori . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES[$field]['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetFile)) {
            return 'uploads/anak_kebiasaan/' . $kategori . '/' . $fileName;
        }

        return null;
    }

    /**
     * ✅ Ringkasan visual di dashboard guru
     */
    public function listKebiasaanByKelas($kelas) {
        $data = $this->getAllByKelas($kelas);
        $rekap = $this->olahRingkasanBulanan($data);
        include $this->basePath . '/resources/views/components/ringkasan-status-kebiasaan.php';
    }

    

    /**
     * ✅ Olah ringkasan data mentah per bulan per siswa
     */
    private function olahRingkasanBulanan($data) {
        $bulanIndo = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];

        $fields = ['bangun_pagi','beribadah','berolahraga','makan_sehat','gemar_belajar','bermasyarakat','tidur_cepat'];
        $result = [];

        foreach ($data as $item) {
            $nama = $item['nama_lengkap'];
            $bulan = (int) date('n', strtotime($item['created_at']));
            if (!isset($result[$nama][$bulan])) {
                $result[$nama][$bulan] = ['bulan'=>$bulanIndo[$bulan]];
                foreach ($fields as $f) {
                    $result[$nama][$bulan][$f] = ['terbiasa'=>0,'kurang'=>0,'total'=>0];
                }
            }

            foreach ($fields as $f) {
                $status = !empty($item[$f]) ? 'terbiasa' : 'kurang';
                $result[$nama][$bulan][$f]['total']++;
                $result[$nama][$bulan][$f][$status]++;
            }
        }

        return $result;
    }
}
?>
