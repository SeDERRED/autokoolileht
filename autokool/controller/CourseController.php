<?php
// controller/CourseController.php
require_once __DIR__ . '/../model/CourseModel.php';
require_once __DIR__ . '/../model/ApplicationModel.php';

class CourseController {
    public function index(): void {
        global $pdo;
        $courseModel = new CourseModel($pdo);
        $courses = $courseModel->getAll();

        renderView('courses', [
            'title' => 'Kursused ja hinnad',
            'courses' => $courses,
            'successMessage' => $_SESSION['flash_success'] ?? null
        ]);

        unset($_SESSION['flash_success']);
    }

    public function apply(): void {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseId = (int)($_POST['course_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            if ($courseId > 0 && !empty($name) && !empty($phone)) {
                $appModel = new ApplicationModel($pdo);
                $appModel->create($courseId, $name, $email, $phone);

                $_SESSION['flash_success'] = "Aitäh registreerumise eest! Võtame teiega peagi ühendust.";
            }
        }
        redirect('/courses');
    }
}
