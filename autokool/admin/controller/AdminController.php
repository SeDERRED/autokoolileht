<?php
// admin/controller/AdminController.php
// Контроллер панели администратора

require_once __DIR__ . '/../model/AdminModel.php';

class AdminController {
    private AdminModel $adminModel;

    public function __construct() {
        global $pdo;
        $this->adminModel = new AdminModel($pdo);

        // Защита POST-действий от неавторизованного доступа
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$this->isAdmin()) {
            redirect('/login/admin');
        }
    }

    private function isAdmin(): bool {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    private function renderAdminView(string $viewName, array $data = []): void {
        extract($data);
        require_once __DIR__ . '/../view/header.php';
        require_once __DIR__ . '/../view/' . $viewName . '.php';
        require_once __DIR__ . '/../view/footer.php';
    }

    // Главная страница админки
    public function dashboard(): void {
        if (!$this->isAdmin()) {
            $this->renderAdminView('login', [
                'title' => 'Juhtpaneel - Sisselogimine',
                'error' => $_SESSION['login_error'] ?? null
            ]);
            unset($_SESSION['login_error']);
            return;
        }

        $courses = $this->adminModel->getAllCourses();
        $applications = $this->adminModel->getAllApplications();
        $stats = $this->adminModel->getStats();

        $this->renderAdminView('dashboard', [
            'title' => 'Autokooli Juhtpaneel',
            'courses' => $courses,
            'applications' => $applications,
            'stats' => $stats,
            'flash_msg' => $_SESSION['admin_msg'] ?? null
        ]);

        unset($_SESSION['admin_msg']);
    }

    // Добавить новый курс
    public function addCourse(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $price = (float)($_POST['price'] ?? 0);

            if (!empty($title) && !empty($category) && $price > 0) {
                $this->adminModel->addCourse($title, $category, $description, $duration, $price);
                $_SESSION['admin_msg'] = "Kursus '{$title}' on edukalt lisatud!";
            }
        }
        redirect('/login/admin');
    }

    // Удалить курс
    public function deleteCourse(): void {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->adminModel->deleteCourse($id);
            $_SESSION['admin_msg'] = "Kursus on edukalt kustutatud.";
        }
        redirect('/login/admin');
    }

    // Обновить статус заявки студента
    public function updateStatus(): void {
        $id = (int)($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? 'processed');

        if ($id > 0) {
            $this->adminModel->updateApplicationStatus($id, $status);
            $_SESSION['admin_msg'] = "Avalduse olek on edukalt uuendatud.";
        }
        redirect('/login/admin');
    }
}
