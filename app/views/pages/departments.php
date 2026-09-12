<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'Departments', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>All 15 LGU Departments</h1>
        <p>Browse by faculty, or jump straight to your department to find past papers, lectures and degree programs.</p>

        <form class="hero__search" action="/departments" method="GET" style="margin-top:20px; max-width:520px;">
            <input type="text" name="q" value="<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>" placeholder="Search your department, e.g. Computer Science">
            <button type="submit" class="btn btn-accent btn-sm">Find</button>
        </form>
    </div>
</section>

<main class="container section">
    <?php if ($searchResults !== null): ?>
        <div class="section-head" style="text-align:left; margin-bottom:24px;">
            <span class="eyebrow">Search Results</span>
            <h2>
                <?= count($searchResults) ?> department<?= count($searchResults) === 1 ? '' : 's' ?>
                found for "<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>"
            </h2>
        </div>

        <?php if (empty($searchResults)): ?>
            <div class="empty-state">
                <p style="margin-bottom:16px;">
                    No such department — we couldn't find anything matching
                    "<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>". Try a broader term
                    (e.g. "Computer" instead of a specific degree name), or browse the full list below.
                </p>
                <a href="/departments" class="btn">Browse All Departments</a>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($searchResults as $dept): ?>
                    <div class="card">
                        <?php if (!empty($dept['hero_image'])): ?>
                            <div class="card-img" style="background-image: url('<?= htmlspecialchars($dept['hero_image'], ENT_QUOTES, 'UTF-8') ?>');"></div>
                        <?php else: ?>
                            <div class="card-img" style="background:linear-gradient(135deg, var(--primary-color), var(--primary-dark)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:1.3rem;">
                                <?= htmlspecialchars(mb_substr($dept['name'], 0, 2), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($dept['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="card-meta"><?= (int) $dept['degree_count'] ?> degree program<?= (int) $dept['degree_count'] === 1 ? '' : 's' ?></p>
                            <p class="card-text">
                                <?= htmlspecialchars(mb_strimwidth(strip_tags($dept['description'] ?? ''), 0, 100, '…'), ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <a href="/department/<?= htmlspecialchars($dept['slug'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-block">Explore Programs</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <?php foreach ($groupedDepartments as $faculty => $depts): ?>
            <h2 class="faculty-heading"><?= htmlspecialchars($faculty, ENT_QUOTES, 'UTF-8') ?></h2>
            <div class="grid">
                <?php foreach ($depts as $dept): ?>
                    <div class="card">
                        <?php if (!empty($dept['hero_image'])): ?>
                            <div class="card-img" style="background-image: url('<?= htmlspecialchars($dept['hero_image'], ENT_QUOTES, 'UTF-8') ?>');"></div>
                        <?php else: ?>
                            <div class="card-img" style="background:linear-gradient(135deg, var(--primary-color), var(--primary-dark)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:1.3rem;">
                                <?= htmlspecialchars(mb_substr($dept['name'], 0, 2), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($dept['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="card-meta"><?= (int) $dept['degree_count'] ?> degree program<?= (int) $dept['degree_count'] === 1 ? '' : 's' ?></p>
                            <p class="card-text">
                                <?= htmlspecialchars(mb_strimwidth(strip_tags($dept['description'] ?? ''), 0, 100, '…'), ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <a href="/department/<?= htmlspecialchars($dept['slug'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-block">Explore Programs</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
