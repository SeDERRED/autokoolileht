<?php
// admin/index.php - Точка входа в панель администратора

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/model/AdminModel.php';
require_once __DIR__ . '/controller/AdminController.php';

$controller = new AdminController();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add_course':
        $controller->addCourse();
        break;
    case 'delete_course':
        $controller->deleteCourse();
        break;
    case 'update_status':
        $controller->updateStatus();
        break;
    default:
        $controller->dashboard();
        break;
}
