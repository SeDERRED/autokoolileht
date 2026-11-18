<div class="container auth-container">
    <div class="auth-card">
        <h2>Juhtpaneeli sisselogimine</h2>
        <p class="auth-subtitle">Autokooli administraatori ligipääs</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/login/admin" method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">Administraatori kasutajanimi:</label>
                <input type="text" id="username" name="username" required placeholder="Sisesta kasutajanimi">
            </div>

            <div class="form-group">
                <label for="password">Parool:</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Logi sisse juhtpaneeli</button>
        </form>

        <div style="text-align: center; margin-top: 15px;">
            <p style="font-size: 0.9rem;">
                <a href="/" style="color: var(--muted);">&larr; Tagasi pealehele</a>
            </p>
        </div>
    </div>
</div>
