<?php
/**
 * Visible FAQ list matching a page's FAQPage JSON-LD (Google requires the
 * marked-up questions to be on the page). Expects $faqs: [{q, a}, ...].
 */
?>
<div class="fee-accordion">
    <?php foreach ($faqs as $faq): ?>
        <div class="fee-panel" data-accordion>
            <h3 class="gpa-faq-q">
                <button type="button" class="fee-panel__header" data-accordion-toggle>
                    <span class="fee-panel__title gpa-faq-title"><?= htmlspecialchars($faq['q'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="fee-toggle-icon" aria-hidden="true">+</span>
                </button>
            </h3>
            <div class="fee-panel__panel" data-accordion-panel>
                <div class="fee-panel__inner">
                    <p class="gpa-faq-a"><?= htmlspecialchars($faq['a'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
