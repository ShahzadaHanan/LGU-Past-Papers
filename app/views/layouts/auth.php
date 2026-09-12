<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($title ?? 'Login', ENT_QUOTES, 'UTF-8') ?> | LGU Hub</title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/png" href="/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/variables.css') ?>">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="auth-body">

<button class="theme-btn" data-theme-toggle style="position:fixed;top:20px;right:20px;" aria-label="Toggle theme">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
    </svg>
</button>

<div style="text-align:center;margin-bottom:8px;">
    <a href="/" style="display:inline-flex;flex-direction:column;align-items:center;gap:10px;text-decoration:none;color:#fff;margin-bottom:20px;">
        <img src="<?= asset('images/brand/lgu-crest-white.jpg') ?>" alt="LGU crest" style="height:64px;width:64px;border-radius:50%;">
        <strong style="font-size:1.05rem;">Lahore Garrison University</strong>
    </a>
</div>

<?= $content ?>

<script src="<?= asset('js/site.js') ?>"></script>
</body>
</html>
