<div class="container page-section">
    <div class="admin-header-row">
        <div>
            <h1>Minu konto</h1>
            <p>Tere tulemast, <strong><?= e($username) ?></strong>! Siin näete oma esitatud koolitusavaldusi ja nende staatust.</p>
        </div>
        <a href="/courses" class="btn btn-primary">+ Vali uus koolituskursus</a>
    </div>

    <section class="admin-card">
        <h2>Minu registreeritud kursused</h2>
        <?php if (!empty($applications)): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Kuupäev</th>
                            <th>Kursus</th>
                            <th>Kestus</th>
                            <th>Hind</th>
                            <th>Olek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?= date('d.m.Y H:i', strtotime($app['created_at'])) ?></td>
                                <td><strong><?= e($app['course_title'] ?? 'Määramata') ?></strong></td>
                                <td><?= e($app['duration'] ?? '-') ?></td>
                                <td><?= number_format($app['price'] ?? 0, 2) ?> €</td>
                                <td>
                                    <span class="badge-status badge-<?= e($app['status']) ?>">
                                        <?php
                                            switch($app['status']) {
                                                case 'new': echo 'Ootel (Uus)'; break;
                                                case 'in_progress': echo 'Töös'; break;
                                                case 'approved': echo 'Kinnitatud'; break;
                                                case 'rejected': echo 'Tagasi lükatud'; break;
                                                default: echo e($app['status']);
                                            }
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state-box" style="text-align: center; padding: 40px 20px;">
                <p style="font-size: 1.1rem; color: var(--muted); margin-bottom: 20px;">Teil pole veel aktiivseid koolitustaotlusi.</p>
                <a href="/courses" class="btn btn-primary">Vali kursus ja alusta õppimist</a>
            </div>
        <?php endif; ?>
    </section>
</div>
