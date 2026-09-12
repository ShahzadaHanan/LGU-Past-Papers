<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'Fee Structure', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>LGU Fee Structure — All Programs</h1>
        <p>Complete, verified fee breakdown for Graduation, M.Phil/MS &amp; PhD, and BTEC HND programs — by faculty, exactly as published on the official LGU site.</p>
    </div>
</section>

<main class="container section">
    <div class="alert alert-success" style="max-width:780px;">
        Figures below are transcribed from LGU's own official fee structure page and are current as of the
        fiscal years noted per program level. Fees are revised periodically — always confirm on
        <a href="https://lgu.edu.pk/fee-structure/" target="_blank" rel="noopener"><strong>lgu.edu.pk/fee-structure</strong></a>
        before making a payment decision.
    </div>

    <div class="fee-accordion">
        <?php foreach ($panels as $panel): ?>
            <div class="fee-panel" data-accordion>
                <button type="button" class="fee-panel__header" data-accordion-toggle>
                    <span class="fee-panel__title"><?= htmlspecialchars($panel['title'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="fee-toggle-icon">+</span>
                </button>
                <div class="fee-panel__panel" data-accordion-panel>
                    <div class="fee-panel__inner">
                        <h3 class="fee-dept-heading"><?= htmlspecialchars($panel['heading'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if (!empty($panel['subheading'])): ?>
                            <p class="fee-dept-subheading"><?= htmlspecialchars($panel['subheading'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>

                        <?php foreach ($panel['tables'] as $table): ?>
                            <?php if (!empty($table['label'])): ?>
                                <p class="fee-table-label"><?= htmlspecialchars($table['label'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                            <div class="fee-table-wrap">
                                <table class="fee-table">
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Head</th>
                                            <th>Learning Investment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($table['rows'] as $row): ?>
                                            <tr class="<?= $row['shaded'] ? 'fee-row--shaded' : '' ?>">
                                                <td><?= htmlspecialchars($row['no'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="<?= $row['head_bold'] ? 'fee-cell--bold' : '' ?>"><?= htmlspecialchars($row['head'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="<?= $row['value_bold'] ? 'fee-cell--bold' : '' ?>"><?= htmlspecialchars($row['value'], ENT_QUOTES, 'UTF-8') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($panel['notes_after'])): ?>
                <div class="fee-notes">
                    <h2>Note Section — <?= htmlspecialchars($panel['notes_after']['heading'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <ol>
                        <?php foreach ($panel['notes_after']['items'] as $note): ?>
                            <li><?= htmlspecialchars($note, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="cta-band" style="margin-top:44px;">
        <h2>Cost doesn't have to be the deciding factor</h2>
        <p>LGU runs six scholarship and financial-assistance categories that can offset the fees above.</p>
        <a href="/scholarships" class="btn btn-accent">See Scholarship Categories</a>
    </div>
</main>
