<?php
class AnakKebiasaan {
    private $pdo;
    private $table = "anak_kebiasaan";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

   public function getRekapBulanan($id_user, $bulan, $tahun) {
    $sql = "
        SELECT 
            DISTINCT DATE(created_at) AS tanggal,
            DAYNAME(created_at) AS hari
        FROM anak_kebiasaan
        WHERE id_user = :id_user
          AND MONTH(created_at) = :bulan
          AND YEAR(created_at) = :tahun
        ORDER BY DATE(created_at)
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_user' => $id_user,
        ':bulan' => $bulan,
        ':tahun' => $tahun
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getAllByKelas($kelas) {
    $sql = "SELECT * FROM anak_kebiasaan WHERE kelas = :kelas ORDER BY created_at DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':kelas' => $kelas]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getDetailHarian($id_user, $tanggal) {
    $sql = "
        SELECT 
            id,
            created_at,
            olahraga, foto_olahraga,
            makan_sehat,
            makan_pagi, foto_makan_pagi,
            makan_siang, foto_makan_siang,
            makan_malam, foto_makan_malam,
            gemar_belajar, jam_belajar_mulai, jam_belajar_selesai, materi_belajar,
            bermasyarakat, kegiatan_masyarakat, ket_masyarakat, foto_masyarakat
        FROM anak_kebiasaan
        WHERE id_user = :id_user
          AND DATE(created_at) = :tanggal
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_user' => $id_user,
        ':tanggal' => $tanggal
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getDetailByDate($id_user, $tanggal) {
    $sql = "
        SELECT *
        FROM anak_kebiasaan
        WHERE id_user = :id_user
          AND DATE(created_at) = :tanggal
        ORDER BY created_at DESC
        LIMIT 1
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_user' => $id_user,
        ':tanggal' => $tanggal
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



   public function insert($data) {
    try {
        // Ambil semua kolom dari key $data
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    } catch (PDOException $e) {
        die('Gagal Menambahkan Data: ' . $e->getMessage());
    }
}


    public function getAll() {
        try{
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            die('Gagal Menampilkan Semua Data: ' . $e->getMessage());
        }
    }

    public function getByUserAndDate($id_user, $tanggal) {
    $sql = "SELECT * FROM anak_kebiasaan WHERE id_user = :id_user AND DATE(created_at) = :tanggal LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_user' => $id_user,
        ':tanggal' => $tanggal
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
?>