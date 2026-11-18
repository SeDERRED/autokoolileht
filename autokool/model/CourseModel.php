<?php
// model/CourseModel.php

class CourseModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM courses ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(string $title, string $category, string $description, string $duration, float $price): bool {
        $stmt = $this->db->prepare("
            INSERT INTO courses (title, category, description, duration, price) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$title, $category, $description, $duration, $price]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
