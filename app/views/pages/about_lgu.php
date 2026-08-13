<main class="container section">
    <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--primary-color); margin-bottom: 30px; text-align: center;">
        About Lahore Garrison University
    </h1>
    
    <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 40px;">
        <?php if (empty($blocks)): ?>
            <div style="text-align: center; color: var(--text-muted);">
                <p>Lahore Garrison University (LGU) is a premium seat of learning in Lahore, dedicated to offering quality education in diverse domains including Computer Science, Software Engineering, Management Sciences, Social Sciences, and more.</p>
                <p style="margin-top: 15px;">Information blocks are configured dynamically via the LGU Admin dashboard content blocks section.</p>
            </div>
        <?php else: ?>
            <?php foreach ($blocks as $block): ?>
                <div class="content-block">
                    <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 15px; color: var(--primary-color); border-left: 4px solid var(--primary-light); padding-left: 15px;">
                        <?= htmlspecialchars($block['heading']) ?>
                    </h2>
                    <div style="font-size: 1.05rem; line-height: 1.7; color: var(--text-color);">
                        <?= $block['body'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
