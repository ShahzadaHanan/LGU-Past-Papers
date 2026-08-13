<main class="container section">
    <h1 style="font-size: 2.5rem; font-weight: 800; text-align: center; margin-bottom: 10px; color: var(--primary-color);">LGU Academic Departments</h1>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 50px;">Select your department below to view degrees, subjects, and download past exam papers.</p>
    
    <div class="grid">
        <?php foreach ($departments as $dept): ?>
            <div class="card">
                <div class="card-img" style="background-image: url('<?= htmlspecialchars($dept['hero_image'] ?? '/assets/images/default-dept.jpg') ?>');"></div>
                <div class="card-content">
                    <h2 class="card-title"><?= htmlspecialchars($dept['name']) ?></h2>
                    <p class="card-text" style="margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= strip_tags($dept['description'] ?? '') ?>
                    </p>
                    <a href="/department/<?= htmlspecialchars($dept['slug']) ?>" class="btn" style="width: 100%; text-align: center;">Explore Programs →</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
