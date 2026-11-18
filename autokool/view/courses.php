<section class="container page-section">
    <div class="section-title-wrap">
        <h1>Koolituskursuste nimekiri</h1>
        <p>Valige sobiv kategooria ja esitage koolituse taotlus mugavalt internetis.</p>
    </div>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <?= e($successMessage) ?>
        </div>
    <?php endif; ?>

    <div class="grid-courses">
        <?php foreach ($courses as $course): ?>
            <div class="card-course">
                <span class="badge-category">Kategooria <?= e($course['category']) ?></span>
                <h3><?= e($course['title']) ?></h3>
                <p class="course-desc"><?= e($course['description']) ?></p>
                <div class="course-meta">
                    <span>Õppe kestus: <strong><?= e($course['duration']) ?></strong></span>
                    <span class="course-price"><?= number_format($course['price'], 2) ?> €</span>
                </div>
                <button class="btn btn-block btn-primary open-enroll-modal" 
                        data-id="<?= e($course['id']) ?>" 
                        data-title="<?= e($course['title']) ?>">
                    Registreeru kursusele
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Kursusele registreerumise modaalaken -->
<div id="enrollModal" class="modal-backdrop" style="display: none;">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <h3 id="modalCourseTitle">Registreerumine koolitusele</h3>
        <p>Täitke allolev ankeet ja võtame teiega ühendust dokumentide vormistamiseks.</p>

        <form action="/apply" method="POST" class="modal-form">
            <input type="hidden" name="course_id" id="modalCourseId" value="">
            
            <div class="form-group">
                <label for="name">Teie ees- ja perekonnanimi:</label>
                <input type="text" id="name" name="name" required value="<?= e($_SESSION['username'] ?? '') ?>" placeholder="Jaan Tamm">
            </div>

            <div class="form-group">
                <label for="email">E-posti aadress:</label>
                <input type="email" id="email" name="email" required placeholder="jaan.tamm@example.ee">
            </div>

            <div class="form-group">
                <label for="phone">Telefoninumber:</label>
                <input type="tel" id="phone" name="phone" required placeholder="+372 555-12345">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Esita avaldus</button>
        </form>
    </div>
</div>
