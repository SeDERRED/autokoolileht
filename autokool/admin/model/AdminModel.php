<?php
// admin/model/AdminModel.php
// Модель панели администратора для работы с базой данных

class AdminModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Получить все заявки студентов
    public function getAllApplications(): array {
        $stmt = $this->db->query("
            SELECT a.*, c.title AS course_title, c.price AS course_price 
            FROM applications a
            LEFT JOIN courses c ON a.course_id = c.id
            ORDER BY a.id DESC
        ");
        return $stmt->fetchAll();
    }

    // Обновить статус заявки
    public function updateApplicationStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE applications SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Удалить заявку
    public function deleteApplication(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM applications WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Получить все курсы
    public function getAllCourses(): array {
        $stmt = $this->db->query("SELECT * FROM courses ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    // Создать новый курс
    public function addCourse(string $title, string $category, string $description, string $duration, float $price): bool {
        $stmt = $this->db->prepare("
            INSERT INTO courses (title, category, description, duration, price) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$title, $category, $description, $duration, $price]);
    }

    // Удалить курс
    public function deleteCourse(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Статистика для панели администратора
    public function getStats(): array {
        $totalCourses = (int)$this->db->query("SELECT COUNT(*) FROM courses")->fetchColumn();
        $totalApps = (int)$this->db->query("SELECT COUNT(*) FROM applications")->fetchColumn();
        $newApps = (int)$this->db->query("SELECT COUNT(*) FROM applications WHERE status = 'new'")->fetchColumn();
        $inProgressApps = (int)$this->db->query("SELECT COUNT(*) FROM applications WHERE status = 'in_progress'")->fetchColumn();
        $approvedApps = (int)$this->db->query("SELECT COUNT(*) FROM applications WHERE status = 'approved'")->fetchColumn();
        $rejectedApps = (int)$this->db->query("SELECT COUNT(*) FROM applications WHERE status = 'rejected'")->fetchColumn();
        
        $totalRevenue = (float)$this->db->query("
            SELECT COALESCE(SUM(c.price), 0) 
            FROM applications a 
            JOIN courses c ON a.course_id = c.id 
            WHERE a.status = 'approved'
        ")->fetchColumn();

        return [
            'total_courses' => $totalCourses,
            'total_applications' => $totalApps,
            'new_applications' => $newApps,
            'in_progress_applications' => $inProgressApps,
            'approved_applications' => $approvedApps,
            'rejected_applications' => $rejectedApps,
            'total_revenue' => $totalRevenue
        ];
    }
}
