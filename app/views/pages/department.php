<section class="page-hero">
    <div class="container">
        <?php $crumbs = [
            ['label' => 'Departments', 'url' => '/departments'],
            ['label' => $department['name'], 'url' => null],
        ]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1><?= htmlspecialchars($department['name'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p><?= htmlspecialchars(mb_strimwidth(strip_tags($department['description'] ?? ''), 0, 160, '…'), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</section>

<main class="container section">
    <div style="display:flex; gap:40px; flex-wrap:wrap; margin-bottom:44px; align-items:flex-start;">
        <div style="flex:1 1 480px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px;">About this department</h2>
            <div class="card-text" style="font-size:1.03rem; line-height:1.8; color:var(--text-color);">
                <?php if (!empty($department['description'])): ?>
                    <?= $department['description'] /* trusted admin-authored rich text */ ?>
                <?php else: ?>
                    <p>
                        The <?= htmlspecialchars($department['name'], ENT_QUOTES, 'UTF-8') ?> department at Lahore
                        Garrison University offers current students a full archive of past papers and lectures
                        for every degree program listed below.
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!empty($department['hero_image'])): ?>
            <div style="flex:1 1 360px;">
                <img src="<?= htmlspecialchars($department['hero_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($department['name'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%; border-radius:var(--radius-lg); box-shadow:var(--shadow); object-fit:cover; height:300px;">
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($blocks)): ?>
        <section style="margin-bottom:44px; background-color:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:32px;">
            <?php foreach ($blocks as $block): ?>
                <div style="margin-bottom:24px;">
                    <h3 style="font-size:1.3rem; margin-bottom:12px; color:var(--primary-color);"><?= htmlspecialchars($block['heading'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <div style="color:var(--text-muted); line-height:1.7;"><?= $block['body'] ?></div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <div class="section-head" style="text-align:left; margin-bottom:24px;">
        <span class="eyebrow">Degree Programs</span>
        <h2>Choose your program</h2>
    </div>

    <div class="grid">
        <?php if (empty($degrees)): ?>
            <div class="empty-state">No degree programs listed under this department yet — check back soon.</div>
        <?php else: ?>
            <?php foreach ($degrees as $degree): ?>
                <div class="card">
                    <div class="card-content">
                        <h3 class="card-title"><?= htmlspecialchars($degree['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="card-text">
                            <?= htmlspecialchars(mb_strimwidth(strip_tags($degree['description'] ?? ''), 0, 110, '…'), ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <a href="/department/<?= htmlspecialchars($department['slug'], ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars($degree['slug'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-block">
                            View Past Papers
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
