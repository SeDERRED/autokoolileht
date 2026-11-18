<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Autokool') ?></title>
    <link rel="stylesheet" href="/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="container nav-wrapper">
            <a href="/" class="logo">
                <span class="logo-accent">Auto</span>kool
            </a>
            <nav class="nav-links">
                <a href="/">Pealeht</a>
                <a href="/courses">Kursused ja hinnad</a>

                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="/login/admin" class="nav-admin-badge">Juhtpaneel</a>
                    <?php else: ?>
                        <a href="/profile" class="nav-login-btn">Minu konto</a>
                    <?php endif; ?>
                    <a href="/logout">Logi välja (<?= e($_SESSION['username']) ?>)</a>
                <?php else: ?>
                    <a href="/login" class="nav-login-btn">Logi sisse</a>
                    <a href="/register" style="color: var(--primary); font-weight: 500;">Loo konto</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="main-content">
