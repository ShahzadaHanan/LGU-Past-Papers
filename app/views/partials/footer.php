<?php
$copyrightText = $siteSettings['copyright_text'] ?? ('© ' . date('Y') . ' Lahore Garrison University. All Rights Reserved.');
$facebookUrl = $siteSettings['facebook_url'] ?? 'https://facebook.com';
$twitterUrl = $siteSettings['twitter_url'] ?? 'https://twitter.com';
$linkedinUrl = $siteSettings['linkedin_url'] ?? 'https://linkedin.com';
$whatsappNumber = $siteSettings['whatsapp_number'] ?? '923000000000';
$contactEmail = $siteSettings['contact_email'] ?? 'info@lgu.edu.pk';
$contactAddress = $siteSettings['contact_address'] ?? 'Sector C, Phase VI, DHA, Lahore, Pakistan';
$footerDepartments = array_slice($navDepartments, 0, 6);
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <a href="/" class="footer-logo">
                <img src="<?= asset('images/brand/lgu-crest-white.jpg') ?>" alt="LGU crest">
                LGU Hub
            </a>
            <p style="font-size:0.88rem;color:#c2d0be;margin-bottom:16px;">
                An independent, student-built resource for Lahore Garrison University —
                past papers, online lectures, and class bookings organised by department and degree.
            </p>
            <div class="footer-social">
                <a href="<?= htmlspecialchars($facebookUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="Facebook">f</a>
                <a href="<?= htmlspecialchars($twitterUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="Twitter">x</a>
                <a href="<?= htmlspecialchars($linkedinUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="LinkedIn">in</a>
            </div>
        </div>

        <div>
            <h3 class="footer-title">Quick Links</h3>
            <ul class="footer-links">
                <li><a href="/" class="footer-link">Home</a></li>
                <li><a href="/departments" class="footer-link">All Departments</a></li>
                <li><a href="/lectures" class="footer-link">Online Lectures</a></li>
                <li><a href="/roadmaps" class="footer-link">Degree Roadmaps</a></li>
                <li><a href="/fee-structure" class="footer-link">Fee Structure</a></li>
                <li><a href="/scholarships" class="footer-link">Scholarships</a></li>
                <li><a href="/about-lgu" class="footer-link">About LGU</a></li>
                <li><a href="/about-us" class="footer-link">About This Platform</a></li>
                <li><a href="/contact-us" class="footer-link">Contact &amp; Submit a Paper</a></li>
            </ul>
        </div>

        <div>
            <h3 class="footer-title">Departments</h3>
            <ul class="footer-links">
                <?php foreach ($footerDepartments as $dept): ?>
                    <li>
                        <a href="/department/<?= htmlspecialchars($dept['slug'], ENT_QUOTES, 'UTF-8') ?>" class="footer-link">
                            <?= htmlspecialchars($dept['name'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li><a href="/departments" class="footer-link" style="font-weight:700;">View all 15 departments →</a></li>
            </ul>
        </div>

        <div>
            <h3 class="footer-title">Visit &amp; Contact</h3>
            <p style="font-size:0.88rem;color:#c2d0be;margin-bottom:10px;">
                <?= htmlspecialchars($contactAddress, ENT_QUOTES, 'UTF-8') ?>
            </p>
            <p style="font-size:0.88rem;color:#c2d0be;margin-bottom:6px;">
                Email: <?= htmlspecialchars($contactEmail, ENT_QUOTES, 'UTF-8') ?>
            </p>
            <p style="font-size:0.88rem;color:#c2d0be;">
                WhatsApp: +<?= htmlspecialchars($whatsappNumber, ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p><?= htmlspecialchars($copyrightText, ENT_QUOTES, 'UTF-8') ?></p>
            <p style="margin-top:6px;font-size:0.75rem;opacity:0.75;">
                Independent student resource — not an official Lahore Garrison University platform.
            </p>
        </div>
    </div>
</footer>

<a href="https://wa.me/<?= htmlspecialchars($whatsappNumber, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="wa-widget" aria-label="Chat on WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg"
        width="30"
        height="30"
        viewBox="0 0 24 24"
        fill="white">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51-.173-.008-.372-.01-.57-.01-.198 0-.52.075-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
        <path d="M20.52 3.449A11.815 11.815 0 0 0 12.04 0C5.495 0 .163 5.332.16 11.877c0 2.093.546 4.136 1.584 5.938L.057 24l6.318-1.656a11.86 11.86 0 0 0 5.66 1.441h.005c6.542 0 11.875-5.332 11.878-11.877a11.815 11.815 0 0 0-3.398-8.459zm-8.48 18.324h-.004a9.85 9.85 0 0 1-5.023-1.375l-.36-.214-3.748.983 1.001-3.653-.235-.375a9.86 9.86 0 0 1-1.511-5.262C2.163 6.49 6.52 2.133 12.04 2.133a9.8 9.8 0 0 1 6.98 2.895 9.8 9.8 0 0 1 2.892 6.985c-.003 5.52-4.36 9.76-9.872 9.76z" />
    </svg>
</a>