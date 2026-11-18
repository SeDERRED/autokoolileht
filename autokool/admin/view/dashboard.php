<div class="container admin-container">
    <div class="admin-header-row">
        <div>
            <h1>Autokooli Juhtpaneel</h1>
            <p>Õpilaste avalduste, kursuste ja statistika haldamine</p>
        </div>
        <a href="#new-course" class="btn btn-primary" onclick="toggleAddCourseForm()">+ Lisa uus kursus</a>
    </div>

    <!-- Статистические карточки -->
    <?php if (isset($stats)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
            <div class="admin-card" style="margin-bottom: 0; text-align: center; padding: 20px;">
                <h3 style="font-size: 2rem; color: var(--primary); margin-bottom: 5px;"><?= e((string)$stats['new_applications']) ?></h3>
                <p style="color: var(--muted); font-size: 0.9rem;">Uued avaldused (ootel)</p>
            </div>
            <div class="admin-card" style="margin-bottom: 0; text-align: center; padding: 20px;">
                <h3 style="font-size: 2rem; color: var(--slate); margin-bottom: 5px;"><?= e((string)$stats['total_applications']) ?></h3>
                <p style="color: var(--muted); font-size: 0.9rem;">Avaldusi kokku</p>
            </div>
            <div class="admin-card" style="margin-bottom: 0; text-align: center; padding: 20px;">
                <h3 style="font-size: 2rem; color: var(--slate); margin-bottom: 5px;"><?= e((string)$stats['total_courses']) ?></h3>
                <p style="color: var(--muted); font-size: 0.9rem;">Aktiivseid kursusi</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($flash_msg)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($flash_msg) ?>
        </div>
    <?php endif; ?>

    <!-- Uue kursuse lisamise vorm (vaikimisi peidetud) -->
    <div id="addCourseBox" class="admin-card add-course-box" style="display: none;">
        <h3>Uue kursuse loomine</h3>
        <form action="/admin/course/add" method="POST" class="grid-form">
            <div class="form-group">
                <label>Kursuse nimetus:</label>
                <input type="text" name="title" required placeholder="D-kategooria (Bussid)">
            </div>
            <div class="form-group">
                <label>Kategooria (tähis):</label>
                <input type="text" name="category" required placeholder="D">
            </div>
            <div class="form-group">
                <label>Õppe kestus:</label>
                <input type="text" name="duration" required placeholder="8 nädalat">
            </div>
            <div class="form-group">
                <label>Hind (€):</label>
                <input type="number" step="0.01" name="price" required placeholder="790.00">
            </div>
            <div class="form-group full-width">
                <label>Kursuse kirjeldus:</label>
                <textarea name="description" rows="3" required placeholder="Kursuse täpsem kirjeldus ja nõuded..."></textarea>
            </div>
            <div class="form-actions full-width">
                <button type="submit" class="btn btn-primary">Salvesta kursus</button>
                <button type="button" class="btn btn-secondary" onclick="toggleAddCourseForm()">Loobu</button>
            </div>
        </form>
    </div>

    <!-- Avalduste tabel -->
    <section class="admin-card">
        <h2>Saabunud registreerimisavaldused</h2>
        <?php if (!empty($applications)): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kuupäev</th>
                            <th>Kursus</th>
                            <th>Õpilase nimi</th>
                            <th>Telefon</th>
                            <th>E-post</th>
                            <th>Olek</th>
                            <th>Tegevus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($app['id']) ?></td>
                                <td><?= date('d.m.Y H:i', strtotime($app['created_at'])) ?></td>
                                <td><strong><?= htmlspecialchars($app['course_title'] ?? 'Määramata') ?></strong></td>
                                <td><?= htmlspecialchars($app['name']) ?></td>
                                <td><a href="tel:<?= htmlspecialchars($app['phone']) ?>"><?= htmlspecialchars($app['phone']) ?></a></td>
                                <td><a href="mailto:<?= htmlspecialchars($app['email']) ?>"><?= htmlspecialchars($app['email']) ?></a></td>
                                <td>
                                    <span class="badge-status badge-<?= htmlspecialchars($app['status']) ?>">
                                        <?php
                                            switch($app['status']) {
                                                case 'new': echo 'Uus'; break;
                                                case 'in_progress': echo 'Töös'; break;
                                                case 'approved': echo 'Kinnitatud'; break;
                                                case 'rejected': echo 'Tagasi lükatud'; break;
                                                default: echo htmlspecialchars($app['status']);
                                            }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="/admin/application/status" method="POST" class="inline-form">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($app['id']) ?>">
                                        <select name="status" onchange="this.form.submit()" class="status-select">
                                            <option value="new" <?= $app['status'] === 'new' ? 'selected' : '' ?>>Uus</option>
                                            <option value="in_progress" <?= $app['status'] === 'in_progress' ? 'selected' : '' ?>>Töös</option>
                                            <option value="approved" <?= $app['status'] === 'approved' ? 'selected' : '' ?>>Kinnitatud</option>
                                            <option value="rejected" <?= $app['status'] === 'rejected' ? 'selected' : '' ?>>Tagasi lükatud</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty-state">Avaldusi ei ole veel laekunud.</p>
        <?php endif; ?>
    </section>

    <!-- Kursuste nimekiri -->
    <section class="admin-card">
        <h2>Kursuste haldamine</h2>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategooria</th>
                        <th>Nimetus</th>
                        <th>Kestus</th>
                        <th>Hind</th>
                        <th>Kustutamine</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['id']) ?></td>
                            <td><span class="badge-category"><?= htmlspecialchars($c['category']) ?></span></td>
                            <td><?= htmlspecialchars($c['title']) ?></td>
                            <td><?= htmlspecialchars($c['duration']) ?></td>
                            <td><strong><?= number_format($c['price'], 2) ?> €</strong></td>
                            <td>
                                <form action="/admin/course/delete" method="POST" onsubmit="return confirm('Kas olete kindel, et soovite selle kursuse kustutada?');">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($c['id']) ?>">
                                    <button type="submit" class="btn-danger-small">Kustuta</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
