<?php
// controller/HomeController.php
require_once __DIR__ . '/../model/CourseModel.php';

class HomeController {
    public function index(): void {
        global $pdo;
        $courseModel = new CourseModel($pdo);
        $courses = $courseModel->getAll();

        renderView('home', [
            'title' => 'Autokool - Pealeht',
            'courses' => array_slice($courses, 0, 3)
        ]);
    }
}
