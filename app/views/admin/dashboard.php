<?php $adminUser = $auth->user(); ?>

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
</div>

<div class="card">
    <div class="card-header">
        <h3>Quick Links</h3>
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <a class="btn-secondary" href="/admin/departments">Manage Departments</a>
        <a class="btn-secondary" href="/admin/papers">Manage Papers</a>
        <a class="btn-secondary" href="/admin/videos">Manage Videos</a>
        <a class="btn-secondary" href="/admin/class-bookings">View Bookings</a>
        <a class="btn-secondary" href="/admin/paper-submissions/list/pending">Pending Submissions</a>
    </div>
</div>
