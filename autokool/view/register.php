<div class="container auth-container">
    <div class="auth-card">
        <h2>Konto loomine</h2>
        <p class="auth-subtitle">Registreeru autokooli õpilaseks</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">Kasutajanimi:</label>
                <input type="text" id="username" name="username" required placeholder="Vali kasutajanimi">
            </div>

            <div class="form-group">
                <label for="password">Parool:</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="password_confirm">Korda parooli:</label>
                <input type="password" id="password_confirm" name="password_confirm" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Loo konto</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <p style="font-size: 0.9rem; color: var(--muted);">
                Konto on juba olemas? <a href="/login" style="color: var(--primary); font-weight: 600;">Logi sisse</a>
            </p>
        </div>
    </div>
</div>
