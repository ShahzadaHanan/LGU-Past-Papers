<main class="container section">
    <h1 style="font-size: 2.5rem; font-weight: 800; text-align: center; color: var(--primary-color); margin-bottom: 10px;">LGU Alumni Network</h1>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 50px;">Read inspiring stories and testimonials from graduates of Lahore Garrison University</p>

    <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
        <?php if (empty($alumni)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
                <p>No alumni testimonials listed yet. Check back soon!</p>
            </div>
        <?php else: ?>
            <?php foreach ($alumni as $alum): ?>
                <div class="card" style="padding: 25px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
                            <?php if (!empty($alum['photo'])): ?>
                                <img src="<?= htmlspecialchars($alum['photo']) ?>" alt="<?= htmlspecialchars($alum['name']) ?>" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 60px; height: 60px; border-radius: 50%; background-color: var(--border-color); display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--text-muted);">
                                    <?= substr($alum['name'], 0, 1) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary-color);"><?= htmlspecialchars($alum['name']) ?></h3>
                                <p style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($alum['degree']) ?> (Batch: <?= htmlspecialchars($alum['batch_year']) ?>)</p>
                            </div>
                        </div>
                        <p style="font-style: italic; font-size: 0.95rem; color: var(--text-color); line-height: 1.6; margin-bottom: 20px;">
                            "<?= htmlspecialchars($alum['testimonial']) ?>"
                        </p>
                    </div>
                    <?php if (!empty($alum['linkedin_url'])): ?>
                        <a href="<?= htmlspecialchars($alum['linkedin_url']) ?>" target="_blank" class="btn btn-secondary" style="padding: 8px 15px; font-size: 0.85rem; text-align: center;">
                            LinkedIn Profile
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
