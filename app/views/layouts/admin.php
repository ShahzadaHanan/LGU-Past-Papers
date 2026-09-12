<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?? 'Admin'; ?> | LGU Admin</title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/png" href="/favicon.png">
<link rel="stylesheet" href="<?= asset('css/admin.css'); ?>">
</head>
<body>

<div class="admin">

<?php require basePath('app/views/admin/partials/sidebar.php'); ?>

<div class="admin-content">

<?php require basePath('app/views/admin/partials/topbar.php'); ?>

<div class="page-content">

<?php if (!empty($session) && ($message = $session->flash('success'))): ?>
<div class="alert-success"><?= $message ?></div>
<?php endif; ?>

<?php if (!empty($session) && ($message = $session->flash('error'))): ?>
<div class="alert-danger"><?= $message ?></div>
<?php endif; ?>

<?= $content; ?>

</div>
</div>
</div>

<script src="<?= asset('js/admin.js'); ?>"></script>
</body>
</html>
