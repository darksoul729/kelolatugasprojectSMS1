<?php
class AnakKebiasaan {
    private $pdo;
    private $table = "anak_kebiasaan";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insert($data) {
        try {
            $sql = "INSERT INTO {$this->table}
            (id_user,bangun_pagi,jam_bangun,beribadah,agama,sholat_subuh,sholat_dzuhur,sholat_ashar,sholat_maghrib,sholat_isya,ibadah_lainnya,
            berolahraga,jam_olahraga_mulai,jam_olahraga_selesai,foto_olahraga,
            makan_sehat,makan_pagi,foto_makan_pagi,makan_siang,foto_makan_siang,makan_malam,foto_makan_malam,
            gemar_belajar,jam_belajar_mulai,jam_belajar_selesai,materi_belajar,
            bermasyarakat,kegiatan_masyarakat,ket_masyarakat,foto_masyarakat,
            tidur_cepat,jam_tidur,ket_tidur)
            VALUES
            (:id_user,:bangun_pagi,:jam_bangun,:beribadah,:agama,:sholat_subuh,:sholat_dzuhur,:sholat_ashar,:sholat_maghrib,:sholat_isya,:ibadah_lainnya,
            :berolahraga,:jam_olahraga_mulai,:jam_olahraga_selesai,:foto_olahraga,
            :makan_sehat,:makan_pagi,:foto_makan_pagi,:makan_siang,:foto_makan_siang,:makan_malam,:foto_makan_malam,
            :gemar_belajar,:jam_belajar_mulai,:jam_belajar_selesai,:materi_belajar,
            :bermasyarakat,:kegiatan_masyarakat,:ket_masyarakat,:foto_masyarakat,
            :tidur_cepat,:jam_tidur,:ket_tidur)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        } catch (PDOException $e) {
            die('Gagal Menambahkan Data: ' . $e->getMessage());
        }
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT ak.*, u.nama_lengkap, u.kelas FROM {$this->table} ak JOIN user u ON ak.id_user = u.id_user");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>