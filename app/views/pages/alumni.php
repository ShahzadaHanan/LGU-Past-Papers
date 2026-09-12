<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'Alumni', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>LGU Alumni Network</h1>
        <p>Where Garrisonian graduates are today — and the degrees that got them there.</p>
    </div>
</section>

<main class="container section">
    <div class="alumni-intro">
        <p>
            Lahore Garrison University's journey began in 2010, when the concept was approved in principle
            by the Chief of Army Staff, and the university was formally chartered by the Government of
            Punjab in 2014. In the years since, LGU has grown into a 65-kanal campus at Sector C, DHA Phase VI,
            Lahore, with four faculties, fifteen academic departments and over a thousand courses offered across
            BS, MS/MPhil, PhD and UK-affiliated BTEC HND programs. Every graduate who has walked out of that
            campus carries the "Garrisonian" name with them — a shared identity built on Governor's Merit
            List positions, Sports Board Punjab caps, and the "Knowledge is Light" motto stitched into the
            university crest.
        </p>
        <p>
            The LGU Alumni Network on this page is where that shared identity keeps showing up years after
            graduation. LGU graduates from the Faculty of Computer Sciences & Information Technology now work
            as software engineers, data analysts and IT consultants; graduates from the Faculty of Management
            & Social Sciences hold roles in banking, marketing, human resources and public policy; graduates
            of Criminology & Forensic Sciences serve in law enforcement and legal research; and graduates of
            the Faculty of Basic & Applied Sciences and Faculty of Languages & Islamic Studies have moved into
            healthcare, biotechnology research, education and media. This page profiles Garrisonians across
            those fields — their degree, the years they spent on campus, and the position they hold today —
            alongside the testimonials that appear in the carousel on the homepage.
        </p>
    </div>

    <div class="alumni-grid">
        <?php if (empty($alumni)): ?>
            <div class="empty-state" style="grid-column:1/-1;">No alumni profiles listed yet. Check back soon!</div>
        <?php else: ?>
            <?php foreach ($alumni as $alum): ?>
                <div class="alumni-card">
                    <?php if (!empty($alum['photo'])): ?>
                        <img src="<?= htmlspecialchars($alum['photo'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($alum['name'], ENT_QUOTES, 'UTF-8') ?>" class="alumni-card__photo">
                    <?php else: ?>
                        <div class="alumni-card__photo alumni-card__photo--placeholder"><?= htmlspecialchars(mb_substr($alum['name'], 0, 1), ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>

                    <h3 class="alumni-card__name"><?= htmlspecialchars($alum['name'], ENT_QUOTES, 'UTF-8') ?></h3>

                    <div class="alumni-card__tenure">
                        <?= htmlspecialchars(!empty($alum['tenure_start_year']) ? $alum['tenure_start_year'] . ' – ' . $alum['batch_year'] : 'Batch of ' . $alum['batch_year'], ENT_QUOTES, 'UTF-8') ?>
                    </div>

                    <div class="alumni-card__degree"><?= htmlspecialchars($alum['degree'], ENT_QUOTES, 'UTF-8') ?></div>

                    <?php if (!empty($alum['current_position']) || !empty($alum['current_field'])): ?>
                        <div class="alumni-card__current">
                            <?php if (!empty($alum['current_position'])): ?>
                                <span class="alumni-card__position"><?= htmlspecialchars($alum['current_position'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                            <?php if (!empty($alum['current_field'])): ?>
                                <span class="alumni-card__field-badge"><?= htmlspecialchars($alum['current_field'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($alum['linkedin_url'])): ?>
                        <a href="<?= htmlspecialchars($alum['linkedin_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm alumni-card__linkedin">
                            LinkedIn Profile
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
