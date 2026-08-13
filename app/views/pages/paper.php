<main class="container section">
    <!-- Breadcrumbs & JSON-LD Structured Schema -->
    <nav style="font-size: 0.9rem; margin-bottom: 30px; color: var(--text-muted);">
        <a href="/" style="color: var(--primary-light); text-decoration: none;">Home</a> › 
        <a href="/department/<?= htmlspecialchars($department['slug']) ?>" style="color: var(--primary-light); text-decoration: none;"><?= htmlspecialchars($department['name']) ?></a> › 
        <a href="/department/<?= htmlspecialchars($department['slug']) ?>/<?= htmlspecialchars($degree['slug']) ?>" style="color: var(--primary-light); text-decoration: none;"><?= htmlspecialchars($degree['name']) ?></a> › 
        <span><?= htmlspecialchars($paper['subject_name']) ?> (<?= htmlspecialchars($paper['session']) ?>)</span>
    </nav>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "<?= env('APP_URL') ?>/"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "<?= htmlspecialchars($department['name']) ?>",
        "item": "<?= env('APP_URL') ?>/department/<?= htmlspecialchars($department['slug']) ?>"
      },{
        "@type": "ListItem",
        "position": 3,
        "name": "<?= htmlspecialchars($degree['name']) ?>",
        "item": "<?= env('APP_URL') ?>/department/<?= htmlspecialchars($department['slug']) ?>/<?= htmlspecialchars($degree['slug']) ?>"
      },{
        "@type": "ListItem",
        "position": 4,
        "name": "<?= htmlspecialchars($paper['subject_name']) ?>",
        "item": "<?= env('APP_URL') ?>/paper/<?= htmlspecialchars($paper['slug']) ?>"
      }]
    }
    </script>

    <div style="margin-bottom: 40px;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--primary-color); margin-bottom: 10px;">
            <?= htmlspecialchars($paper['subject_name']) ?> (<?= htmlspecialchars($paper['session']) ?>) - <?= strtoupper(str_replace('_', ' ', $paper['exam_type'])) ?>
        </h1>
        <p style="color: var(--text-muted);">Views: <?= (int)$paper['views_count'] ?> | Uploaded on LGU Past Papers Portal</p>
    </div>

    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        <!-- Paper Preview & Lightbox -->
        <div style="flex: 2 1 600px;">
            <div style="border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; background-color: var(--bg-card); padding: 15px; position: relative;">
                <img id="paper-image" src="<?= htmlspecialchars($paper['paper_image']) ?>" alt="<?= htmlspecialchars($paper['subject_name']) ?> Past Paper" style="width: 100%; height: auto; display: block; border-radius: 8px; cursor: zoom-in;" onclick="openLightbox()">
                <div style="text-align: center; margin-top: 15px;">
                    <a href="<?= htmlspecialchars($paper['download_file'] ?? $paper['paper_image']) ?>" download class="btn" style="display: inline-flex; align-items: center; gap: 10px;">
                        Download Past Paper
                    </a>
                </div>
            </div>
        </div>

        <!-- Details & Solution & Related Papers Sidebar -->
        <div style="flex: 1 1 300px; display: flex; flex-direction: column; gap: 30px;">
            <?php if (!empty($paper['solution_image'])): ?>
                <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px;">
                    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 15px; color: var(--primary-color);">Solution Available</h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">An admin-verified solution sheet has been uploaded for this past paper.</p>
                    <a href="<?= htmlspecialchars($paper['solution_image']) ?>" target="_blank" class="btn btn-secondary" style="width: 100%; text-align: center; display: block;">View Solution Sheet</a>
                </div>
            <?php endif; ?>

            <!-- Related Papers -->
            <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px;">
                <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 15px; color: var(--primary-color);">Related Papers</h3>
                <?php if (empty($related)): ?>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">No related papers found for this semester.</p>
                <?php else: ?>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach ($related as $rel): ?>
                            <li>
                                <a href="/paper/<?= htmlspecialchars($rel['slug']) ?>" style="color: var(--text-color); text-decoration: none; font-size: 0.95rem; font-weight: 500; display: block;">
                                    <?= htmlspecialchars($rel['subject_name']) ?> (<?= htmlspecialchars($rel['session']) ?>)
                                </a>
                                <span style="font-size: 0.8rem; color: var(--text-muted);"><?= strtoupper(str_replace('_', ' ', $rel['exam_type'])) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Lightbox Zoom Overlay -->
<div id="lightbox" style="position: fixed; inset: 0; background-color: rgba(15,23,42,0.9); z-index: 2000; display: none; align-items: center; justify-content: center; padding: 20px; cursor: zoom-out;" onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="Lightbox View" style="max-width: 100%; max-height: 90%; border-radius: 4px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
    <span style="position: absolute; top: 20px; right: 30px; font-size: 3rem; color: white; cursor: pointer; font-weight: bold;">&times;</span>
</div>

<script>
    function openLightbox() {
        const paperImg = document.getElementById('paper-image').src;
        document.getElementById('lightbox-img').src = paperImg;
        document.getElementById('lightbox').style.display = 'flex';
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }
</script>
