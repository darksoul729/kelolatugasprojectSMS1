<?php
class PengumpulanTugas {
    private $pdo;
    private $table = "pengumpulan_tugas";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function submit($id_tugas, $id_siswa, $isi_jawaban = null, $lampiran_siswa = null, $catatan_siswa = null) {
        // Cek apakah sudah pernah mengumpulkan
        $stmt = $this->pdo->prepare("SELECT id_pengumpulan FROM {$this->table} WHERE id_tugas = ? AND id_siswa = ?");
        $stmt->execute([$id_tugas, $id_siswa]);
        $existing = $stmt->fetch();

        $status = 'terkirim';
        $now = new DateTime();
        $deadline = $this->getDeadlineTugas($id_tugas);
        if ($deadline && $now > new DateTime($deadline)) {
            $status = 'terlambat';
        }

        if ($existing) {
            // Perbarui
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET isi_jawaban = ?, lampiran_siswa = ?, catatan_siswa = ?, status_pengumpulan = 'diperbarui', tanggal_kirim = NOW() WHERE id_pengumpulan = ?");
            $result = $stmt->execute([$isi_jawaban, $lampiran_siswa, $catatan_siswa, $existing['id_pengumpulan']]);
        } else {
            // Buat baru
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_tugas, id_siswa, isi_jawaban, lampiran_siswa, catatan_siswa, status_pengumpulan) VALUES (?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$id_tugas, $id_siswa, $isi_jawaban, $lampiran_siswa, $catatan_siswa, $status]);
        }

        return $result 
            ? ['success' => true, 'message' => 'Tugas berhasil dikumpulkan.'] 
            : ['success' => false, 'message' => 'Gagal mengumpulkan tugas.'];
    }

    private function getDeadlineTugas($id_tugas) {
        $stmt = $this->pdo->prepare("SELECT tanggal_deadline FROM tugas WHERE id_tugas = ?");
        $stmt->execute([$id_tugas]);
        $row = $stmt->fetch();
        return $row ? $row['tanggal_deadline'] : null;
    }

    public function findBySiswaAndTugas($id_siswa, $id_tugas) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id_siswa = ? AND id_tugas = ?");
        $stmt->execute([$id_siswa, $id_tugas]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function allByTugas($id_tugas) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, u.nama_lengkap, u.kelas 
            FROM {$this->table} p
            JOIN users u ON p.id_siswa = u.id_user
            WHERE p.id_tugas = ?
        ");
        $stmt->execute([$id_tugas]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}