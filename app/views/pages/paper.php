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

            <?php if (!empty($paper['solution_text']) || !empty($paper['solution_image'])): ?>
                <div class="fee-panel" style="margin-top:24px;">
                    <div class="fee-panel__header" style="cursor:default;">
                        <span class="fee-panel__title">Solution</span>
                    </div>
                    <div class="fee-panel__inner" style="border-top:1px solid var(--border-color);">
                        <?php if (!empty($paper['solution_text'])): ?>
                            <div class="card-text" style="font-size:0.98rem; line-height:1.8; white-space:pre-line;">
                                <?= nl2br(htmlspecialchars($paper['solution_text'], ENT_QUOTES, 'UTF-8')) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($paper['solution_image'])): ?>
                            <div style="<?= !empty($paper['solution_text']) ? 'margin-top:20px; padding-top:20px; border-top:1px dashed var(--border-color);' : '' ?>">
                                <a href="<?= htmlspecialchars($paper['solution_image'], ENT_QUOTES, 'UTF-8') ?>" data-lightbox-trigger>
                                    <img src="<?= htmlspecialchars($paper['solution_image'], ENT_QUOTES, 'UTF-8') ?>" alt="Solution sheet for <?= htmlspecialchars($paper['subject_name'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%; max-width:420px; height:auto; border-radius:var(--radius-md); cursor:zoom-in; display:block; margin:0 auto;">
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div style="flex:1 1 300px; display:flex; flex-direction:column; gap:24px;">
            <div class="fee-panel">
                <div class="fee-panel__header" style="cursor:default;">
                    <span class="fee-panel__title">Related Papers</span>
                </div>
                <div class="fee-panel__inner" style="border-top:1px solid var(--border-color);">
                    <?php if (empty($related)): ?>
                        <p style="color:var(--text-muted); font-size:0.9rem;">No related papers found for this exam type yet.</p>
                    <?php else: ?>
                        <div style="display:flex; flex-direction:column; gap:14px;">
                            <?php foreach ($related as $rel): ?>
                                <a href="/paper/<?= htmlspecialchars($rel['slug'], ENT_QUOTES, 'UTF-8') ?>" class="related-paper-row">
                                    <strong><?= htmlspecialchars($rel['subject_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                    <span><?= htmlspecialchars($rel['session'], ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars(ucwords(str_replace('_', ' ', $rel['exam_type'])), ENT_QUOTES, 'UTF-8') ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="fee-panel">
                <div class="fee-panel__header" style="cursor:default;">
                    <span class="fee-panel__title">Related Videos</span>
                </div>
                <div class="fee-panel__inner" style="border-top:1px solid var(--border-color);">
                    <?php if (empty($relatedVideos)): ?>
                        <p style="color:var(--text-muted); font-size:0.9rem;">No related videos found for this degree yet.</p>
                    <?php else: ?>
                        <div class="related-videos-grid">
                            <?php foreach ($relatedVideos as $video): ?>
                                <a href="<?= htmlspecialchars($video['youtube_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="related-video-row">
                                    <span class="related-video-thumb" style="background-image:url('https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_video_id'], ENT_QUOTES, 'UTF-8') ?>/hqdefault.jpg');">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8 5v14l11-7z"></path>
                                        </svg>
                                    </span>
                                    <span class="related-video-meta">
                                        <strong><?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <span><?= htmlspecialchars($video['subject_name'] ?? $video['category_name'] ?? 'LGU Hub', ENT_QUOTES, 'UTF-8') ?></span>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top:16px; text-align:center;">
                        <a href="/lectures?department=<?= htmlspecialchars($department['slug'], ENT_QUOTES, 'UTF-8') ?>&degree=<?= htmlspecialchars($degree['slug'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary btn-sm">
                            See More Videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal lightbox" data-lightbox>
    <img src="" alt="Paper preview">
</div>
