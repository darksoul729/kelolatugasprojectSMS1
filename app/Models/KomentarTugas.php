<?php
class KomentarTugas {
    private $pdo;
    private $table = "komentar_tugas";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function tambah($id_tugas, $id_user, $isi_komentar, $id_balasan_untuk = null) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_tugas, id_user, isi_komentar, id_balasan_untuk) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$id_tugas, $id_user, $isi_komentar, $id_balasan_untuk]);

        return $result 
            ? ['success' => true, 'id_komentar' => $this->pdo->lastInsertId()] 
            : ['success' => false, 'message' => 'Gagal menambah komentar.'];
    }

    public function getByTugas($id_tugas) {
        $stmt = $this->pdo->prepare("
            SELECT k.*, u.nama_lengkap, u.peran
            FROM {$this->table} k
            JOIN users u ON k.id_user = u.id_user
            WHERE k.id_tugas = ?
            ORDER BY k.tanggal_komentar ASC
        ");
        $stmt->execute([$id_tugas]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}