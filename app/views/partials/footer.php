<?php
$db = new \App\Core\Database();
$activeDepartments = $db->fetchAll("SELECT name, slug FROM departments WHERE is_active = 1 ORDER BY display_order ASC, name LIMIT 5");
$settingsRows = $db->fetchAll("SELECT setting_key, setting_value FROM site_settings");
$settings = [];
foreach ($settingsRows as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
$copyrightText = $settings['copyright_text'] ?? ('© ' . date('Y') . ' Lahore Garrison University. All Rights Reserved.');
$facebookUrl = $settings['facebook_url'] ?? 'https://facebook.com';
$twitterUrl = $settings['twitter_url'] ?? 'https://twitter.com';
$linkedinUrl = $settings['linkedin_url'] ?? 'https://linkedin.com';
$whatsappNumber = $settings['whatsapp_number'] ?? '923000000000';
?>
<footer>
    <div class="container footer-grid">
        <div>
            <h3 class="footer-title">LGU Past Papers</h3>
            <p style="font-size: 0.9rem; color: #cbd5e1; margin-bottom: 20px;">
                Your ultimate resource for past exam papers, solutions, and premium video lectures at Lahore Garrison University.
            </p>
            <div style="display: flex; gap: 15px;">
                <a href="<?= htmlspecialchars($facebookUrl) ?>" target="_blank" class="footer-link" style="display: inline-block;">Facebook</a>
                <a href="<?= htmlspecialchars($twitterUrl) ?>" target="_blank" class="footer-link" style="display: inline-block;">Twitter</a>
                <a href="<?= htmlspecialchars($linkedinUrl) ?>" target="_blank" class="footer-link" style="display: inline-block;">LinkedIn</a>
            </div>
        </div>
        <div>
            <h3 class="footer-title">Quick Links</h3>
            <ul class="footer-links">
                <li><a href="/" class="footer-link">Home</a></li>
                <li><a href="/about-lgu" class="footer-link">About LGU</a></li>
                <li><a href="/alumni" class="footer-link">Alumni</a></li>
                <li><a href="/lectures" class="footer-link">Online Lectures</a></li>
                <li><a href="/about-us" class="footer-link">About Us</a></li>
                <li><a href="/contact-us" class="footer-link">Contact Us</a></li>
            </ul>
        </div>
        <div>
            <h3 class="footer-title">Departments</h3>
            <ul class="footer-links">
                <?php foreach ($activeDepartments as $dept): ?>
                    <li>
                        <a href="/department/<?= htmlspecialchars($dept['slug']) ?>" class="footer-link">
                            <?= htmlspecialchars($dept['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li><a href="/departments" class="footer-link" style="font-weight: 600;">View All Departments →</a></li>
            </ul>
        </div>
        <div>
            <h3 class="footer-title">Contact Support</h3>
            <p style="font-size: 0.9rem; color: #cbd5e1; margin-bottom: 10px;">
                Email: <?= htmlspecialchars($settings['contact_email'] ?? 'support@lgu.edu.pk') ?>
            </p>
            <p style="font-size: 0.9rem; color: #cbd5e1;">
                WhatsApp: +<?= htmlspecialchars($whatsappNumber) ?>
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p><?= htmlspecialchars($copyrightText) ?></p>
            <p style="margin-top: 5px; font-size: 0.75rem; opacity: 0.7;">Built for Lahore Garrison University Academic Resources Portal</p>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp Widget -->
<a href="https://wa.me/<?= htmlspecialchars($whatsappNumber) ?>" target="_blank" class="wa-widget" aria-label="Chat on WhatsApp">
    <!-- WhatsApp Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="fill: white; stroke: none;">
        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
    </svg>
</a>