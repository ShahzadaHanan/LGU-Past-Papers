<?php
/** @var \App\Services\SeoService $seo */
$seoData = $seo->data();
$siteName = 'LGU Hub';
$pageTitle = $seoData['title'] !== '' ? $seoData['title'] : ($title ?? $siteName);
$fullTitle = str_contains($pageTitle, $siteName) ? $pageTitle : "{$pageTitle} | {$siteName}";
$metaDescription = $seoData['description'] !== ''
    ? $seoData['description']
    : 'Past papers, online lectures and class bookings for Lahore Garrison University students — organised by department, degree and exam type.';
$requestPath = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
$baseUrl = rtrim(env('APP_URL', ''), '/');
$canonical = $seoData['canonical'] !== '' ? $seoData['canonical'] : ($baseUrl . $requestPath);
$ogImage = $seoData['ogImage'] !== '' ? $seoData['ogImage'] : ($baseUrl . asset('images/brand/campus-hero.jpg'));
$gaId = $siteSettings['ga_measurement_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($fullTitle, ENT_QUOTES, 'UTF-8') ?></title>
<meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
<meta name="robots" content="<?= htmlspecialchars($seoData['robots'], ENT_QUOTES, 'UTF-8') ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">

<meta property="og:site_name" content="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:type" content="<?= htmlspecialchars($seoData['og']['type'] ?? 'website', ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
<meta name="twitter:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">

<link rel="icon" type="image/png" href="/favicon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?= asset('css/style.css') ?>">

<?php foreach ($seoData['jsonLd'] as $schema): ?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
<?php endforeach; ?>

<?php if (!empty($gaId)): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($gaId, ENT_QUOTES, 'UTF-8') ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= htmlspecialchars($gaId, ENT_QUOTES, 'UTF-8') ?>');
</script>
<?php endif; ?>

</head>

<body>

<?php require basePath('app/views/partials/news-bar.php'); ?>
<?php require basePath('app/views/partials/header.php'); ?>

<?= $content ?>

<?php require basePath('app/views/partials/footer.php'); ?>

<script src="<?= asset('js/site.js') ?>"></script>

</body>

</html>
