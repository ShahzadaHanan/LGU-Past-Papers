<?php
// Exact 7 categories, in order, as published on LGU's own official
// Scholarship dashboard (admissions.lgu.edu.pk/Dashboard/Scholarship) —
// same "Category | Scholarships" table format, not a re-worded summary.
$categories = [
    ['no' => 'Category-I', 'name' => 'Merit Based Scholarship'],
    ['no' => 'Category-II', 'name' => 'Performance Based Award'],
    ['no' => 'Category-III', 'name' => 'Defence Based Subsidy'],
    ['no' => 'Category-IV', 'name' => 'Garrisonian & Kinship Based Scholarship'],
    ['no' => 'Category-V', 'name' => 'LGU Employees Scholarship'],
    ['no' => 'Category-VI', 'name' => 'Sports Based Scholarship'],
    ['no' => 'Category-VII', 'name' => 'Need Based Scholarship'],
];
?>
<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'Scholarships', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>Scholarships &amp; Financial Assistance</h1>
        <p>The seven financial assistance categories LGU publishes on its own admissions portal.</p>
    </div>
</section>

<main class="container section">
    <div class="alert alert-success" style="max-width:780px;">
        This table matches LGU's own official Scholarship dashboard exactly. To check which category you
        qualify for or to apply, use the
        <a href="https://admissions.lgu.edu.pk/Dashboard/Scholarship" target="_blank" rel="noopener"><strong>official Scholarship dashboard</strong></a>
        on the admissions portal.
    </div>

    <h2 class="fee-dept-heading" style="text-align:left; margin:28px 0 16px;">Financial Assistance Categories Are As Follows</h2>

    <div class="fee-table-wrap" style="max-width:640px;">
        <table class="fee-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Scholarships</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td class="fee-cell--bold"><?= htmlspecialchars($cat['no'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p style="color:var(--text-muted); font-size:0.85rem; margin-top:14px; max-width:640px;">
        Category names and order are transcribed exactly from LGU's official dashboard. Eligibility criteria,
        award percentages and application steps for each category are handled entirely on that portal —
        LGU Hub doesn't process scholarship applications.
    </p>

    <div class="cta-band" style="margin-top:44px;">
        <h2>Ready to apply?</h2>
        <p>Scholarship applications are handled through LGU's own admissions portal, not through LGU Hub.</p>
        <a href="https://admissions.lgu.edu.pk/Dashboard/Scholarship" target="_blank" rel="noopener" class="btn btn-accent">Open Scholarship Dashboard</a>
    </div>
</main>
