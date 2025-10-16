<?php
class Tugas {
    private $pdo;
    private $table = "tugas";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($judul_tugas, $deskripsi, $id_guru, $id_kategori, $tanggal_deadline, $poin_nilai = 100, $instruksi_pengumpulan = null, $lampiran_guru = null) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (judul_tugas, deskripsi, id_guru, id_kategori, tanggal_deadline, poin_nilai, instruksi_pengumpulan, lampiran_guru) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$judul_tugas, $deskripsi, $id_guru, $id_kategori, $tanggal_deadline, $poin_nilai, $instruksi_pengumpulan, $lampiran_guru]);

        return $result 
            ? ['success' => true, 'id_tugas' => $this->pdo->lastInsertId()] 
            : ['success' => false, 'message' => 'Gagal membuat tugas.'];
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT t.*, u.nama_lengkap AS nama_guru, k.nama_kategori 
                                     FROM {$this->table} t
                                     JOIN users u ON t.id_guru = u.id_user
                                     JOIN kategori_tugas k ON t.id_kategori = k.id_kategori
                                     WHERE t.id_tugas = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function allByGuru($id_guru) {
        $stmt = $this->pdo->prepare("SELECT t.*, k.nama_kategori 
                                     FROM {$this->table} t
                                     JOIN kategori_tugas k ON t.id_kategori = k.id_kategori
                                     WHERE t.id_guru = ? ORDER BY t.tanggal_deadline DESC");
        $stmt->execute([$id_guru]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allBySiswa($id_siswa) {
        // Ambil semua tugas + status pengumpulan siswa
        $stmt = $this->pdo->prepare("
            SELECT t.*, k.nama_kategori, u.nama_lengkap AS nama_guru,
                   p.id_pengumpulan, p.status_pengumpulan, p.tanggal_kirim
            FROM tugas t
            JOIN kategori_tugas k ON t.id_kategori = k.id_kategori
            JOIN users u ON t.id_guru = u.id_user
            LEFT JOIN pengumpulan_tugas p ON t.id_tugas = p.id_tugas AND p.id_siswa = ?
            WHERE t.status_tugas = 'aktif'
            ORDER BY t.tanggal_deadline ASC
        ");
        $stmt->execute([$id_siswa]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id_tugas, $status) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET status_tugas = ? WHERE id_tugas = ?");
        return $stmt->execute([$status, $id_tugas]);
    }
}