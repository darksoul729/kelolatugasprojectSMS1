<?php
class ProgressUpdate {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function deleteByProject($project_id) {
    $stmt = $this->pdo->prepare("DELETE FROM progress_updates WHERE project_id = ?");
    $stmt->execute([$project_id]);
}

    public function allByProject($project_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM progress_updates WHERE project_id = ? ORDER BY tanggal_update DESC");
        $stmt->execute([$project_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($project_id, $keterangan, $foto_dokumentasi) {
        $stmt = $this->pdo->prepare("INSERT INTO progress_updates (project_id, tanggal_update, keterangan, foto_dokumentasi) VALUES (?, NOW(), ?, ?)");
        return $stmt->execute([$project_id, $keterangan, $foto_dokumentasi]);
    }

    public function update($update_id, $keterangan, $foto_dokumentasi = null) {
        if ($foto_dokumentasi) {
            $stmt = $this->pdo->prepare("UPDATE progress_updates SET keterangan = ?, foto_dokumentasi = ? WHERE update_id = ?");
            return $stmt->execute([$keterangan, $foto_dokumentasi, $update_id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE progress_updates SET keterangan = ? WHERE update_id = ?");
            return $stmt->execute([$keterangan, $update_id]);
        }
    }

    public function delete($update_id, $project_id) {
        $stmt = $this->pdo->prepare("DELETE FROM progress_updates WHERE update_id = ? AND project_id = ?");
        return $stmt->execute([$update_id, $project_id]);
    }
}
