<?php
require_once __DIR__ . '/../Models/AnakKebiasaan.php';

class AnakKebiasaanController {
    private $model;
    private $basePath;

    public function __construct($pdo) {
        $this->model = new AnakKebiasaan($pdo);
        $this->basePath = dirname(__DIR__, 2); // ke root proyek
    }

    /** 
     * Tampilkan form kebiasaan di: resources/views/murid/kebiasaan/form.php 
     */
    public function createForm() {
        $viewPath = include $this->basePath . '/resources/views/murid/kebiasaan/create.php';

    }

    /**
     * Simpan data ke database
     */
 public function store() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Ambil id_user dari session (pastikan user sudah login)
        $id_user = $_SESSION['user']['id_user'] ?? null;
        $nama_lengkap = $_SESSION['user']['nama_lengkap'] ?? '';
        $kelas = $_SESSION['user']['kelas'] ?? '';


        if (!$id_user) {
            $_SESSION['message'] = "Gagal: user belum login.";
            header('Location: /?route=login');
            exit;
        }

        

        $data = [
            'id_user' => $id_user,
         
            'bangun_pagi' => isset($_POST['bangun_pagi']) ? 1 : 0,
            'jam_bangun' => $_POST['jam_bangun'] ?? null,

            'beribadah' => isset($_POST['beribadah']) ? 1 : 0,
            'agama' => $_POST['agama'] ?? null,
            'sholat_subuh' => isset($_POST['sholat_subuh']) ? 1 : 0,
            'sholat_dzuhur' => isset($_POST['sholat_dzuhur']) ? 1 : 0,
            'sholat_ashar' => isset($_POST['sholat_ashar']) ? 1 : 0,
            'sholat_maghrib' => isset($_POST['sholat_maghrib']) ? 1 : 0,
            'sholat_isya' => isset($_POST['sholat_isya']) ? 1 : 0,
            'ibadah_lainnya' => $_POST['ibadah_lainnya'] ?? null,

            'berolahraga' => isset($_POST['berolahraga']) ? 1 : 0,
            'jam_olahraga_mulai' => $_POST['jam_olahraga_mulai'] ?? null,
            'jam_olahraga_selesai' => $_POST['jam_olahraga_selesai'] ?? null,
            'foto_olahraga' => $this->uploadFile('foto_olahraga', 'olahraga'),

            'makan_sehat' => isset($_POST['makan_sehat']) ? 1 : 0,
            'makan_pagi' => $_POST['makan_pagi'] ?? null,
            'foto_makan_pagi' => $this->uploadFile('foto_makan_pagi', 'makan'),
            'makan_siang' => $_POST['makan_siang'] ?? null,
            'foto_makan_siang' => $this->uploadFile('foto_makan_siang', 'makan'),
            'makan_malam' => $_POST['makan_malam'] ?? null,
            'foto_makan_malam' => $this->uploadFile('foto_makan_malam', 'makan'),

            'gemar_belajar' => isset($_POST['gemar_belajar']) ? 1 : 0,
            'jam_belajar_mulai' => $_POST['jam_belajar_mulai'] ?? null,
            'jam_belajar_selesai' => $_POST['jam_belajar_selesai'] ?? null,
            'materi_belajar' => $_POST['materi_belajar'] ?? null,

            'bermasyarakat' => isset($_POST['bermasyarakat']) ? 1 : 0,
            'kegiatan_masyarakat' => $_POST['kegiatan_masyarakat'] ?? null,
            'ket_masyarakat' => $_POST['ket_masyarakat'] ?? null,
            'foto_masyarakat' => $this->uploadFile('foto_masyarakat', 'masyarakat'),

            'tidur_cepat' => isset($_POST['tidur_cepat']) ? 1 : 0,
            'jam_tidur' => $_POST['jam_tidur'] ?? null,
            'ket_tidur' => $_POST['ket_tidur'] ?? null,
            'nama_lengkap' => $nama_lengkap,
            'kelas' => $kelas
        ];

        $this->model->insert($data);
$_SESSION['message'] = [
    'type' => 'success', // bisa 'success' atau 'error'
    'text' => 'Data kebiasaan berhasil disimpan!'
];
        header('Location: ?route=murid/dashboard');
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
}
?>
