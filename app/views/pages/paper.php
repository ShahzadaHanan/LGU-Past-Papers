<?php $examLabel = ucwords(str_replace('_', ' ', $paper['exam_type'])); ?>
<main class="container section">
    <?php $crumbs = [
        ['label' => $department['name'], 'url' => '/department/' . $department['slug']],
        ['label' => $degree['name'], 'url' => '/department/' . $department['slug'] . '/' . $degree['slug']],
        ['label' => $paper['subject_name'] . ' (' . $paper['session'] . ')', 'url' => null],
    ]; require basePath('app/views/components/breadcrumb.php'); ?>

    <div style="margin-bottom:32px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <span class="badge badge-accent"><?= htmlspecialchars($examLabel, ENT_QUOTES, 'UTF-8') ?></span>
        <span class="badge badge-primary"><?= htmlspecialchars($paper['session'], ENT_QUOTES, 'UTF-8') ?></span>
    </div>

    <h1 style="font-size:2rem; margin-bottom:8px;">
        <?= htmlspecialchars($paper['subject_name'], ENT_QUOTES, 'UTF-8') ?>
    </h1>
    <p style="color:var(--text-muted); margin-bottom:32px;">
        <?= htmlspecialchars($degree['name'], ENT_QUOTES, 'UTF-8') ?> ·
        <?= (int) $paper['views_count'] ?> view<?= (int) $paper['views_count'] === 1 ? '' : 's' ?>
    </p>

    <div style="display:flex; gap:32px; flex-wrap:wrap;">
        <div style="flex:2 1 560px;">
            <div style="border:1px solid var(--border-color); border-radius:var(--radius-lg); overflow:hidden; background-color:var(--bg-card); padding:16px;">
                <a href="<?= htmlspecialchars($paper['paper_image'], ENT_QUOTES, 'UTF-8') ?>" data-lightbox-trigger>
                    <img src="<?= htmlspecialchars($paper['paper_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($paper['subject_name'], ENT_QUOTES, 'UTF-8') ?> past paper" style="width:100%; height:auto; border-radius:var(--radius-md); cursor:zoom-in;">
                </a>
                <div style="text-align:center; margin-top:16px;">
                    <a href="<?= htmlspecialchars($paper['download_file'] ?? $paper['paper_image'], ENT_QUOTES, 'UTF-8') ?>" download class="btn">
                        Download Past Paper
                    </a>
                </div>
            </div>
        </div>

        <div style="flex:1 1 300px; display:flex; flex-direction:column; gap:24px;">
            <?php if (!empty($paper['solution_image'])): ?>
                <div style="background-color:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:22px;">
                    <h3 style="font-size:1.1rem; margin-bottom:10px; color:var(--primary-color);">Solution Available</h3>
                    <p style="font-size:0.9rem; color:var(--text-muted); margin-bottom:16px;">An admin-verified solution sheet has been uploaded for this paper.</p>
                    <a href="<?= htmlspecialchars($paper['solution_image'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-block">View Solution Sheet</a>
                </div>
            <?php endif; ?>

            <div style="background-color:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:22px;">
                <h3 style="font-size:1.1rem; margin-bottom:14px; color:var(--primary-color);">Related Papers</h3>
                <?php if (empty($related)): ?>
                    <p style="color:var(--text-muted); font-size:0.9rem;">No related papers found for this exam type yet.</p>
                <?php else: ?>
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        <?php foreach ($related as $rel): ?>
                            <a href="/paper/<?= htmlspecialchars($rel['slug'], ENT_QUOTES, 'UTF-8') ?>" style="text-decoration:none; color:var(--text-color);">
                                <strong style="font-size:0.92rem; display:block;"><?= htmlspecialchars($rel['subject_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <span style="font-size:0.8rem; color:var(--text-muted);"><?= htmlspecialchars($rel['session'], ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars(ucwords(str_replace('_', ' ', $rel['exam_type'])), ENT_QUOTES, 'UTF-8') ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <a href="/lectures?category=<?= htmlspecialchars($department['slug'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-accent btn-block">
                Watch Related Lectures
            </a>
        </div>
    </div>
</main>

<div class="modal lightbox" data-lightbox>
    <img src="" alt="Paper preview">
</div>
