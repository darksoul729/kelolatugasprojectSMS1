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

    public function findById($project_id, $user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE project_id = ? AND user_id = ?");
        $stmt->execute([$project_id, $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($user_id, $judul, $deskripsi, $deadline) {
        $stmt = $this->pdo->prepare("INSERT INTO projects (user_id, judul, deskripsi, deadline) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $judul, $deskripsi, $deadline]);
    }
}
