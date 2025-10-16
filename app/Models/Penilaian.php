<?php
class Penilaian {
    private $pdo;
    private $table = "penilaian";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function nilai($id_pengumpulan, $id_guru, $nilai, $komentar_guru = null) {
        // Cek apakah sudah dinilai
        $stmt = $this->pdo->prepare("SELECT id_penilaian FROM {$this->table} WHERE id_pengumpulan = ?");
        $stmt->execute([$id_pengumpulan]);
        $exists = $stmt->fetch();

        if ($exists) {
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET nilai = ?, komentar_guru = ?, tanggal_penilaian = NOW() WHERE id_pengumpulan = ?");
            $result = $stmt->execute([$nilai, $komentar_guru, $id_pengumpulan]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_pengumpulan, id_guru, nilai, komentar_guru) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$id_pengumpulan, $id_guru, $nilai, $komentar_guru]);
        }

        return $result 
            ? ['success' => true, 'message' => 'Penilaian berhasil disimpan.'] 
            : ['success' => false, 'message' => 'Gagal menyimpan penilaian.'];
    }

    public function findByPengumpulan($id_pengumpulan) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_pengumpulan = ?");
        $stmt->execute([$id_pengumpulan]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getNilaiSiswa($id_siswa) {
        $stmt = $this->pdo->prepare("
            SELECT p.nilai, t.judul_tugas, k.nama_kategori, t.poin_nilai
            FROM penilaian p
            JOIN pengumpulan_tugas pt ON p.id_pengumpulan = pt.id_pengumpulan
            JOIN tugas t ON pt.id_tugas = t.id_tugas
            JOIN kategori_tugas k ON t.id_kategori = k.id_kategori
            WHERE pt.id_siswa = ?
        ");
        $stmt->execute([$id_siswa]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}