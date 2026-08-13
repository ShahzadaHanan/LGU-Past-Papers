<?php
$seo=$seo->data();
?>

<title>

<?= htmlspecialchars(
$seo['title']
); ?>

</title>

<meta
name="description"
content="<?= htmlspecialchars(
$seo['description']
); ?>">

<meta
name="keywords"
content="<?= htmlspecialchars(
$seo['keywords']
); ?>">

<link
rel="canonical"
href="<?= htmlspecialchars(
$seo['canonical']
); ?>">

<meta
property="og:title"
content="<?= htmlspecialchars(
$seo['title']
); ?>">

<meta
property="og:description"
content="<?= htmlspecialchars(
$seo['description']
); ?>">

<meta
property="og:url"
content="<?= htmlspecialchars(
$seo['canonical']
); ?>">

<meta
property="og:type"
content="website">

<meta
name="twitter:card"
content="summary_large_image">

<meta
name="twitter:title"
content="<?= htmlspecialchars(
$seo['title']
); ?>">

<meta
name="twitter:description"
content="<?= htmlspecialchars(
$seo['description']
); ?>">


