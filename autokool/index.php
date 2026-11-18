<?php
// index.php - Единая точка входа (Front Controller)

session_start();

// Подключение ядра системы
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/route/routing.php';

$router = new Router();

// Публичные маршруты
$router->get('', 'HomeController@index');
$router->get('courses', 'CourseController@index');
$router->post('apply', 'CourseController@apply');

// Аутентификация и регистрация
$router->get('login', 'AuthController@showLogin');
$router->post('login', 'AuthController@login');
$router->get('register', 'AuthController@showRegister');
$router->post('register', 'AuthController@register');
$router->get('logout', 'AuthController@logout');

// Личный кабинет пользователя (ученика)
$router->get('profile', 'UserController@profile');

// Панель администратора
$router->get('admin', 'AdminController@dashboard');
$router->get('login/admin', 'AdminController@dashboard');
$router->post('login/admin', 'AuthController@login');
$router->post('admin/course/add', 'AdminController@addCourse');
$router->post('admin/course/delete', 'AdminController@deleteCourse');
$router->post('admin/application/status', 'AdminController@updateStatus');

// Определение текущего URL
// Если запрос перенаправлен через .htaccess: $_GET['route']
// Иначе берем из REQUEST_URI (для встроенного php -S)
if (isset($_GET['route'])) {
    $uri = $_GET['route'];
} else {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
}

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Запуск диспетчера
$router->dispatch($uri, $requestMethod);
