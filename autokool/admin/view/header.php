<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Autokooli Juhtpaneel') ?></title>
    <link rel="stylesheet" href="/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="container nav-wrapper">
            <a href="/" class="logo">
                <span class="logo-accent">Auto</span>kool <span style="font-size: 0.85rem; color: var(--muted); font-weight: normal;">| Juhtpaneel</span>
            </a>
            <nav class="nav-links">
                <a href="/">Pealeht</a>
                <a href="/courses">Kursused</a>
                <a href="/admin" class="nav-admin-badge">Juhtpaneel</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/logout">Logi välja (<?= htmlspecialchars($_SESSION['username'] ?? 'admin') ?>)</a>
                <?php else: ?>
                    <a href="/login" class="nav-login-btn">Logi sisse</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="main-content">
