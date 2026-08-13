<main class="container">
    <!-- Hero Slider Section -->
    <?php if (!empty($slides)): ?>
        <section class="hero">
            <div class="hero-slider" id="hero-slider">
                <?php foreach ($slides as $index => $slide): ?>
                    <div class="hero-slide" style="background-image: url('<?= htmlspecialchars($slide['image_path']) ?>'); display: <?= $index === 0 ? 'flex' : 'none' ?>;">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <h2 class="hero-title"><?= htmlspecialchars($slide['caption'] ?? 'Academic Resources') ?></h2>
                            <?php if (!empty($slide['link_url'])): ?>
                                <a href="<?= htmlspecialchars($slide['link_url']) ?>" class="btn">Explore Now</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <script>
            // Simple hero slide switcher
            const slides = document.querySelectorAll('.hero-slide');
            let currentSlide = 0;
            if (slides.length > 1) {
                setInterval(() => {
                    slides[currentSlide].style.display = 'none';
                    currentSlide = (currentSlide + 1) % slides.length;
                    slides[currentSlide].style.display = 'flex';
                }, 5000);
            }
        </script>
    <?php endif; ?>

    <!-- University content blocks (Repeatable Heading + Content Blocks builder) -->
    <section class="section">
        <?php foreach ($blocks as $block): ?>
            <div class="content-block" style="margin-bottom: 40px;">
                <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 15px; color: var(--primary-color); border-left: 4px solid var(--primary-light); padding-left: 15px;">
                    <?= htmlspecialchars($block['heading']) ?>
                </h2>
                <div class="card-text" style="font-size: 1.1rem; line-height: 1.7; color: var(--text-color);">
                    <?= $block['body'] // Contains HTML from rich text editor ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Departments Grid Section -->
    <section class="section">
        <h2 style="text-align: center; font-size: 2.2rem; font-weight: 800; margin-bottom: 10px;">Academic Departments</h2>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 40px;">Select your department to browse degrees and past papers</p>
        <div class="grid">
            <?php foreach ($departments as $dept): ?>
                <div class="card">
                    <div class="card-img" style="background-image: url('<?= htmlspecialchars($dept['hero_image'] ?? '/assets/images/default-dept.jpg') ?>');"></div>
                    <div class="card-content">
                        <h3 class="card-title"><?= htmlspecialchars($dept['name']) ?></h3>
                        <p class="card-text" style="margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= strip_tags($dept['description'] ?? '') ?>
                        </p>
                        <a href="/department/<?= htmlspecialchars($dept['slug']) ?>" class="btn" style="width: 100%; text-align: center;">
                            Browse Papers +
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Newsletter Sign up Section -->
    <section class="section" style="background-color: var(--bg-card); border-radius: 16px; border: 1px solid var(--border-color); padding: 50px 30px; text-align: center; margin: 40px 0;">
        <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 15px;">Subscribe for Upload Alerts</h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 30px auto;">
            Get notified immediately when new mid-term or final past papers are uploaded for your degree program.
        </p>
        <?php if ($msg = $session->flash('success')): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                <?= $msg ?>
            </div>
        <?php endif; ?>
        <?php if ($err = $session->flash('error')): ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                <?= $err ?>
            </div>
        <?php endif; ?>
        <form action="/newsletter/subscribe" method="POST" style="display: flex; max-width: 500px; margin: 0 auto; gap: 10px;">
            <input type="email" name="email" placeholder="Enter your university email" required style="flex-grow: 1; padding: 12px 20px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color); font-size: 1rem;">
            <button type="submit" class="btn">Subscribe</button>
        </form>
    </section>
</main>

<!-- First Visit Popup Modal (Paid Online Lectures Alert) -->
<div class="modal" id="lectures-modal">
    <div class="modal-content">
        <button class="modal-close" id="modal-close">&times;</button>
        <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--primary-color); margin-bottom: 15px;">Paid Online Lectures Available!</h3>
        <p style="color: var(--text-muted); margin-bottom: 25px; line-height: 1.6;">
            Need help preparing for exams? Browse our collection of video lectures and book specialized online classes directly with top tutors.
        </p>
        <div style="display: flex; gap: 15px;">
            <a href="/lectures" class="btn" style="flex-grow: 1; text-align: center;">Browse Lectures & Book</a>
            <button class="btn btn-secondary" id="modal-cancel" style="flex-grow: 1;">Maybe Later</button>
        </div>
    </div>
</div>

<script>
    // Show modal once per session
    window.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('lectures-modal');
        const closeBtn = document.getElementById('modal-close');
        const cancelBtn = document.getElementById('modal-cancel');
        
        if (!sessionStorage.getItem('lectures_popup_shown')) {
            setTimeout(() => {
                modal.classList.add('active');
            }, 2000); // Popup after 2s
        }

        const dismissModal = () => {
            modal.classList.remove('active');
            sessionStorage.setItem('lectures_popup_shown', 'true');
        };

        closeBtn.addEventListener('click', dismissModal);
        cancelBtn.addEventListener('click', dismissModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) dismissModal();
        });
    });
</script>