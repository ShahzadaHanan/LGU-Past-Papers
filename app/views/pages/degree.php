<?php
$examTypes = [
    'mids' => 'Mid-Term Papers',
    'finals' => 'Final-Term Papers',
    'summer_mids' => 'Summer Mid-Term Papers',
    'summer_finals' => 'Summer Final-Term Papers',
];
$firstKey = array_key_first($examTypes);
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
    <div class="exam-tabs">
        <?php foreach ($examTypes as $key => $label): $papersList = $groupedPapers[$key] ?? []; ?>
            <button type="button" class="exam-tab <?= $key === $firstKey ? 'active' : '' ?>" data-exam-tab="<?= $key ?>">
                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?> (<?= count($papersList) ?>)
            </button>
        <?php endforeach; ?>
    </div>

    <?php foreach ($examTypes as $key => $label): $papersList = $groupedPapers[$key] ?? []; ?>
        <div class="exam-panel <?= $key === $firstKey ? 'active' : '' ?>" data-exam-panel="<?= $key ?>">
            <?php if (empty($papersList)): ?>
                <div class="empty-state">
                    No <?= htmlspecialchars(strtolower($label), ENT_QUOTES, 'UTF-8') ?> uploaded yet.
                    <a href="/contact-us">Submit one</a> if you have it.
                </div>
            <?php else: ?>
                <?php foreach ($papersList as $paper): ?>
                    <a href="/paper/<?= htmlspecialchars($paper['slug'], ENT_QUOTES, 'UTF-8') ?>" class="paper-row" style="text-decoration:none; color:inherit;">
                        <div class="paper-row__meta">
                            <strong><?= htmlspecialchars($paper['subject_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <span><?= htmlspecialchars($paper['session'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <span class="badge badge-primary">View Paper</span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div style="margin-top:40px; display:flex; gap:14px; flex-wrap:wrap;">
        <a href="/lectures" class="btn btn-secondary">Watch <?= htmlspecialchars($degree['name'], ENT_QUOTES, 'UTF-8') ?> Lectures</a>
        <a href="/contact-us" class="btn btn-ghost">Submit a Past Paper</a>
    </div>
</main>
