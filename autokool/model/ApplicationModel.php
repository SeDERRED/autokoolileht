<?php
// model/ApplicationModel.php

class ApplicationModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT a.*, c.title AS course_title 
            FROM applications a
            LEFT JOIN courses c ON a.course_id = c.id
            ORDER BY a.id DESC
        ");
        return $stmt->fetchAll();
    }

    public function create(int $courseId, string $name, string $email, string $phone): bool {
        $stmt = $this->db->prepare("
            INSERT INTO applications (course_id, name, email, phone) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$courseId, $name, $email, $phone]);
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE applications SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
