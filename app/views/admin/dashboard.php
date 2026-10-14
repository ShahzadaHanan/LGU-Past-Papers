<?php
$adminUser = $auth->user();

$statusColors = [
    'pending' => '#d97706',
    'new' => '#d97706',
    'approved' => '#16a34a',
    'contacted' => '#0f6b3e',
    'closed' => '#5c6b57',
    'rejected' => '#dc2626',
];

/** Builds the conic-gradient() background + legend data for a donut chart
 * from [{label,total}] rows — kept as one shared helper so the two donuts
 * on this page (submissions, bookings) can't drift out of sync. */
$buildDonut = function (array $rows) use ($statusColors): array {
    $total = array_sum(array_column($rows, 'total'));
    $segments = [];
    $cursor = 0;

    foreach ($rows as $row) {
        if ((int) $row['total'] === 0) {
            continue;
        }
        $pct = $total > 0 ? ($row['total'] / $total * 100) : 0;
        $color = $statusColors[$row['label']] ?? '#0f6b3e';
        $segments[] = [
            'label' => ucfirst((string) $row['label']),
            'total' => (int) $row['total'],
            'pct' => $pct,
            'color' => $color,
            'start' => $cursor,
            'end' => $cursor + $pct,
        ];
        $cursor += $pct;
    }

    return ['total' => $total, 'segments' => $segments];
};

$submissionDonut = $buildDonut($submissionStatus);
$bookingDonut = $buildDonut($bookingStatus);

$maxDeptPapers = max(1, ...array_column($papersByDepartment ?: [['total' => 0]], 'total'));
$maxRegistrations = max(1, ...array_column($registrationsByDay ?: [['total' => 0]], 'total'));
$maxViews = max(1, ...array_column($topPapers ?: [['views_count' => 0]], 'views_count'));
?>

<h1>Dashboard</h1>

<p style="color: var(--text-muted); margin: 6px 0 24px;">
    Welcome back, <strong style="color: var(--text-color);"><?= htmlspecialchars($adminUser['name'] ?? 'Admin'); ?></strong>.
</p>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['departments'] ?? 0); ?></div>
        <div class="stat-label">Departments</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['degrees'] ?? 0); ?></div>
        <div class="stat-label">Degree Programs</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['subjects'] ?? 0); ?></div>
        <div class="stat-label">Subjects</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['papers'] ?? 0); ?></div>
        <div class="stat-label">Past Papers</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['videos'] ?? 0); ?></div>
        <div class="stat-label">Video Lectures</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['bookings'] ?? 0); ?></div>
        <div class="stat-label">Class Bookings</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['registeredUsers'] ?? 0); ?></div>
        <div class="stat-label">Registered Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['admins'] ?? 0); ?></div>
        <div class="stat-label">Admin Accounts</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['subscribers'] ?? 0); ?></div>
        <div class="stat-label">Newsletter Subscribers</div>
    </div>
    <div class="stat-card<?= ($stats['pendingSubmissions'] ?? 0) > 0 ? ' stat-card--alert' : '' ?>">
        <div class="stat-value"><?= (int) ($stats['pendingSubmissions'] ?? 0); ?></div>
        <div class="stat-label">Pending Submissions</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) ($stats['alumni'] ?? 0); ?></div>
        <div class="stat-label">Alumni Testimonials</div>
    </div>
</div>

<div class="charts-grid">

    <div class="card">
        <div class="card-header">
            <h3>Papers by Department</h3>
        </div>
        <?php if (empty($papersByDepartment)): ?>
            <p style="color:var(--text-muted); font-size:0.9rem;">No papers yet.</p>
        <?php else: ?>
            <div class="chart-bars">
                <?php foreach ($papersByDepartment as $row): ?>
                    <div class="chart-bar-row">
                        <span class="chart-bar-row__label"><?= htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="chart-bar-row__track">
                            <span class="chart-bar-row__fill" style="width:<?= (int) $row['total'] / $maxDeptPapers * 100 ?>%;"></span>
                        </span>
                        <span class="chart-bar-row__value"><?= (int) $row['total'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>New Registrations (last 14 days)</h3>
        </div>
        <div class="chart-columns">
            <?php foreach ($registrationsByDay as $row): ?>
                <div class="chart-column" title="<?= (int) $row['total'] ?> on <?= htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8') ?>">
                    <span class="chart-column__bar" style="height:<?= max(4, (int) $row['total'] / $maxRegistrations * 100) ?>%;"></span>
                    <span class="chart-column__label"><?= htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Paper Submissions</h3>
        </div>
        <?php if ($submissionDonut['total'] === 0): ?>
            <p style="color:var(--text-muted); font-size:0.9rem;">No submissions yet.</p>
        <?php else: ?>
            <div class="donut-chart">
                <div class="donut-chart__circle" style="background:conic-gradient(<?php
                    $parts = [];
                    foreach ($submissionDonut['segments'] as $seg) {
                        $parts[] = "{$seg['color']} {$seg['start']}% {$seg['end']}%";
                    }
                    echo implode(', ', $parts);
                ?>);">
                    <div class="donut-chart__hole"><?= (int) $submissionDonut['total'] ?></div>
                </div>
                <ul class="donut-chart__legend">
                    <?php foreach ($submissionDonut['segments'] as $seg): ?>
                        <li><span class="donut-chart__dot" style="background-color:<?= $seg['color'] ?>;"></span><?= htmlspecialchars($seg['label'], ENT_QUOTES, 'UTF-8') ?> (<?= (int) $seg['total'] ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Class Bookings</h3>
        </div>
        <?php if ($bookingDonut['total'] === 0): ?>
            <p style="color:var(--text-muted); font-size:0.9rem;">No bookings yet.</p>
        <?php else: ?>
            <div class="donut-chart">
                <div class="donut-chart__circle" style="background:conic-gradient(<?php
                    $parts = [];
                    foreach ($bookingDonut['segments'] as $seg) {
                        $parts[] = "{$seg['color']} {$seg['start']}% {$seg['end']}%";
                    }
                    echo implode(', ', $parts);
                ?>);">
                    <div class="donut-chart__hole"><?= (int) $bookingDonut['total'] ?></div>
                </div>
                <ul class="donut-chart__legend">
                    <?php foreach ($bookingDonut['segments'] as $seg): ?>
                        <li><span class="donut-chart__dot" style="background-color:<?= $seg['color'] ?>;"></span><?= htmlspecialchars($seg['label'], ENT_QUOTES, 'UTF-8') ?> (<?= (int) $seg['total'] ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

</div>

<div class="card">
    <div class="card-header">
        <h3>Most-Viewed Papers</h3>
    </div>
    <?php if (empty($topPapers)): ?>
        <p style="color:var(--text-muted); font-size:0.9rem;">No views recorded yet.</p>
    <?php else: ?>
        <div class="chart-bars">
            <?php foreach ($topPapers as $paper): ?>
                <div class="chart-bar-row">
                    <span class="chart-bar-row__label"><?= htmlspecialchars($paper['subject_name'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($paper['session'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $paper['exam_type'])), ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="chart-bar-row__track">
                        <span class="chart-bar-row__fill" style="width:<?= (int) $paper['views_count'] / $maxViews * 100 ?>%; background-color:var(--accent-color);"></span>
                    </span>
                    <span class="chart-bar-row__value"><?= (int) $paper['views_count'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3>Quick Links</h3>
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <a class="btn-secondary" href="/admin/departments">Manage Departments</a>
        <a class="btn-secondary" href="/admin/sub-departments">Manage Degrees</a>
        <a class="btn-secondary" href="/admin/subjects">Manage Subjects</a>
        <a class="btn-secondary" href="/admin/papers">Manage Papers</a>
        <a class="btn-secondary" href="/admin/videos">Manage Videos</a>
        <a class="btn-secondary" href="/admin/class-bookings">View Bookings</a>
        <a class="btn-secondary" href="/admin/paper-submissions/list/pending">Pending Submissions</a>
        <a class="btn-secondary" href="/admin/site-users">Registered Users</a>
        <a class="btn-secondary" href="/admin/users">Admin Users</a>
    </div>
</div>
