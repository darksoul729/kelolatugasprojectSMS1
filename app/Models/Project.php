<?php
class Project {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function allByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function findById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE project_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    public function create($user_id, $judul, $deskripsi, $tipe_tugas,$deadline) {
        $stmt = $this->pdo->prepare("INSERT INTO projects (user_id, judul, deskripsi, tipe_tugas, deadline) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $judul, $deskripsi, $tipe_tugas, $deadline]);
    }


    // Project.php
public function delete($project_id, $user_id) {
    $stmt = $this->pdo->prepare("DELETE FROM projects WHERE project_id = ? AND user_id = ?");
    $stmt->execute([$project_id, $user_id]);
}

}
