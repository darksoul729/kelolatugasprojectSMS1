<?php
class Tugas {
    private $pdo;
    private $table = "tugas";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Buat tugas baru
     */
  public function create($data) {
    $sql = "INSERT INTO {$this->table} 
            (judul_tugas, deskripsi, id_guru, id_kategori, tanggal_mulai, tanggal_deadline, 
             durasi_estimasi, poin_nilai, instruksi_pengumpulan, lampiran_guru, status_tugas, kelas)
            VALUES 
            (:judul_tugas, :deskripsi, :id_guru, :id_kategori, :tanggal_mulai, :tanggal_deadline, 
             :durasi_estimasi, :poin_nilai, :instruksi_pengumpulan, :lampiran_guru, :status_tugas, :kelas)";

    $stmt = $this->pdo->prepare($sql);

    $params = [
        ':judul_tugas'           => $data['judul_tugas'],
        ':deskripsi'             => $data['deskripsi'] ?? null,
        ':id_guru'               => $data['id_guru'],
        ':id_kategori'           => $data['id_kategori'],
        ':tanggal_mulai'         => $data['tanggal_mulai'] ?? date('Y-m-d H:i:s'),
        ':tanggal_deadline'      => $data['tanggal_deadline'],
        ':durasi_estimasi'      => $data['durasi_estimasi'] ?? null,
        ':poin_nilai'            => $data['poin_nilai'] ?? 100,
        ':instruksi_pengumpulan' => $data['instruksi_pengumpulan'] ?? null,
        ':lampiran_guru'         => $data['lampiran_guru'] ?? null,
        ':status_tugas'          => $data['status_tugas'] ?? 'aktif',
        ':kelas'                 => $data['kelas'] ?? null, // <–– tambahan baru
    ];

    if ($stmt->execute($params)) {
        return [
            'success' => true,
            'id_tugas' => $this->pdo->lastInsertId()
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Gagal membuat tugas.'
        ];
    }
}

public function getByKelas($kelas) {
    try {
        $sql = "SELECT * FROM {$this->table} WHERE kelas = :kelas"; // hapus ORDER BY jika tidak ada created_at
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':kelas' => $kelas]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Gagal mengambil data tugas: " . $e->getMessage());
    }
}


    /**
     * Ambil detail tugas berdasarkan ID
     */
    public function findById($id_tugas) {
        $sql = "SELECT t.*, 
                       u.nama_lengkap AS nama_guru, 
                       k.nama_kategori
                FROM {$this->table} t
                JOIN users u ON t.id_guru = u.id_user
                JOIN kategori_tugas k ON t.id_kategori = k.id_kategori
                WHERE t.id_tugas = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tugas]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil semua tugas milik guru tertentu
     */
    public function allByGuru($id_guru) {
        $sql = "SELECT t.*, k.nama_kategori 
                FROM {$this->table} t
                JOIN kategori_tugas k ON t.id_kategori = k.id_kategori
                WHERE t.id_guru = ?
                ORDER BY t.tanggal_deadline DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_guru]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil semua tugas untuk siswa (dengan status pengumpulan)
     */
   public function allBySiswa($id_siswa) {
    // Ambil kelas siswa dulu
    $queryKelas = "SELECT kelas FROM users WHERE id_user = ?";
    $stmtKelas = $this->pdo->prepare($queryKelas);
    $stmtKelas->execute([$id_siswa]);
    $kelasSiswa = $stmtKelas->fetchColumn();

    // Ambil hanya tugas untuk kelas tersebut
    $sql = "SELECT t.*, k.nama_kategori, u.nama_lengkap AS nama_guru,
                   p.id_pengumpulan, p.status_pengumpulan, p.tanggal_kirim
            FROM {$this->table} t
            JOIN kategori_tugas k ON t.id_kategori = k.id_kategori
            JOIN users u ON t.id_guru = u.id_user
            LEFT JOIN pengumpulan_tugas p 
                   ON t.id_tugas = p.id_tugas 
                  AND p.id_siswa = ?
            WHERE t.kelas = ? -- filter berdasarkan kelas siswa
            ORDER BY t.tanggal_deadline ASC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_siswa, $kelasSiswa]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Ubah status tugas (aktif / ditutup / dibatalkan)
     */
    public function updateStatus($id_tugas, $status) {
        $sql = "UPDATE {$this->table} 
                SET status_tugas = ?, tanggal_diperbarui = CURRENT_TIMESTAMP 
                WHERE id_tugas = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $id_tugas]);
    }

    /**
     * Hapus tugas
     */
    public function delete($id_tugas) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_tugas = ?");
        return $stmt->execute([$id_tugas]);
    }


    public function update($id, $data)
{
    try {
        $query = "UPDATE tugas SET 
            judul_tugas = :judul_tugas,
            deskripsi = :deskripsi,
            id_kategori = :id_kategori,
            kelas = :kelas,
            tanggal_mulai = :tanggal_mulai,
            tanggal_deadline = :tanggal_deadline,
            durasi_estimasi = :durasi_estimasi,
            poin_nilai = :poin_nilai,
            instruksi_pengumpulan = :instruksi_pengumpulan,
            lampiran_guru = :lampiran_guru,
            status_tugas = :status_tugas
        WHERE id_tugas = :id_tugas";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':judul_tugas'           => $data['judul_tugas'],
            ':deskripsi'             => $data['deskripsi'] ?? null,
            ':id_kategori'           => $data['id_kategori'],
            ':kelas'                 => $data['kelas'] ?? null,
            ':tanggal_mulai'         => $data['tanggal_mulai'],
            ':tanggal_deadline'      => $data['tanggal_deadline'],
            ':durasi_estimasi'       => $data['durasi_estimasi'] ?? null,
            ':poin_nilai'            => $data['poin_nilai'] ?? 100,
            ':instruksi_pengumpulan' => $data['instruksi_pengumpulan'] ?? null,
            ':lampiran_guru'         => $data['lampiran_guru'] ?? null,
            ':status_tugas'          => $data['status_tugas'] ?? 'aktif',
            ':id_tugas'              => $id
        ]);

        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

}
