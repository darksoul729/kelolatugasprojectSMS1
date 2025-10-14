<?php
class Todo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

   public function allByProject($project_id) {
    $stmt = $this->pdo->prepare("SELECT * FROM todo_list WHERE project_id = ?");
    $stmt->execute([$project_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function create($project_id, $deskripsi, $is_done) {
        $stmt = $this->pdo->prepare("INSERT INTO todo_list (deskripsi, project_id, is_done) VALUES (?, ?, ?)");
        return $stmt->execute([$deskripsi, $project_id, $is_done]);
    }

    public function update($todo_id, $project_id, $deskripsi, $is_done) {
        $stmt = $this->pdo->prepare("UPDATE todo_list SET deskripsi = ?, is_done = ? WHERE todo_id = ? AND project_id = ?");
        return $stmt->execute([$deskripsi, $is_done, $todo_id, $project_id]);
    }

    public function delete($todo_id, $project_id) {
        $stmt = $this->pdo->prepare("DELETE FROM todo_list WHERE todo_id = ? AND project_id = ?");
        return $stmt->execute([$todo_id, $project_id]);
    }

    public function deleteByProject($project_id) {
    $stmt = $this->pdo->prepare("DELETE FROM todo_list WHERE project_id = ?");
    $stmt->execute([$project_id]);
}
}
