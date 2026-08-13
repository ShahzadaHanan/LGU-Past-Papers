<main class="container section">
    <!-- Breadcrumbs -->
    <nav style="font-size: 0.9rem; margin-bottom: 30px; color: var(--text-muted);">
        <a href="/" style="color: var(--primary-light); text-decoration: none;">Home</a> › 
        <a href="/department/<?= htmlspecialchars($department['slug']) ?>" style="color: var(--primary-light); text-decoration: none;"><?= htmlspecialchars($department['name']) ?></a> › 
        <span><?= htmlspecialchars($degree['name']) ?></span>
    </nav>

    <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--primary-color); margin-bottom: 10px;">
        <?= htmlspecialchars($degree['name']) ?> Past Papers
    </h1>
    <p style="color: var(--text-muted); margin-bottom: 40px;"><?= htmlspecialchars($degree['description'] ?? '') ?></p>

    <!-- Accordion Section for exam types -->
    <div class="accordion">
        <?php 
        $examTypes = [
            'mids' => 'Mid Term Papers',
            'finals' => 'Final Term Papers',
            'summer_mids' => 'Summer Mid Term Papers',
            'summer_finals' => 'Summer Final Term Papers'
        ];
        foreach ($examTypes as $key => $title): 
            $papersList = $groupedPapers[$key] ?? [];
        ?>
            <div class="accordion-item" id="accordion-<?= $key ?>">
                <div class="accordion-header" onclick="toggleAccordion('<?= $key ?>')">
                    <span><?= htmlspecialchars($title) ?> (<?= count($papersList) ?>)</span>
                    <span style="font-size: 1.2rem; font-weight: bold;" id="icon-<?= $key ?>">+</span>
                </div>
                <div class="accordion-content" id="content-<?= $key ?>">
                    <?php if (empty($papersList)): ?>
                        <p style="color: var(--text-muted); font-size: 0.95rem;">No past papers available for this category yet.</p>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                                <thead>
                                    <tr style="border-bottom: 2px solid var(--border-color); color: var(--text-muted); font-weight: 600;">
                                        <th style="padding: 12px 10px;">Paper Name</th>
                                        <th style="padding: 12px 10px;">Session</th>
                                        <th style="padding: 12px 10px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($papersList as $paper): ?>
                                        <tr style="border-bottom: 1px solid var(--border-color);">
                                            <td style="padding: 12px 10px; font-weight: 500;">
                                                <a href="/paper/<?= htmlspecialchars($paper['slug']) ?>" style="color: var(--text-color); text-decoration: none;">
                                                    <?= htmlspecialchars($paper['subject_name']) ?>
                                                </a>
                                            </td>
                                            <td style="padding: 12px 10px;"><?= htmlspecialchars($paper['session']) ?></td>
                                            <td style="padding: 12px 10px;">
                                                <a href="/paper/<?= htmlspecialchars($paper['slug']) ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;">View Paper</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script>
    function toggleAccordion(key) {
        const item = document.getElementById('accordion-' + key);
        const content = document.getElementById('content-' + key);
        const icon = document.getElementById('icon-' + key);
        
        if (item.classList.contains('active')) {
            item.classList.remove('active');
            content.style.display = 'none';
            icon.textContent = '+';
        } else {
            item.classList.add('active');
            content.style.display = 'block';
            icon.textContent = '−';
        }
    }
</script>
