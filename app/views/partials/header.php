<?php
$db = new \App\Core\Database();
$activeDepartments = $db->fetchAll("SELECT name, slug FROM departments WHERE is_active = 1 ORDER BY display_order ASC, name ASC");
$whatsappNumber = $db->fetch("SELECT setting_value FROM site_settings WHERE setting_key = 'whatsapp_number'")['setting_value'] ?? '923000000000';
?>
<header>
    <div class="container nav-container">
        <a href="/" class="logo">
            <span class="logo-text">LGU Past Papers</span>
        </a>
        <nav>
            <ul class="nav-menu">
                <li><a href="/" class="nav-link">Home</a></li>
                <li class="dropdown">
                    <a href="/departments" class="nav-link">Departments ▾</a>
                    <div class="dropdown-menu">
                        <?php foreach ($activeDepartments as $dept): ?>
                            <a href="/department/<?= htmlspecialchars($dept['slug']) ?>" class="dropdown-item">
                                <?= htmlspecialchars($dept['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </li>
                <li><a href="/about-lgu" class="nav-link">About LGU</a></li>
                <li><a href="/alumni" class="nav-link">Alumni</a></li>
                <li><a href="/lectures" class="nav-link">Lectures</a></li>
                <li><a href="/about-us" class="nav-link">About Us</a></li>
                <li><a href="/contact-us" class="nav-link">Contact Us</a></li>
            </ul>
        </nav>
        <div class="right-controls">
            <button class="theme-btn" id="theme-toggle" aria-label="Toggle Theme">
                <!-- Moon Icon -->
                <svg id="theme-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>
            <a href="https://wa.me/<?= htmlspecialchars($whatsappNumber) ?>" target="_blank" class="btn" style="padding: 8px 16px; font-size: 0.9rem; background-color: #25d366; box-shadow: none;">
                WhatsApp Chat
            </a>
        </div>
    </div>
</header>

<script>
    // Dark/Light Theme Switcher script
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const currentTheme = localStorage.getItem('theme') || 'light';

    document.documentElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);

    themeToggle.addEventListener('click', () => {
        const theme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        updateThemeIcon(theme);
    });

    function updateThemeIcon(theme) {
        if (theme === 'dark') {
            themeIcon.innerHTML = '<circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>';
        } else {
            themeIcon.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>';
        }
    }
</script>