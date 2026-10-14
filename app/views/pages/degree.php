<?php
$examTypeLabels = [
    'mids' => 'Mid-Term',
    'finals' => 'Final-Term',
    'summer_mids' => 'Summer Mid-Term',
    'summer_finals' => 'Summer Final-Term',
];
?>
<section class="page-hero">
    <div class="container">
        <?php $crumbs = [
            ['label' => $department['name'], 'url' => '/department/' . $department['slug']],
            ['label' => $degree['name'], 'url' => null],
        ]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1><?= htmlspecialchars($degree['name'], ENT_QUOTES, 'UTF-8') ?> Past Papers</h1>
        <p><?= htmlspecialchars($degree['description'] ?? ('All mid-term, final-term and summer past papers for ' . $degree['name'] . ' at Lahore Garrison University.'), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</section>

<main class="container section">
    <?php if (empty($bySubject)): ?>
        <div class="empty-state">
            No past papers uploaded yet for <?= htmlspecialchars($degree['name'], ENT_QUOTES, 'UTF-8') ?>.
            <a href="/contact-us">Submit one</a> if you have it.
        </div>
    <?php else: ?>
        <div class="fee-accordion">
            <?php foreach ($bySubject as $subjectName => $examGroups): ?>
                <div class="fee-panel" data-accordion>
                    <button type="button" class="fee-panel__header" data-accordion-toggle>
                        <span class="fee-panel__title"><?= htmlspecialchars($subjectName, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="fee-toggle-icon">+</span>
                    </button>
                    <div class="fee-panel__panel" data-accordion-panel>
                        <div class="fee-panel__inner">
                            <div class="fee-accordion fee-accordion--nested">
                                <?php foreach ($examTypeLabels as $examKey => $examLabel): ?>
                                    <?php $papersList = $examGroups[$examKey] ?? []; ?>
                                    <?php if (empty($papersList)): continue; endif; ?>
                                    <div class="fee-panel" data-accordion>
                                        <button type="button" class="fee-panel__header" data-accordion-toggle>
                                            <span class="fee-panel__title"><?= htmlspecialchars($examLabel, ENT_QUOTES, 'UTF-8') ?> (<?= count($papersList) ?>)</span>
                                            <span class="fee-toggle-icon">+</span>
                                        </button>
                                        <div class="fee-panel__panel" data-accordion-panel>
                                            <div class="fee-panel__inner">
                                                <?php foreach ($papersList as $paper): ?>
                                                    <a href="/paper/<?= htmlspecialchars($paper['slug'], ENT_QUOTES, 'UTF-8') ?>" class="paper-row" style="text-decoration:none; color:inherit;">
                                                        <div class="paper-row__meta">
                                                            <strong><?= htmlspecialchars($paper['subject_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                                            <span><?= htmlspecialchars($paper['session'], ENT_QUOTES, 'UTF-8') ?></span>
                                                        </div>
                                                        <span class="badge badge-primary">View Paper</span>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="margin-top:40px; display:flex; gap:14px; flex-wrap:wrap;">
        <a href="/lectures" class="btn btn-secondary">Watch <?= htmlspecialchars($degree['name'], ENT_QUOTES, 'UTF-8') ?> Lectures</a>
        <a href="/contact-us" class="btn btn-ghost">Submit a Past Paper</a>
    </div>
</main>
