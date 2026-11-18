<section class="hero-section">
    <div class="container hero-content">
        <h1 class="hero-title">Saa enesekindlaks juhiks koos <span class="text-accent">Autokool</span>iga!</h1>
        <p class="hero-subtitle">Kaasaegsed õppesõidukid, kogenud instruktorid ja kõrge eksamite sooritamise tase esimesel katsel.</p>
        <div class="hero-actions">
            <a href="/courses" class="btn btn-primary">Vali kategooria</a>
            <a href="#features" class="btn btn-secondary">Meist lähemalt</a>
        </div>
    </div>
</section>

<section id="features" class="container features-section">
    <div class="feature-card">
        <div class="feature-icon">🚗</div>
        <h3>Uus autopark</h3>
        <p>Mugavad ja turvalised manuaal- ning automaatkäigukastiga õppesõidukid.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">⏱️</div>
        <h3>Paindlik graafik</h3>
        <p>Hommikused, õhtused ja nädalavahetuse grupid. Individuaalne sõidutundide ajakava.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">🎓</div>
        <h3>Kogenud õpetajad</h3>
        <p>Sõbralikud ja litsentseeritud instruktorid enam kui 10-aastase õpetamiskogemusega.</p>
    </div>
</section>

<section class="container popular-courses">
    <div class="section-header">
        <h2>Populaarsed koolitusprogrammid</h2>
        <a href="/courses" class="link-more">Vaata kõiki kursusi &rarr;</a>
    </div>

    <div class="grid-courses">
        <?php foreach ($courses as $course): ?>
            <div class="card-course">
                <span class="badge-category">Kategooria <?= e($course['category']) ?></span>
                <h3><?= e($course['title']) ?></h3>
                <p class="course-desc"><?= e($course['description']) ?></p>
                <div class="course-meta">
                    <span>Kestus: <strong><?= e($course['duration']) ?></strong></span>
                    <span class="course-price"><?= number_format($course['price'], 2) ?> €</span>
                </div>
                <a href="/courses" class="btn btn-block btn-outline">Registreeru</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
