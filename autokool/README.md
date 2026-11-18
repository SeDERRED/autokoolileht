# Autokool - Веб-сайт автошколы на PHP (Архитектура MVC)

Проект построен по архитектуре **Model-View-Controller (MVC)** с единой точкой входа (`index.php`) и чистым разделением логики.

## Структура проекта
```text
autokool/
├── admin/               # Модуль административной панели
├── controller/          # Контроллеры (HomeController, CourseController, AuthController, AdminController)
├── images/ (imeges/)    # Изображения и графика
├── inc/                 # Ядро: db.php (PDO), functions.php (хелперы)
├── js/                  # main.js (модальные окна, AJAX, клиентская логика)
├── model/               # Модели данных (CourseModel, UserModel, ApplicationModel)
├── route/               # routing.php (маршрутизатор запросов)
├── view/                # HTML-шаблоны (header, footer, home, courses, login, admin_dashboard)
├── .dockerignore        # Исключения для Docker
├── .htaccess            # ЧПУ и перенаправление запросов на index.php
├── Dockerfile           # Конфигурация контейнера PHP 8.2 + Apache
├── docker-compose.yml   # Оркестрация (PHP + MySQL 8.0 + phpMyAdmin)
├── index.php            # Главная точка входа (Front Controller)
├── style.css            # Таблица стилей
├── database.sql         # SQL-дамп для импорта в MySQL
└── README.md            # Инструкция к проекту
```

---

## Запуск через Docker (Рекомендуемый способ)

В проект добавлены `Dockerfile` и `docker-compose.yml`, которые автоматически поднимают:
1. **PHP 8.2 + Apache** с включенным `mod_rewrite` и поддержкой `.htaccess`.
2. **MySQL 8.0** с автоматическим созданием таблиц из `database.sql`.
3. **phpMyAdmin** для удобного управления базой данных через веб-интерфейс.

### Команда для запуска:
```bash
docker compose up -d --build
```

### Доступные адреса:
- **Сайт автошколы:** [http://localhost:8080](http://localhost:8080)
- **phpMyAdmin (БД):** [http://localhost:8081](http://localhost:8081)
  - Сервер: `db`
  - Пользователь: `autokool_user`
  - Пароль: `autokool_pass`

### Остановка контейнеров:
```bash
docker compose down
```

> **Горячая перезагрузка (Live Reload):** Папка проекта примонтирована внутрь контейнера через том (`volume`). Любые изменения в PHP, CSS или JS файлах сразу отображаются в браузере без необходимости пересобирать контейнер!

---

## Запуск без Docker

### Вариант 1: Встроенный сервер PHP
```bash
php -S localhost:8000
```
Сайт доступен на `http://localhost:8000` (работает на встроенной SQLite).

### Вариант 2: OpenServer / XAMPP
Скопируйте папку в директорию веб-сервера (`OSPanel/domains/` или `xampp/htdocs/`).

---

## Данные для входа в панель администратора
- **URL входа:** `/login` (или кнопка «Вход» в шапке)
- **Логин:** `admin`
- **Пароль:** `admin123`
