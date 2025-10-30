<?php
// app/Controllers/AdminController.php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Untuk PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AdminController {
    private $userModel;
    private $basePath;

    public function __construct($pdo) {
        // Base path fleksibel lintas OS (Windows, Linux, macOS)
        $this->basePath = dirname(__DIR__, 2);
        $this->userModel = new User($pdo);
    }

    /**
     * 🟢 Dashboard Admin - menampilkan daftar semua user
     */
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] !== 'admin') {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.'
            ];
            header("Location: ?route=auth/login");
            exit;
        }

        $keyword = $_GET['search'] ?? null;

        if ($keyword) {
            $users = $this->userModel->search($keyword);
        } else {
            $users = $this->userModel->all();
        }

        $totalUsers = $this->userModel->countAll();
        $roleCounts = $this->userModel->countByRole();

        $viewPath = $this->basePath . '/resources/views/admin/dashboard_admin.php';
        if (!file_exists($viewPath)) {
            die("❌ View tidak ditemukan: $viewPath");
        }

        include $viewPath;
    }

    /**
     * 🟡 Detail User
     */
    public function show($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] !== 'admin') {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.'
            ];
            header("Location: ?route=auth/login");
            exit;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Pengguna tidak ditemukan.'
            ];
            header("Location: ?route=admin/users");
            exit;
        }

        $viewPath = $this->basePath . '/resources/views/admin/detail_user.php';
        if (!file_exists($viewPath)) {
            die("❌ View tidak ditemukan: $viewPath");
        }

        include $viewPath;
    }

    /**
     * 🟡 Edit User (mengambil data untuk modal)
     */
    public function edit($id_user) {
        $user = $this->userModel->findById($id_user);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Pengguna tidak ditemukan.']);
            exit;
        }
        // Kembalikan data user sebagai JSON agar bisa di-fill modal JS
        echo json_encode(['success' => true, 'data' => $user]);
        exit;
    }

    /**
     * 🟢 Update user (submit dari modal)
     */
    public function update($id_user) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Metode request tidak valid.'
            ];
            header("Location: ?route=admin/users");
            exit;
        }

        $postData = [
            'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
            'email' => $_POST['email'] ?? '',
            'peran' => $_POST['peran'] ?? 'siswa',
            'kelas' => $_POST['kelas'] ?? null,
            'nip_nis' => $_POST['nip_nis'] ?? null,
            'status_aktif' => $_POST['status_aktif'] ?? 'aktif',
            'wali_kelas' => $_POST['wali_kelas'] ?? null
        ];

        // Jika password diisi, tambahkan ke data
        if (!empty($_POST['password'])) {
            $postData['password'] = $_POST['password'];
        }

        $result = $this->userModel->editUser($id_user, $postData);
        $_SESSION['message'] = [
            'type' => $result['success'] ? 'success' : 'danger',
            'text' => $result['message']
        ];
        header("Location: ?route=admin/users");
        exit;
    }

    /**
     * 🔴 Delete User
     */
    public function delete($id_user) {
        $result = $this->userModel->deleteUser($id_user);
        $_SESSION['message'] = [
            'type' => $result['success'] ? 'success' : 'danger',
            'text' => $result['message']
        ];
        header("Location: ?route=admin/users");
        exit;
    }

    /**
     * ✅ Verify User (Set Role Multiple)
     */
    public function verify() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Metode request tidak valid.'
            ];
            header("Location: ?route=admin/users");
            exit;
        }

        // Ambil data dari POST
        $ids = $_POST['id_user'] ?? [];
        $peran = $_POST['peran'] ?? 'siswa';
        $wali_kelas = ($peran === 'guru' && isset($_POST['wali_kelas'])) ? $_POST['wali_kelas'] : null;

        if (empty($ids)) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Tidak ada user yang dipilih.'
            ];
            header("Location: ?route=admin/users");
            exit;
        }

        $successCount = 0;
        foreach ($ids as $id) {
            $id_user = (int) $id;
            if ($this->userModel->setPeran($id_user, $peran, $wali_kelas)) {
                $successCount++;
            }
        }

        $_SESSION['message'] = [
            'type' => $successCount > 0 ? 'success' : 'danger',
            'text' => $successCount > 0 
                ? "Berhasil memverifikasi {$successCount} user menjadi {$peran}" . ($peran === 'guru' ? " dengan Wali Kelas {$wali_kelas}" : "")
                : "Gagal memverifikasi user."
        ];

        header("Location: ?route=admin/users");
        exit;
    }

    /**
     * 🔄 Set Role Single User
     */
    public function setrole() {
        if (isset($_GET['id']) && isset($_GET['role'])) {
            $id = (int) $_GET['id'];
            $role = $_GET['role'] === 'guru' ? 'guru' : 'siswa';

            // ambil koneksi dari userModel
            $pdo = $this->userModel->getConnection();
            $stmt = $pdo->prepare("UPDATE users SET peran = ? WHERE id_user = ?");
            $stmt->execute([$role, $id]);

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => "Peran pengguna berhasil diubah menjadi {$role}."
            ];
        }

        header('Location: ?route=admin/users');
        exit;
    }

    /**
     * 📥 Import Siswa dari Excel
     */
    public function importSiswa()
{
    header('Content-Type: application/json');

    try {
        // Pastikan request POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Metode tidak diizinkan.'
            ]);
            return;
        }

        // Validasi file upload
        if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'File tidak ditemukan atau gagal diunggah.'
            ]);
            return;
        }

        // Pastikan ekstensi file benar (xls/xlsx)
        $allowedExtensions = ['xls', 'xlsx'];
        $fileName = $_FILES['excel_file']['name'];
        $fileTmp = $_FILES['excel_file']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExtensions)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Format file tidak valid. Hanya mendukung .xls atau .xlsx.'
            ]);
            return;
        }

        // Tentukan lokasi penyimpanan sementara
        $uploadDir = __DIR__ . '/../../storage/tmp/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . uniqid('import_') . '.' . $fileExt;

        // Pindahkan file ke folder sementara
        if (!move_uploaded_file($fileTmp, $filePath)) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memindahkan file upload.'
            ]);
            return;
        }

        // Proses import lewat model User
        $result = $this->userModel->importSiswaFromExcel($filePath);

        // Hapus file setelah diproses
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // ✅ Jika sukses
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'imported' => $result['imported'],
                    'skipped' => $result['skipped'],
                    'errors' => $result['errors'],
                    'results' => $result['results']
                ]
            ]);
        } 
        // ❌ Jika gagal
        else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $result['message'],
                'data' => [
                    'imported' => 0,
                    'skipped' => 0,
                    'errors' => $result['errors']
                ]
            ]);
        }

    } catch (Exception $e) {
        // Hapus file jika ada error fatal
        if (isset($filePath) && file_exists($filePath)) {
            unlink($filePath);
        }

        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ]);
    }
}


    /**
     * 📋 Download Template Excel
     */
    public function downloadTemplate() {
        // Header untuk file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_import_siswa.xlsx"');
        header('Cache-Control: max-age=0');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NAMA LENGKAP');
        $sheet->setCellValue('C1', 'KELAS');
        $sheet->setCellValue('D1', 'EMAIL AKTIF');

        // Set contoh data
        $sheet->setCellValue('A2', '1');
        $sheet->setCellValue('B2', 'Ade Raffa');
        $sheet->setCellValue('C2', 'KELAS 7A');
        $sheet->setCellValue('D2', 'raffaade0311@gmail.com');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6E6FA']
            ]
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        // Auto size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}

