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
            DAYNAME(created_at) AS hari,
            bangun_pagi,
            beribadah,
            berolahraga,
            makan_sehat,
            gemar_belajar,
            bermasyarakat,
            tidur_cepat
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


 // ✅ Statistik bulanan per siswa (untuk murid sendiri)
    public function getMonthlyStatsByUser($id_user, $bulan, $tahun) {
        $sql = "
            SELECT 
                COUNT(id) AS total_laporan,
                SUM(bangun_pagi) AS total_bangun_pagi,
                SUM(beribadah) AS total_beribadah,
                SUM(berolahraga) AS total_berolahraga,
                SUM(makan_sehat) AS total_makan_sehat,
                SUM(gemar_belajar) AS total_gemar_belajar,
                SUM(bermasyarakat) AS total_bermasyarakat,
                SUM(tidur_cepat) AS total_tidur_cepat
            FROM anak_kebiasaan
            WHERE id_user = :id_user
              AND MONTH(created_at) = :bulan
              AND YEAR(created_at) = :tahun
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_user' => $id_user,
            ':bulan' => $bulan,
            ':tahun' => $tahun
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Detail laporan per siswa dalam 1 bulan (untuk modal detail)
    public function getMonthlyDetailsByUser($id_user, $bulan, $tahun) {
        $sql = "
            SELECT 
                id,
                DATE_FORMAT(created_at, '%d %M %Y %H:%i') AS tanggal_laporan,
                bangun_pagi, beribadah, berolahraga, makan_sehat, 
                gemar_belajar, bermasyarakat, tidur_cepat
            FROM anak_kebiasaan
            WHERE id_user = :id_user
              AND MONTH(created_at) = :bulan
              AND YEAR(created_at) = :tahun
            ORDER BY created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_user' => $id_user,
            ':bulan' => $bulan,
            ':tahun' => $tahun
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Rekap seluruh siswa satu kelas (untuk guru wali kelas)
    public function getMonthlySummaryByClass($kelas, $bulan, $tahun) {
        $sql = "
            SELECT 
                u.id_user,
                u.nama_lengkap,
                u.kelas,
                COUNT(k.id) AS total_laporan,
                SUM(k.bangun_pagi) AS total_bangun_pagi,
                SUM(k.beribadah) AS total_beribadah,
                SUM(k.berolahraga) AS total_berolahraga,
                SUM(k.makan_sehat) AS total_makan_sehat,
                SUM(k.gemar_belajar) AS total_gemar_belajar,
                SUM(k.bermasyarakat) AS total_bermasyarakat,
                SUM(k.tidur_cepat) AS total_tidur_cepat
            FROM anak_kebiasaan k
            JOIN users u ON k.id_user = u.id_user
            WHERE u.kelas = :kelas
              AND MONTH(k.created_at) = :bulan
              AND YEAR(k.created_at) = :tahun
            GROUP BY u.id_user, u.nama_lengkap, u.kelas
            ORDER BY u.nama_lengkap ASC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':kelas' => $kelas,
            ':bulan' => $bulan,
            ':tahun' => $tahun
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>