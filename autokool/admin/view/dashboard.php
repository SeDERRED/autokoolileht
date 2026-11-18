<div class="container admin-container">
    <div class="admin-header-row">
        <div>
            <h1>Autokooli Juhtpaneel</h1>
            <p>Õpilaste avalduste, kursuste ja statistika haldamine</p>
        </div>
        <a href="#new-course" class="btn btn-primary" onclick="toggleAddCourseForm()">+ Lisa uus kursus</a>
    </div>

    <!-- Статистический блок -->
    <?php if (isset($stats)): ?>
        <?php 
            $totalApps = max(1, (int)$stats['total_applications']);
            $approvedPct = round(((int)$stats['approved_applications'] / $totalApps) * 100);
            $inProgressPct = round(((int)$stats['in_progress_applications'] / $totalApps) * 100);
            $newPct = round(((int)$stats['new_applications'] / $totalApps) * 100);
            $rejectedPct = round(((int)$stats['rejected_applications'] / $totalApps) * 100);
        ?>
        <section class="admin-card" style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); border-top: 4px solid var(--primary); margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0; display: flex; align-items: center; gap: 8px;">
                    📊 Autokooli Statistika ja Analüütika
                </h2>
                <span style="font-size: 0.85rem; color: var(--muted); background: #e2e8f0; padding: 4px 10px; border-radius: 20px;">
                    Reaalajas andmed
                </span>
            </div>

            <!-- KPI карточки -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 25px;">
                <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 8px; padding: 18px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="font-size: 1.8rem; margin-bottom: 5px;">⏳</div>
                    <div style="font-size: 2.2rem; font-weight: 700; color: #0284c7; line-height: 1;"><?= (int)$stats['new_applications'] ?></div>
                    <div style="color: var(--muted); font-size: 0.85rem; margin-top: 6px; font-weight: 500;">Uued avaldused (ootel)</div>
                </div>

                <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 8px; padding: 18px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="font-size: 1.8rem; margin-bottom: 5px;">👥</div>
                    <div style="font-size: 2.2rem; font-weight: 700; color: var(--slate); line-height: 1;"><?= (int)$stats['total_applications'] ?></div>
                    <div style="color: var(--muted); font-size: 0.85rem; margin-top: 6px; font-weight: 500;">Avaldusi kokku</div>
                </div>

                <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 8px; padding: 18px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="font-size: 1.8rem; margin-bottom: 5px;">🚗</div>
                    <div style="font-size: 2.2rem; font-weight: 700; color: #16a34a; line-height: 1;"><?= (int)$stats['total_courses'] ?></div>
                    <div style="color: var(--muted); font-size: 0.85rem; margin-top: 6px; font-weight: 500;">Aktiivseid kursusi</div>
                </div>

                <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 8px; padding: 18px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="font-size: 1.8rem; margin-bottom: 5px;">💰</div>
                    <div style="font-size: 2.2rem; font-weight: 700; color: #d97706; line-height: 1;"><?= number_format((float)$stats['total_revenue'], 2) ?> €</div>
                    <div style="color: var(--muted); font-size: 0.85rem; margin-top: 6px; font-weight: 500;">Kinnitatud tulu</div>
                </div>
            </div>

            <!-- Аналитика статусов с прогресс-баром -->
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px;">
                    <span>Avalduste jaotus staatuste järgi:</span>
                    <span>
                        <span style="color: #0284c7;">Uus: <?= (int)$stats['new_applications'] ?></span> | 
                        <span style="color: #d97706;">Töös: <?= (int)$stats['in_progress_applications'] ?></span> | 
                        <span style="color: #16a34a;">Kinnitatud: <?= (int)$stats['approved_applications'] ?></span> | 
                        <span style="color: #dc2626;">Tagasi lükatud: <?= (int)$stats['rejected_applications'] ?></span>
                    </span>
                </div>
                <div style="height: 12px; border-radius: 6px; background: #e2e8f0; display: flex; overflow: hidden;">
                    <div style="width: <?= $newPct ?>%; background: #38bdf8;" title="Uus: <?= $newPct ?>%"></div>
                    <div style="width: <?= $inProgressPct ?>%; background: #fbbf24;" title="Töös: <?= $inProgressPct ?>%"></div>
                    <div style="width: <?= $approvedPct ?>%; background: #4ade80;" title="Kinnitatud: <?= $approvedPct ?>%"></div>
                    <div style="width: <?= $rejectedPct ?>%; background: #f87171;" title="Tagasi lükatud: <?= $rejectedPct ?>%"></div>
                </div>
            </div>
        </section>
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
