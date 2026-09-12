<section class="hero">
    <div class="container hero__inner">
        <div class="hero__content">
            <span class="hero__eyebrow">🎓 Lahore Garrison University</span>
            <h1>Past papers, lectures &amp; class bookings — for LGU students, by LGU students.</h1>
            <p class="lead">
                Every department. Every degree. Every exam. Find mid-term and final-term
                past papers, watch course-mapped lectures, and book a live class with a
                tutor who already knows your syllabus.
            </p>

            <form class="hero__search" action="/departments" method="GET">
                <input type="text" name="q" placeholder="Search your department, e.g. Computer Science">
                <button type="submit" class="btn btn-accent btn-sm">Find</button>
            </form>

            <div class="hero__ctas" style="margin-top:22px;">
                <a href="/departments" class="btn btn-accent">Browse Departments</a>
                <a href="/lectures" class="btn btn-on-dark">Watch Lectures</a>
            </div>
        </div>
    </div>
</section>

<div class="modal" id="lectures-modal">
    <div class="modal-content">
        <button class="modal-close" data-modal-close aria-label="Close">&times;</button>
        <h3 style="font-size:1.4rem;color:var(--primary-color);margin-bottom:14px;">Paid online classes are open</h3>
        <p style="color:var(--text-muted);margin-bottom:22px;line-height:1.6;">
            Need extra help before your exam? Browse recorded lectures or book a live
            one-on-one session with a tutor who knows your exact course.
        </p>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a href="/lectures" class="btn" style="flex:1;text-align:center;">Browse Lectures</a>
            <button class="btn btn-secondary" data-modal-close style="flex:1;">Maybe later</button>
        </div>
    </div>
</div>

<script>
(function () {
    var modal = document.getElementById('lectures-modal');
    if (!modal || sessionStorage.getItem('lectures_popup_shown')) return;
    // This block sits right after the hero on purpose — parked at the
    // bottom of the page it had to wait for the whole department grid to
    // parse first. Placed here, the timer starts as soon as the browser
    // reaches this point, not after the rest of the page. Short delay only
    // so it doesn't slam in before the page has even painted — not a
    // "wait until the visitor is done looking" delay.
    setTimeout(function () { modal.classList.add('active'); }, 600);
    modal.querySelectorAll('[data-modal-close]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            sessionStorage.setItem('lectures_popup_shown', 'true');
        });
    });
})();
</script>

<div class="container">
    <div class="stats-strip">
        <div class="stat-card">
            <strong data-count-to="<?= (int) $stats['departments'] ?>">0</strong>
            <span>Departments</span>
        </div>
        <div class="stat-card">
            <strong data-count-to="<?= (int) $stats['degrees'] ?>">0</strong>
            <span>Degree Programs</span>
        </div>
        <div class="stat-card">
            <strong data-count-to="<?= (int) $stats['papers'] ?>" data-count-suffix="+">0</strong>
            <span>Past Papers</span>
        </div>
        <div class="stat-card">
            <strong data-count-to="<?= (int) $stats['lectures'] ?>" data-count-suffix="+">0</strong>
            <span>Video Lectures</span>
        </div>
    </div>
</div>

<?php if (!empty($slides)): ?>
<section class="section container">
    <div class="hero" style="border-radius: var(--radius-lg); overflow:hidden; position:relative; height:320px;">
        <div data-hero-slider style="display:flex; height:100%; width:100%; transition: transform 0.6s ease;">
            <?php foreach ($slides as $slide): ?>
                <div class="hero-slide" style="min-width:100%; height:100%; background-image:url('<?= htmlspecialchars($slide['image_path'], ENT_QUOTES, 'UTF-8') ?>'); background-size:cover; background-position:center; display:flex; align-items:flex-end; padding: 28px;">
                    <div style="background:linear-gradient(to top, rgba(8,42,1,0.85), transparent); position:absolute; inset:0;"></div>
                    <div style="position:relative; color:#fff; z-index:2;">
                        <h3 style="font-size:1.4rem; margin-bottom:8px;"><?= htmlspecialchars($slide['caption'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if (!empty($slide['link_url'])): ?>
                            <a href="<?= htmlspecialchars($slide['link_url'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-accent btn-sm">Explore</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($blocks)): ?>
<section class="section container">
    <?php foreach ($blocks as $block): ?>
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.7rem; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px; margin-bottom:14px;">
                <?= htmlspecialchars($block['heading'], ENT_QUOTES, 'UTF-8') ?>
            </h2>
            <div class="card-text" style="font-size:1.02rem; line-height:1.75;">
                <?= $block['body'] /* trusted admin-authored rich text */ ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>

<section class="section container">
    <div class="section-head">
        <span class="eyebrow">Academic Departments</span>
        <h2>Select your department to get started</h2>
        <p>Browse past papers and lectures organised the way you actually search for them.</p>
    </div>
    <div class="grid dept-grid" id="dept-grid">
        <?php foreach ($departments as $dept): ?>
            <div class="card">
                <?php if (!empty($dept['hero_image'])): ?>
                    <div class="card-img" style="background-image:url('<?= htmlspecialchars($dept['hero_image'], ENT_QUOTES, 'UTF-8') ?>');"></div>
                <?php else: ?>
                    <div class="card-img" style="background:linear-gradient(135deg, var(--primary-color), var(--primary-dark)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:1.4rem;">
                        <?= htmlspecialchars(mb_substr($dept['name'], 0, 2), ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                <div class="card-content">
                    <h3 class="card-title"><?= htmlspecialchars($dept['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="card-text">
                        <?= htmlspecialchars(mb_strimwidth(strip_tags($dept['description'] ?? ''), 0, 110, '…'), ENT_QUOTES, 'UTF-8') ?>
                    </p>
                    <a href="/department/<?= htmlspecialchars($dept['slug'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-block">
                        Browse Courses
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (count($departments) > 4): ?>
        <div class="show-more-wrap">
            <button type="button" class="btn btn-secondary" data-show-more="dept-grid">Show More Departments</button>
        </div>
    <?php endif; ?>
</section>

<section class="section container">
    <div class="cta-band">
        <h2>Never miss a new past paper</h2>
        <p>Get a quick alert whenever new mid-term or final-term papers go up for your degree.</p>

        <?php if ($msg = $session->flash('success')): ?>
            <div class="alert alert-success" style="max-width:480px;margin:0 auto 16px;"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($err = $session->flash('error')): ?>
            <div class="alert alert-danger" style="max-width:480px;margin:0 auto 16px;"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form action="/newsletter/subscribe" method="POST">
            <?= \App\Helpers\Form::csrf($csrf) ?>
            <input type="email" name="email" placeholder="Enter your university email" required>
            <button type="submit" class="btn btn-accent">Subscribe</button>
        </form>
    </div>
</section>

<?php if (!empty($testimonials)): ?>
<section class="section testimonial-carousel-section">
    <div class="container">
        <h2 class="section-title">What Garrisonians Say</h2>
        <p class="section-subtitle">Hear from LGU students and alumni about their time on campus. See the full <a href="/alumni">alumni network</a>.</p>
    </div>
    <div class="testimonial-carousel">
        <div class="testimonial-carousel__track">
            <?php
                $renderTestimonial = function (array $t) {
                    ?>
                    <div class="testimonial-carousel__card">
                        <?php if (!empty($t['photo'])): ?>
                            <img src="<?= htmlspecialchars($t['photo'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') ?>" class="testimonial-carousel__avatar">
                        <?php else: ?>
                            <div class="testimonial-carousel__avatar testimonial-carousel__avatar--placeholder"><?= htmlspecialchars(mb_substr($t['name'], 0, 1), ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                        <p class="testimonial-carousel__quote">"<?= htmlspecialchars($t['quote'], ENT_QUOTES, 'UTF-8') ?>"</p>
                        <strong class="testimonial-carousel__name"><?= htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <?php if (!empty($t['degree'])): ?>
                            <span class="testimonial-carousel__degree"><?= htmlspecialchars($t['degree'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </div>
                    <?php
                };
            ?>
            <?php foreach ($testimonials as $t): $renderTestimonial($t); ?><?php endforeach; ?>
            <?php // Duplicate the track once so the CSS marquee can loop seamlessly from -50% back to 0. ?>
            <?php foreach ($testimonials as $t): $renderTestimonial($t); ?><?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
