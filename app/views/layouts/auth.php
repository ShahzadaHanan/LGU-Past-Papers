<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $title ?? 'Login' ?> | LGU Portal</title>
<link rel="stylesheet" href="<?= asset('css/auth.css') ?>">
</head>
<body>

<button class="theme-btn auth-theme-btn" id="auth-theme-toggle" type="button" aria-label="Toggle Theme">
<svg id="auth-theme-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
</svg>
</button>

<?= $content ?>

<script>
(function () {
    var toggle = document.getElementById('auth-theme-toggle');
    var icon = document.getElementById('auth-theme-icon');
    var root = document.documentElement;
    var current = localStorage.getItem('theme') || 'light';
    root.setAttribute('data-theme', current);
    setIcon(current);
    toggle.addEventListener('click', function () {
        var theme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        setIcon(theme);
    });
    function setIcon(theme) {
        icon.innerHTML = theme === 'dark'
            ? '<circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>'
            : '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>';
    }
})();
</script>
</body>
</html>
