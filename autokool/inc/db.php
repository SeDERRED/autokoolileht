<?php
// inc/db.php - Подключение к базе данных через PDO

// Считываем настройки из переменных окружения (для Docker) или используем значения по умолчанию
$envDriver = getenv('DB_DRIVER');
$dbHost    = getenv('DB_HOST') ?: '127.0.0.1';
$dbName    = getenv('DB_NAME') ?: 'autokool';
$dbUser    = getenv('DB_USER') ?: 'root';
$dbPass    = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

// Если переменная DB_DRIVER не задана явно:
// если передан DB_HOST (например, в Docker), используем mysql, иначе sqlite
if ($envDriver) {
    $dbDriver = $envDriver;
} else {
    $dbDriver = getenv('DB_HOST') ? 'mysql' : 'sqlite';
}

try {
    if ($dbDriver === 'sqlite') {
        $dbPath = __DIR__ . '/../database.sqlite';
        $pdo = new PDO("sqlite:" . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Автоматически создаем таблицы SQLite при первом локальном запуске
        initSqliteTables($pdo);
    } else {
        $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . htmlspecialchars($e->getMessage()));
}

function initSqliteTables(PDO $pdo): void {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'user',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS courses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            category TEXT NOT NULL,
            description TEXT NOT NULL,
            duration TEXT NOT NULL,
            price REAL NOT NULL,
            image TEXT DEFAULT 'course-default.jpg'
        );

        CREATE TABLE IF NOT EXISTS applications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            course_id INTEGER,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT NOT NULL,
            status TEXT DEFAULT 'new',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // Создаем тестового администратора (admin / admin123)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO users (username, password, role) VALUES ('admin', ?, 'admin')");
        $insert->execute([$hash]);
    }

    // Lisame algkursused, kui tabel on tühi
    $count = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    if ($count == 0) {
        $courses = [
            ['B-kategooria (Sõiduauto)', 'B', 'Täielik teooria- ja sõiduõppekursus manuaal- või automaatkäigukastiga autol. Ettevalmistus riiklikeks eksamiteks.', '6-8 nädalat', 650.00],
            ['A-kategooria (Mootorrattad)', 'A', 'Kõikide mootorrataste juhtimisõigus. Praktiline sõiduõpe ja ettevalmistus Transpordiameti eksamiteks.', '4-5 nädalat', 450.00],
            ['C-kategooria (Veoautod)', 'C', 'Veoautojuhtide kutseõpe sõidukitele registrimassiga üle 3500 kg.', '8-10 nädalat', 850.00],
            ['Lõppastmekoolitus (Libedasõit)', 'B-talv', 'Spetsiaalne koolitus ohutuks ja säästlikuks sõiduks rasketes teeoludes ning libedarajal.', '1 päev', 120.00]
        ];

        $ins = $pdo->prepare("INSERT INTO courses (title, category, description, duration, price) VALUES (?, ?, ?, ?, ?)");
        foreach ($courses as $c) {
            $ins->execute($c);
        }
    }
}
