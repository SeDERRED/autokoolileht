<?php
// controller/UserController.php
require_once __DIR__ . '/../model/ApplicationModel.php';
require_once __DIR__ . '/../model/CourseModel.php';

class UserController {
    public function profile(): void {
        if (!isLoggedIn()) {
            redirect('/login');
        }

        global $pdo;
        $username = $_SESSION['username'] ?? '';
        
        // Получаем все заявки, отправленные текущим пользователем (по имени или email)
        $stmt = $pdo->prepare("
            SELECT a.*, c.title AS course_title, c.price, c.duration
            FROM applications a
            LEFT JOIN courses c ON a.course_id = c.id
            WHERE a.name = ? OR a.email = ?
            ORDER BY a.id DESC
        ");
        $stmt->execute([$username, $username]);
        $myApplications = $stmt->fetchAll();

        renderView('profile', [
            'title' => 'Minu konto',
            'username' => $username,
            'role' => $_SESSION['user_role'] ?? 'user',
            'applications' => $myApplications
        ]);
    }
}
