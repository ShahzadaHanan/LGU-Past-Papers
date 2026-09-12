<?php
$contactAddress = $siteSettings['contact_address'] ?? 'Sector C, Phase VI, DHA, Lahore, Punjab, Pakistan';
$contactEmail = $siteSettings['contact_email'] ?? 'info@lgu.edu.pk';
$contactPhone = $siteSettings['contact_phone'] ?? null;
?>
<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'Contact Us', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>Contact Us &amp; Submit a Paper</h1>
        <p>Have a question, or a past paper other students would find useful? Send it our way.</p>
    </div>
</section>

<main class="container section">
    <div style="display:flex; gap:32px; flex-wrap:wrap;">
        <div style="flex:1 1 300px; display:flex; flex-direction:column; gap:24px;">
            <div style="background-color:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:26px;">
                <h3 style="font-size:1.15rem; color:var(--primary-color); margin-bottom:14px;">Contact Info</h3>
                <p style="margin-bottom:10px; font-size:0.92rem;">
                    <strong>Campus:</strong> <?= htmlspecialchars($contactAddress, ENT_QUOTES, 'UTF-8') ?>
                </p>
                <p style="margin-bottom:10px; font-size:0.92rem;">
                    <strong>Email:</strong> <?= htmlspecialchars($contactEmail, ENT_QUOTES, 'UTF-8') ?>
                </p>
                <?php if ($contactPhone): ?>
                    <p style="font-size:0.92rem;">
                        <strong>Phone:</strong> <?= htmlspecialchars($contactPhone, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>
            </div>

            <div style="background-color:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:26px;">
                <h3 style="font-size:1.15rem; color:var(--primary-color); margin-bottom:14px;">Submission Guidelines</h3>
                <ul style="padding-left:18px; font-size:0.9rem; color:var(--text-muted); display:flex; flex-direction:column; gap:10px;">
                    <li>Make sure the paper image is clear and fully readable.</li>
                    <li>Use the exact course/subject name (e.g. "Data Structures", not "DS").</li>
                    <li>Mention the session (e.g. Fall 2024) and exam type in the details field.</li>
                    <li>Submissions are reviewed by moderators before publishing.</li>
                </ul>
            </div>

            <?php if (!empty($departments)): ?>
                <div style="background-color:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:26px;">
                    <h3 style="font-size:1.15rem; color:var(--primary-color); margin-bottom:14px;">Looking for a department?</h3>
                    <a href="/departments" class="btn btn-secondary btn-block">Browse All Departments</a>
                </div>
            <?php endif; ?>
        </div>

        <div style="flex:2 1 500px; background-color:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:36px;">
            <h3 style="font-size:1.4rem; color:var(--primary-color); margin-bottom:8px;">Contribute a Past Paper</h3>
            <p style="color:var(--text-muted); margin-bottom:26px;">Upload a copy of your exam paper to the moderation queue.</p>

            <?php if ($msg = $session->flash('success')): ?>
                <div class="alert alert-success"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <?php if ($err = $session->flash('error')): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form action="/paper/submit" method="POST" enctype="multipart/form-data">
                <?= \App\Helpers\Form::csrf($csrf) ?>
                <div class="form-grid form-grid--2">
                    <div class="form-field">
                        <label>Your Name *</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-field">
                        <label>Your Email *</label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                <div class="form-grid form-grid--2">
                    <div class="form-field">
                        <label>WhatsApp Contact *</label>
                        <input type="text" name="whatsapp" placeholder="e.g. 923xxxxxxxxx" required>
                    </div>
                    <div class="form-field">
                        <label>Subject / Course Name *</label>
                        <input type="text" name="subject" placeholder="e.g. Data Structures" required>
                    </div>
                </div>
                <div class="form-field">
                    <label>Paper Snapshot (image only) *</label>
                    <input type="file" name="paper_image" accept="image/*" required>
                </div>
                <div class="form-field">
                    <label>Details (session, exam type, degree) *</label>
                    <textarea name="description" rows="3" placeholder="e.g. Fall 2024, Mid-Term, BSCS 3rd semester" required></textarea>
                </div>
                <button type="submit" class="btn btn-block">Submit to Moderator</button>
            </form>
        </div>
    </div>
</main>
