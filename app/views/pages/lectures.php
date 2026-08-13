<main class="container section">
    <h1 style="font-size: 2.5rem; font-weight: 800; text-align: center; margin-bottom: 10px; color: var(--primary-color);">LGU Learning Hub</h1>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 40px;">Access verified online lectures or schedule specialized coaching sessions</p>

    <!-- Category Filter Bar -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin-bottom: 40px;">
        <a href="/lectures" class="btn <?= empty($selectedCategory) ? '' : 'btn-secondary' ?>" style="padding: 8px 16px; font-size: 0.9rem; border-radius: 20px;">All Lectures</a>
        <?php foreach ($categories as $cat): ?>
            <a href="/lectures?category=<?= htmlspecialchars($cat['slug']) ?>" class="btn <?= $selectedCategory === $cat['slug'] ? '' : 'btn-secondary' ?>" style="padding: 8px 16px; font-size: 0.9rem; border-radius: 20px;">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Video grid -->
    <div class="grid" id="lectures-grid">
        <?php if (empty($videos)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
                <p>No video lectures available in this category yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($videos as $video): ?>
                <div class="card">
                    <!-- YouTube parsed thumbnail link -->
                    <div class="card-img" style="background-image: url('https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_video_id']) ?>/hqdefault.jpg'); position: relative;">
                        <!-- Play Icon Overlay -->
                        <a href="<?= htmlspecialchars($video['youtube_url']) ?>" target="_blank" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background-color: rgba(15,23,42,0.2); color: white; text-decoration: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="currentColor" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));">
                                <path d="M8 5v14l11-7z"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="card-content">
                        <span style="font-size: 0.75rem; font-weight: 600; color: var(--primary-light); text-transform: uppercase; letter-spacing: 0.5px;"><?= htmlspecialchars($video['category_name']) ?></span>
                        <h3 class="card-title" style="margin-top: 5px; font-size: 1.1rem; line-height: 1.4;"><?= htmlspecialchars($video['title']) ?></h3>
                        <p class="card-text" style="font-size: 0.85rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-top: 10px;">
                            <?= htmlspecialchars($video['description'] ?? '') ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Paginated See More Button -->
    <?php if ($page < $totalPages): ?>
        <div style="text-align: center; margin-top: 5px; padding: 40px 0;">
            <a href="/lectures?category=<?= htmlspecialchars($selectedCategory) ?>&page=<?= $page + 1 ?>" class="btn btn-secondary" style="border-radius: 8px;">
                See More Lectures
            </a>
        </div>
    <?php endif; ?>

    <!-- Class Booking Form Section -->
    <section class="section" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 50px 30px; margin-top: 60px;">
        <div style="max-width: 700px; margin: 0 auto;">
            <h2 style="font-size: 2rem; font-weight: 800; text-align: center; color: var(--primary-color); margin-bottom: 10px;">Book an Online Class</h2>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 45px;">Request a specialized coaching session or full-semester private tutoring</p>

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

            <form action="/lectures/book" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1 1 300px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">Your Full Name *</label>
                        <input type="text" name="name" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                    </div>
                    <div style="flex: 1 1 300px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">Your Email Address *</label>
                        <input type="email" name="email" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                    </div>
                </div>

                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1 1 300px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">WhatsApp Contact Number *</label>
                        <input type="text" name="whatsapp" placeholder="e.g. 923xxxxxxxxx" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                    </div>
                    <div style="flex: 1 1 300px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">Subject / Course Name *</label>
                        <input type="text" name="subject" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                    </div>
                </div>

                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1 1 300px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">Lecture Topic (Optional)</label>
                        <input type="text" name="topic" style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                    </div>
                    <div style="flex: 1 1 300px; display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 600; font-size: 0.9rem;">Coaching Budget Type *</label>
                        <select name="fee_type" required style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                            <option value="single_lecture">Single Specific Topic / Chapter</option>
                            <option value="full_subject">Complete Semester Course Support</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-weight: 600; font-size: 0.9rem;">Brief details or requirements</label>
                    <textarea name="description" rows="4" style="padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color); resize: vertical;"></textarea>
                </div>

                <button type="submit" class="btn" style="padding: 14px; font-size: 1rem; margin-top: 10px;">Submit Booking Request</button>
            </form>
        </div>
    </section>
</main>
