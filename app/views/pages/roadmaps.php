<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'Degree Roadmaps', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>All Degree Roadmaps</h1>
        <p>Duration, credit hours and semester structure for LGU's degree programs, grouped by department.</p>
    </div>
</section>

<main class="container section">
    <div class="alert alert-success" style="max-width:780px;">
        Program durations and credit hours below are verified against LGU's own published roadmap pages.
        Where the full semester-by-semester curriculum has been transcribed, it's one click away —
        otherwise "View Roadmap" links out to the official page for the complete course list.
    </div>

    <?php foreach ($roadmaps as $deptName => $programs): ?>
        <h2 class="faculty-heading"><?= htmlspecialchars($deptName, ENT_QUOTES, 'UTF-8') ?></h2>
        <div class="fee-table-wrap">
            <table class="fee-table roadmap-list-table">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Duration</th>
                        <th>Semesters</th>
                        <th>Credit Hours</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($programs as $p): ?>
                        <tr>
                            <td class="fee-cell--bold"><?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($p['duration'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $p['semesters'] ? (int) $p['semesters'] : '—' ?></td>
                            <td><?= $p['credit_hours'] ? (int) $p['credit_hours'] : '—' ?></td>
                            <td><a href="/roadmaps/<?= htmlspecialchars($p['slug'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary btn-sm">Road Map</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</main>
