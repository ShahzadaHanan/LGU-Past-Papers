<?php
$whatsappNumber = $siteSettings['whatsapp_number'] ?? '923000000000';

$currentPath = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

/** Marks a nav link "active" (current page) — exact match, or any of the
 * given prefixes (e.g. Departments stays active on every /department/*
 * detail page, not just the /departments hub itself). */
$navActive = function (array $paths, array $prefixes = []) use ($currentPath): string {
    if (in_array($currentPath, $paths, true)) {
        return ' active';
    }
    foreach ($prefixes as $prefix) {
        if (str_starts_with($currentPath, $prefix)) {
            return ' active';
        }
    }
    return '';
};
$calculatorPaths = ['/lgu-sgpa-calculator', '/lgu-cgpa-calculator'];
?>
<header class="site-header">
    <div class="container nav-container">
        <a href="/" class="logo">
            <img src="<?= asset('images/brand/lgu-crest.png') ?>" alt="Lahore Garrison University crest">
            <span class="logo-text">
                <strong>LGU Hub</strong>
                <span>Past Papers &amp; Lectures</span>
            </span>
        </a>

        <nav>
            <ul class="nav-menu">
                <li><a href="/" class="nav-link<?= $navActive(['/']) ?>">Home</a></li>
                <li class="dropdown">
                    <a href="/departments" class="nav-link<?= $navActive(['/departments'], ['/department/']) ?>">Departments ▾</a>
                    <div class="dropdown-menu">
                        <?php foreach ($navDepartments as $dept): ?>
                            <a href="/department/<?= htmlspecialchars($dept['slug'], ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item">
                                <?= htmlspecialchars($dept['name'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        <?php endforeach; ?>
                        <a href="/departments" class="dropdown-item" style="font-weight:700;color:var(--primary-color);">View all departments →</a>
                    </div>
                </li>
                <li><a href="/lectures" class="nav-link<?= $navActive(['/lectures']) ?>">Lectures</a></li>
                <li class="dropdown">
                    <a href="/lgu-cgpa-calculator" class="nav-link<?= $navActive($calculatorPaths) ?>">Calculators ▾</a>
                    <div class="dropdown-menu">
                        <a href="/lgu-sgpa-calculator" class="dropdown-item">SGPA Calculator</a>
                        <a href="/lgu-cgpa-calculator" class="dropdown-item">CGPA Calculator</a>
                    </div>
                </li>
                <li><a href="/about-lgu" class="nav-link<?= $navActive(['/about-lgu']) ?>">About LGU</a></li>
                <li><a href="/alumni" class="nav-link<?= $navActive(['/alumni']) ?>">Alumni</a></li>
                <li><a href="/contact-us" class="nav-link<?= $navActive(['/contact-us']) ?>">Contact</a></li>
            </ul>
        </nav>

        <div class="right-controls">
            <a href="/lectures/book" class="header-book" aria-label="Book an online class">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <path d="M9 16l2 2 4-4"></path>
                </svg>
            </a>
            <a href="https://wa.me/<?= htmlspecialchars($whatsappNumber, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="header-whatsapp" aria-label="Chat on WhatsApp">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="white">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51-.173-.008-.372-.01-.57-.01-.198 0-.52.075-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                    <path d="M20.52 3.449A11.815 11.815 0 0 0 12.04 0C5.495 0 .163 5.332.16 11.877c0 2.093.546 4.136 1.584 5.938L.057 24l6.318-1.656a11.86 11.86 0 0 0 5.66 1.441h.005c6.542 0 11.875-5.332 11.878-11.877a11.815 11.815 0 0 0-3.398-8.459zm-8.48 18.324h-.004a9.85 9.85 0 0 1-5.023-1.375l-.36-.214-3.748.983 1.001-3.653-.235-.375a9.86 9.86 0 0 1-1.511-5.262C2.163 6.49 6.52 2.133 12.04 2.133a9.8 9.8 0 0 1 6.98 2.895 9.8 9.8 0 0 1 2.892 6.985c-.003 5.52-4.36 9.76-9.872 9.76z" />
                    </svg>
            </a>
            <button class="theme-btn" data-theme-toggle aria-label="Toggle theme">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>

            <?php $googleClientId = env('GOOGLE_CLIENT_ID', ''); ?>

            <?php if (!empty($currentSiteUser)): ?>
                <div class="dropdown header-account">
                    <a href="#" class="header-account__toggle" aria-label="Account menu">
                        <?php if (!empty($currentSiteUser['profile_image'])): ?>
                            <img src="<?= htmlspecialchars($currentSiteUser['profile_image'], ENT_QUOTES, 'UTF-8') ?>" alt="" class="header-account__avatar">
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        <?php endif; ?>
                        <span class="header-account__name"><?= htmlspecialchars($currentSiteUser['name'], ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="/account/logout" class="dropdown-item">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="dropdown header-account">
                    <a href="#" class="header-account__toggle" aria-label="Login or sign up">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Login</span>
                    </a>

                    <div class="dropdown-menu header-auth">
                        <div class="header-auth__tabs">
                            <button type="button" class="header-auth__tab active" data-auth-tab="login">Login</button>
                            <button type="button" class="header-auth__tab" data-auth-tab="signup">Sign Up</button>
                        </div>

                        <form method="POST" action="/account/login" class="header-auth__panel" data-auth-panel="login">
                            <?= \App\Helpers\Form::csrf($csrf) ?>
                            <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($currentPath, ENT_QUOTES, 'UTF-8') ?>">

                            <label for="header-login-email">Email</label>
                            <input id="header-login-email" type="email" name="email" placeholder="you@example.com" required>

                            <label for="header-login-password">Password</label>
                            <input id="header-login-password" type="password" name="password" placeholder="Password" required>

                            <button type="submit" class="btn btn-accent">Login</button>

                            <?php if ($googleClientId !== ''): ?>
                                <div class="header-auth__divider"><span>or</span></div>
                                <div class="header-auth__google" data-google-signin
                                     data-client-id="<?= htmlspecialchars($googleClientId, ENT_QUOTES, 'UTF-8') ?>"
                                     data-csrf="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>"
                                     data-redirect="<?= htmlspecialchars($currentPath, ENT_QUOTES, 'UTF-8') ?>"></div>
                            <?php endif; ?>
                        </form>

                        <form method="POST" action="/account/register" enctype="multipart/form-data" class="header-auth__panel" data-auth-panel="signup" hidden>
                            <?= \App\Helpers\Form::csrf($csrf) ?>
                            <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($currentPath, ENT_QUOTES, 'UTF-8') ?>">

                            <label for="header-signup-name">Name</label>
                            <input id="header-signup-name" type="text" name="name" placeholder="Your name" required>

                            <label for="header-signup-email">Email</label>
                            <input id="header-signup-email" type="email" name="email" placeholder="you@example.com" required>

                            <label for="header-signup-password">Password</label>
                            <input id="header-signup-password" type="password" name="password" placeholder="At least 8 characters" minlength="8" required>

                            <label for="header-signup-password-confirm">Retype Password</label>
                            <input id="header-signup-password-confirm" type="password" name="password_confirmation" placeholder="Retype password" minlength="8" required>

                            <label for="header-signup-phone">Phone Number</label>
                            <input id="header-signup-phone" type="tel" name="phone" placeholder="03xx-xxxxxxx" required>

                            <label for="header-signup-avatar">Profile Picture (optional)</label>
                            <input id="header-signup-avatar" type="file" name="profile_image" accept="image/*">

                            <button type="submit" class="btn btn-accent">Sign Up</button>

                            <?php if ($googleClientId !== ''): ?>
                                <div class="header-auth__divider"><span>or</span></div>
                                <div class="header-auth__google" data-google-signin
                                     data-client-id="<?= htmlspecialchars($googleClientId, ENT_QUOTES, 'UTF-8') ?>"
                                     data-csrf="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>"
                                     data-redirect="<?= htmlspecialchars($currentPath, ENT_QUOTES, 'UTF-8') ?>"></div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <button class="nav-toggle" data-nav-toggle aria-label="Open menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
</header>

<div class="mobile-nav" data-mobile-nav>
    <div class="mobile-nav__panel">
        <button class="mobile-nav__close" data-nav-close aria-label="Close menu">&times;</button>
        <ul class="mobile-nav__links">
            <li><a href="/" class="<?= trim($navActive(['/'])) ?>">Home</a></li>
            <li>
                <details class="mobile-nav__group" <?= $navActive(['/departments'], ['/department/']) ? ' open' : '' ?>>
                    <summary class="<?= trim($navActive(['/departments'], ['/department/'])) ?>">Departments</summary>
                    <?php foreach ($navDepartments as $dept): ?>
                        <a href="/department/<?= htmlspecialchars($dept['slug'], ENT_QUOTES, 'UTF-8') ?>" class="<?= trim($navActive(["/department/{$dept['slug']}"], ["/department/{$dept['slug']}/"])) ?>">
                            <?= htmlspecialchars($dept['name'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    <?php endforeach; ?>
                </details>
            </li>
            <li><a href="/lectures" class="<?= trim($navActive(['/lectures'])) ?>">Lectures</a></li>
            <li>
                <details class="mobile-nav__group" <?= $navActive($calculatorPaths) ? ' open' : '' ?>>
                    <summary class="<?= trim($navActive($calculatorPaths)) ?>">Calculators</summary>
                    <a href="/lgu-sgpa-calculator" class="<?= trim($navActive(['/lgu-sgpa-calculator'])) ?>">SGPA Calculator</a>
                    <a href="/lgu-cgpa-calculator" class="<?= trim($navActive(['/lgu-cgpa-calculator'])) ?>">CGPA Calculator</a>
                </details>
            </li>
            <li><a href="/about-lgu" class="<?= trim($navActive(['/about-lgu'])) ?>">About LGU</a></li>
            <li><a href="/alumni" class="<?= trim($navActive(['/alumni'])) ?>">Alumni</a></li>
            <li><a href="/about-us" class="<?= trim($navActive(['/about-us'])) ?>">About Us</a></li>
            <li><a href="/contact-us" class="<?= trim($navActive(['/contact-us'])) ?>">Contact Us</a></li>
            <?php if (!empty($currentSiteUser)): ?>
                <li><a href="/account/logout">Logout (<?= htmlspecialchars($currentSiteUser['name'], ENT_QUOTES, 'UTF-8') ?>)</a></li>
            <?php else: ?>
                <li><a href="/account/login">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>