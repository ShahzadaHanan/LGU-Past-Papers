<section class="page-hero">
    <div class="container">
        <?php $crumbs = [
            ['label' => 'Degree Roadmaps', 'url' => '/roadmaps'],
            ['label' => $program['title'], 'url' => null],
        ]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1><?= htmlspecialchars($program['title'], ENT_QUOTES, 'UTF-8') ?> Roadmap</h1>
        <p><?= htmlspecialchars($departmentName, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</section>

<main class="container section">
    <div class="stats-strip" style="grid-template-columns:repeat(3,1fr); margin-top:0;">
        <div class="stat-card">
            <strong style="font-size:1.2rem;"><?= htmlspecialchars($program['duration'], ENT_QUOTES, 'UTF-8') ?></strong>
            <span>Duration</span>
        </div>
        <div class="stat-card">
            <strong style="font-size:1.2rem;"><?= $program['semesters'] ? (int) $program['semesters'] : '—' ?></strong>
            <span>Semesters</span>
        </div>
        <div class="stat-card">
            <strong style="font-size:1.2rem;"><?= $program['credit_hours'] ? (int) $program['credit_hours'] : '—' ?></strong>
            <span>Credit Hours</span>
        </div>
    </div>

    <?php if ($semesters): ?>
        <div class="section-head" style="text-align:left; margin:44px 0 20px;">
            <span class="eyebrow">Full Curriculum</span>
            <h2>Semester-by-semester breakdown</h2>
        </div>

        <div class="fee-accordion">
            <?php foreach ($semesters as $num => $courses): ?>
                <div class="fee-panel" data-accordion>
                    <button type="button" class="fee-panel__header" data-accordion-toggle>
                        <span class="fee-panel__title">Semester <?= (int) $num ?></span>
                        <span class="fee-toggle-icon">+</span>
                    </button>
                    <div class="fee-panel__panel" data-accordion-panel>
                        <div class="fee-panel__inner">
                            <div class="fee-table-wrap">
                                <table class="fee-table">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Code</th>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($courses as $course): ?>
                                            <tr>
                                                <td class="fee-cell--bold"><?= htmlspecialchars($course['title'] ?? 'See official roadmap', ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($course['code'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($course['group'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($program['official_url'])): ?>
            <p style="color:var(--text-muted); font-size:0.85rem; margin-top:16px;">
                Transcribed from LGU's own published roadmap — course codes/titles may be revised between catalogue years.
                <a href="<?= htmlspecialchars($program['official_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Check the official roadmap</a> before registering.
            </p>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state" style="margin-top:40px;">
            <p style="margin-bottom:16px;">
                We haven't transcribed <?= htmlspecialchars($program['title'], ENT_QUOTES, 'UTF-8') ?>'s full semester-by-semester
                curriculum yet — the duration and semester count above are verified<?= !empty($program['official_url']) ? ', but for the complete course list, the official roadmap is the authoritative source' : '' ?>.
            </p>
            <?php if (!empty($program['official_url'])): ?>
                <a href="<?= htmlspecialchars($program['official_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn">
                    View Official Roadmap
                </a>
            <?php else: ?>
                <p style="color:var(--text-muted); font-size:0.9rem;">LGU hasn't published an official roadmap page for this program yet.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="cta-band" style="margin-top:44px;">
        <h2>Looking for past papers instead?</h2>
        <p>Browse mid-term and final-term papers for this and every other LGU degree program.</p>
        <a href="/departments" class="btn btn-accent">Browse Departments</a>
    </div>
</main>
