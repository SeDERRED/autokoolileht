<?php
// inc/functions.php - Вспомогательные функции

// Рендеринг шаблона с передачей параметров
function renderView(string $viewName, array $data = []): void {
    extract($data);
    $viewPath = __DIR__ . '/../view/' . $viewName . '.php';

    if (file_exists($viewPath)) {
        require_once __DIR__ . '/../view/header.php';
        require_once $viewPath;
        require_once __DIR__ . '/../view/footer.php';
    } else {
        http_response_code(404);
        echo "Ошибка: шаблон view/{$viewName}.php не найден.";
    }
}

// Редирект на другой адрес
function redirect(string $path): void {
    if (!headers_sent()) {
        header("Location: " . $path);
    } else {
        echo "<script>window.location.href=" . json_encode($path) . ";</script>";
        echo "<noscript><meta http-equiv='refresh' content='0;url=" . htmlspecialchars($path, ENT_QUOTES) . "'></noscript>";
    }
    exit;
}

// Проверка авторизации
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

// Проверка прав администратора
function isAdmin(): bool {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Требовать права администратора (иначе редирект на логин)
function requireAdmin(): void {
    if (!isAdmin()) {
        redirect('/login');
    }
}

// Очистка входных данных от XSS
function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
