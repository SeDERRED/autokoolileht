<div class="container auth-container">
    <div class="auth-card">
        <h2><?= e($title ?? 'Sisselogimine') ?></h2>
        <p class="auth-subtitle">Autokooli infosüsteem</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= e($success) ?>
            </div>
        <?php endif; ?>

        <form action="<?= e($actionUrl ?? '/login') ?>" method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">Kasutajanimi:</label>
                <input type="text" id="username" name="username" required placeholder="Sisesta kasutajanimi">
            </div>

            <div class="form-group">
                <label for="password">Parool:</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Logi sisse</button>
        </form>

        <div style="text-align: center; margin-top: 15px;">
            <p style="font-size: 0.9rem; color: var(--muted);">
                Pole veel kontot? <a href="/register" style="color: var(--primary); font-weight: 600;">Loo konto</a>
            </p>
        </div>
    </div>
</div>
