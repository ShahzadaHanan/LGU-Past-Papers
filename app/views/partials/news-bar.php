<?php if (!empty($announcements)): ?>
<div class="news-bar" data-news-bar>
    <div class="container news-bar__inner">
        <span class="news-bar__tag">News</span>
        <div class="news-bar__track">
            <?php foreach ($announcements as $i => $item): ?>
                <div class="news-bar__item <?= $i === 0 ? 'active' : '' ?>">
                    <?php if (!empty($item['link_url'])): ?>
                        <a href="<?= htmlspecialchars($item['link_url'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($item['message'], ENT_QUOTES, 'UTF-8') ?>
                            <?php if (!empty($item['link_label'])): ?>
                                <span class="news-bar__cta"><?= htmlspecialchars($item['link_label'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                        </a>
                    <?php else: ?>
                        <span><?= htmlspecialchars($item['message'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- <button class="news-bar__close" data-news-bar-close aria-label="Dismiss">&times;</button> -->
    </div>
</div>
<?php endif; ?>
