<main class="container section">
    <div style="display: flex; gap: 40px; flex-wrap: wrap; margin-bottom: 50px;">
        <div style="flex: 1 1 500px;">
            <h1 style="font-size: 2.6rem; font-weight: 800; color: var(--primary-color); margin-bottom: 20px;">
                <?= htmlspecialchars($department['name']) ?>
            </h1>
            <div class="card-text" style="font-size: 1.1rem; line-height: 1.8; color: var(--text-color); margin-bottom: 30px;">
                <?= $department['description'] ?>
            </div>
        </div>
        <?php if (!empty($department['hero_image'])): ?>
            <div style="flex: 1 1 400px;">
                <img src="<?= htmlspecialchars($department['hero_image']) ?>" alt="<?= htmlspecialchars($department['name']) ?>" style="width: 100%; border-radius: 16px; box-shadow: var(--shadow); object-fit: cover; height: 350px;">
            </div>
        <?php endif; ?>
    </div>

    <!-- Career Opportunities Content Blocks -->
    <?php if (!empty($blocks)): ?>
        <section style="margin-bottom: 50px; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 40px;">
            <?php foreach ($blocks as $block): ?>
                <div style="margin-bottom: 30px;">
                    <h3 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 15px; color: var(--primary-color);"><?= htmlspecialchars($block['heading']) ?></h3>
                    <div style="color: var(--text-muted); font-size: 1rem; line-height: 1.7;"><?= $block['body'] ?></div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <!-- Degrees list (Sub-Departments) -->
    <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 20px;">Degree Programs Offered</h2>
    <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
        <?php if (empty($degrees)): ?>
            <p style="color: var(--text-muted);">No degree programs listed under this department yet.</p>
        <?php else: ?>
            <?php foreach ($degrees as $degree): ?>
                <div class="card" style="padding: 25px; display: flex; flex-direction: column; justify-content: space-between; min-height: 200px;">
                    <div>
                        <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 10px; color: var(--primary-color);"><?= htmlspecialchars($degree['name']) ?></h3>
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= strip_tags($degree['description'] ?? '') ?>
                        </p>
                    </div>
                    <a href="/department/<?= htmlspecialchars($department['slug']) ?>/<?= htmlspecialchars($degree['slug']) ?>" class="btn" style="text-align: center;">View Past Papers</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
