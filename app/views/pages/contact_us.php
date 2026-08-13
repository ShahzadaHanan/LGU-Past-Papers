<main class="container section">
    <h1 style="font-size: 2.5rem; font-weight: 800; text-align: center; color: var(--primary-color); margin-bottom: 10px;">Contact Us & Share Resources</h1>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 50px;">Have questions? Or want to submit a past exam paper to help fellow students?</p>

    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        <!-- Left Side: Contact Information & Guidelines -->
        <div style="flex: 1 1 300px; display: flex; flex-direction: column; gap: 30px;">
            <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 30px;">
                <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--primary-color); margin-bottom: 15px;">Contact Info</h3>
                <p style="margin-bottom: 12px; font-size: 0.95rem;">
                    <strong>Office Address:</strong> Sector C, Phase VI, DHA, Lahore, Punjab, Pakistan.
                </p>
                <p style="margin-bottom: 12px; font-size: 0.95rem;">
                    <strong>Support Email:</strong> info@lgu.edu.pk
                </p>
                <p style="font-size: 0.95rem;">
                    <strong>WhatsApp Helpline:</strong> Available 24/7 on click widget.
                </p>
            </div>

            <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 30px;">
                <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--primary-color); margin-bottom: 15px;">Submission Guidelines</h3>
                <ul style="padding-left: 20px; font-size: 0.9rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 10px;">
                    <li>Ensure the uploaded exam paper image is clearly readable.</li>
                    <li>Specify the exact course subject name.</li>
                    <li>Double-check you choose the correct session (e.g. FA23) and exam type (Mids/Finals).</li>
                    <li>Submissions are reviewed by administrators before being published publicly.</li>
                </ul>
            </div>
        </div>

        <!-- Right Side: Submit a Paper Form -->
        <div style="flex: 2 1 500px; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 40px;">
            <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--primary-color); margin-bottom: 10px;">Contribute past papers</h3>
            <p style="color: var(--text-muted); margin-bottom: 30px;">Upload a copy of your exam paper to the moderation queue.</p>

            <?php if ($msg = $session->flash('success')): ?>
                <div style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 25px; font-weight: 600;">
                    <?= $msg ?>
                </div>
            <?php endif; ?>
            <?php if ($err = $session->flash('error')): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 25px; font-weight: 600;">
                    <?= $err ?>
                </div>
            <?php endif; ?>

            <form action="/paper/submit" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1 1 200px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">Your Name *</label>
                        <input type="text" name="name" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                    </div>
                    <div style="flex: 1 1 200px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">Your Email *</label>
                        <input type="email" name="email" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                    </div>
                </div>

                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1 1 200px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">WhatsApp Contact *</label>
                        <input type="text" name="whatsapp" placeholder="e.g. 923xxxxxxxxx" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                    </div>
                    <div style="flex: 1 1 200px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">Subject / Course Name *</label>
                        <input type="text" name="subject" placeholder="e.g. Data Structures" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-weight: 600; font-size: 0.9rem;">Paper Snapshot Upload (Image only) *</label>
                    <input type="file" name="paper_image" accept="image/*" required style="padding: 8px; border: 1px dashed var(--border-color); border-radius: 8px; background-color: var(--bg-color); cursor: pointer;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-weight: 600; font-size: 0.9rem;">Details (e.g. Session fall 2023, Mid term, Department) *</label>
                    <textarea name="description" rows="3" placeholder="Describe the paper session, degree, and other helpful context" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color); resize: vertical;"></textarea>
                </div>

                <button type="submit" class="btn" style="padding: 14px; font-size: 1rem; margin-top: 10px;">Submit to Moderator</button>
            </form>
        </div>
    </div>
</main>
