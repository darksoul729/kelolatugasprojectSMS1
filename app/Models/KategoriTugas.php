<?php
class KategoriTugas {
    private $pdo;
    private $table = "kategori_tugas";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY nama_kategori");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_kategori = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByNama($nama) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE nama_kategori = ?");
        $stmt->execute([$nama]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}