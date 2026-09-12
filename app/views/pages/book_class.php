<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'Lectures', 'url' => '/lectures'], ['label' => 'Book an Online Class', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>Book an Online Class</h1>
        <p>One-on-one live coaching with LGU-experienced tutors, matched to your exact subject and semester.</p>
    </div>
</section>

<main class="container section">
    <div class="book-class-intro">
        <p>
            Recorded lectures on the <a href="/lectures">Lectures</a> page cover the basics, but some topics need a
            tutor who can slow down, answer questions live, and walk through a past paper with you step by step.
            That's what live class booking is for: a private, one-on-one online session with a tutor who has
            studied the same LGU course you're taking, scheduled around your availability, over a video call
            rather than a pre-recorded video.
        </p>
        <p>
            Every request below is reviewed individually — tell us the subject, the specific topic if you already
            know it, and whether you want a single focused session or ongoing support for the whole semester, and
            a tutor will reach out over WhatsApp or email to confirm timing and pricing before anything is charged.
        </p>
    </div>

    <div class="book-class-table-wrap">
        <table class="fee-table book-class-table">
            <thead>
                <tr><th>Coaching Type</th><th>What's Included</th><th>Best For</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fee-cell--bold">Single Topic / Chapter</td>
                    <td>One focused live session covering a specific topic, chapter, or past-paper question you're stuck on.</td>
                    <td>Quick concept clarification before a mid-term or final.</td>
                </tr>
                <tr>
                    <td class="fee-cell--bold">Complete Semester Support</td>
                    <td>Ongoing weekly sessions covering the full subject from start to finish, aligned to your class schedule.</td>
                    <td>Students who want consistent, structured support across the whole semester.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <section class="section book-class-form-section" id="book-a-class">
        <div style="max-width:680px; margin:0 auto;">
            <div class="section-head">
                <span class="eyebrow">Live Classes</span>
                <h2>Request Your Session</h2>
                <p>Fill in the details below — we'll match you with a tutor.</p>
            </div>

            <?php if ($msg = $session->flash('success')): ?>
                <div class="alert alert-success"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <?php if ($err = $session->flash('error')): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form action="/lectures/book" method="POST">
                <?= \App\Helpers\Form::csrf($csrf) ?>
                <div class="form-grid form-grid--2">
                    <div class="form-field">
                        <label>Your Full Name *</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-field">
                        <label>Your Email Address *</label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                <div class="form-grid form-grid--2">
                    <div class="form-field">
                        <label>WhatsApp Contact Number *</label>
                        <input type="text" name="whatsapp" placeholder="e.g. 923xxxxxxxxx" required>
                    </div>
                    <div class="form-field">
                        <label>Subject / Course Name *</label>
                        <input type="text" name="subject" required>
                    </div>
                </div>
                <div class="form-grid form-grid--2">
                    <div class="form-field">
                        <label>Lecture Topic (optional)</label>
                        <input type="text" name="topic">
                    </div>
                    <div class="form-field">
                        <label>Coaching Type *</label>
                        <select name="fee_type" required>
                            <option value="single_lecture">Single Topic / Chapter</option>
                            <option value="full_subject">Complete Semester Support</option>
                        </select>
                    </div>
                </div>
                <div class="form-field">
                    <label>Brief details or requirements</label>
                    <textarea name="description" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-block">Submit Booking Request</button>
            </form>
        </div>
    </section>
</main>
