<?php
// route/routing.php - Маршрутизатор запросов

class Router {
    private array $routes = [];

    // Регистрация GET-маршрута
    public function get(string $path, string $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    // Регистрация POST-маршрута
    public function post(string $path, string $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    // Универсальный метод добавления маршрута
    public function addRoute(string $method, string $path, string $handler): void {
        $normalizedPath = trim($path, '/');
        $this->routes[strtoupper($method)][$normalizedPath] = $handler;
    }

    // Диспетчеризация входящего запроса
    public function dispatch(string $uri, string $requestMethod): void {
        // Очищаем путь от GET-параметров и слэшей
        $path = trim(parse_url($uri, PHP_URL_PATH), '/');
        $method = strtoupper($requestMethod);

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            [$controllerName, $action] = explode('@', $handler);

            $controllerFile = __DIR__ . '/../controller/' . $controllerName . '.php';
            if (!file_exists($controllerFile)) {
                $controllerFile = __DIR__ . '/../admin/controller/' . $controllerName . '.php';
            }

            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $action)) {
                        $controller->$action();
                        return;
                    }
                }
            }
        }

        // Если точного совпадения нет, проверяем параметризованные маршруты (например, courses/view?id=1 или courses/detail/1)
        // Для простоты, если путь не найден:
        http_response_code(404);
        renderView('404', ['title' => 'Страница не найдена']);
    }
}
