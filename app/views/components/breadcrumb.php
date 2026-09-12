<?php
/**
 * Shared breadcrumb: renders both the visible nav and its BreadcrumbList
 * JSON-LD from one input, so callers stop hand-rolling both (previously
 * duplicated, inconsistently, in paper.php and degree.php).
 *
 * Expects $crumbs: array<int, array{label: string, url: ?string}> — the
 * last entry should have url => null (it's the current page).
 */
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="/">Home</a>
    <?php foreach ($crumbs as $crumb): ?>
        <span class="sep">/</span>
        <?php if (!empty($crumb['url'])): ?>
            <a href="<?= htmlspecialchars($crumb['url'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php else: ?>
            <span class="current"><?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>

<?php
$itemListElement = [[
    '@type' => 'ListItem',
    'position' => 1,
    'name' => 'Home',
    'item' => $baseUrl . '/',
]];

$position = 2;

foreach ($crumbs as $crumb) {
    $item = [
        '@type' => 'ListItem',
        'position' => $position,
        'name' => $crumb['label'],
    ];

    if (!empty($crumb['url'])) {
        $item['item'] = $baseUrl . $crumb['url'];
    }

    $itemListElement[] = $item;
    $position++;
}

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => $itemListElement,
];
?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
