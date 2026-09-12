<?php $today = date('F Y'); ?>
<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'About LGU', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>About Lahore Garrison University (LGU)</h1>
        <p>"Knowledge is Light" — an HEC-recognized university at Sector C, DHA Phase VI, Lahore, established by the Pakistan Army.</p>
    </div>
</section>

<main class="container section">
    <div style="max-width:820px; margin:0 auto;">

        <?php foreach ($blocks as $block): ?>
            <div style="margin-bottom:36px;">
                <h2 style="font-size:1.5rem; margin-bottom:14px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                    <?= htmlspecialchars($block['heading'], ENT_QUOTES, 'UTF-8') ?>
                </h2>
                <div class="card-text" style="font-size:1.02rem; line-height:1.8; color:var(--text-color);">
                    <?= $block['body'] /* trusted admin-authored rich text */ ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Vision & Mission -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Our Vision &amp; Mission
            </h2>
            <div style="display:grid; gap:16px; grid-template-columns:1fr; margin-bottom:0;">
                <blockquote style="background:var(--bg-card); border:1px solid var(--border-color); border-left:4px solid var(--primary-color); border-radius:var(--radius-md); padding:20px 22px; margin:0;">
                    <strong style="display:block; color:var(--primary-color); font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Vision</strong>
                    <p style="font-style:italic; font-size:1.02rem;">"An inclusive university nurturing skilled professionals and responsible citizens."</p>
                </blockquote>
                <blockquote style="background:var(--bg-card); border:1px solid var(--border-color); border-left:4px solid var(--accent-color); border-radius:var(--radius-md); padding:20px 22px; margin:0;">
                    <strong style="display:block; color:var(--primary-color); font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Mission</strong>
                    <p style="font-style:italic; font-size:1.02rem;">"To foster an open and supportive educational environment that produces graduates who can innovate as entrepreneurs and serve as responsible citizens through transformative learning, industry collaboration and a commitment to sustainability."</p>
                </blockquote>
            </div>
        </div>

        <!-- Core Values -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Our Core Values
            </h2>
            <div style="display:flex; flex-wrap:wrap; gap:10px;">
                <?php foreach (['Innovation', 'Excellence', 'Empathy', 'Discipline', 'Integrity', 'Inclusivity', 'Attitude of Gratitude', 'Team Building'] as $value): ?>
                    <span class="badge badge-primary" style="font-size:0.82rem; padding:8px 16px;"><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Leadership -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Leadership
            </h2>
            <div style="display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; justify-content:space-between; gap:12px; padding:12px 0; border-bottom:1px solid var(--border-color);">
                    <strong>Chancellor</strong><span style="color:var(--text-muted); text-align:right;">Governor of Punjab (ex officio)</span>
                </div>
                <div style="display:flex; justify-content:space-between; gap:12px; padding:12px 0; border-bottom:1px solid var(--border-color);">
                    <strong>Vice Chancellor</strong><span style="color:var(--text-muted); text-align:right;">Maj Gen Muhammad Khalil Dar, HI(M) (Retd)</span>
                </div>
                <div style="display:flex; justify-content:space-between; gap:12px; padding:12px 0;">
                    <strong>Registrar</strong><span style="color:var(--text-muted); text-align:right;">Brig Adnan Ahmed Khan, SI(M) (Retd)</span>
                </div>
            </div>
        </div>

        <!-- Academic Structure -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:8px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Academic Structure — 4 Faculties, 15 Departments
            </h2>
            <p style="color:var(--text-muted); margin-bottom:18px; padding-left:16px;">
                LGU offers programs at BS, MS/MPhil, and PhD levels, plus BTEC HND diplomas (Computing, Business,
                Creative Media) delivered in partnership with Pearson UK — the first university in Lahore to offer
                UK-accredited qualifications at an affordable cost.
            </p>
            <div class="grid" style="grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));">
                <?php
                $facultyStructure = [
                    'Faculty of Social Sciences' => ['Psychology', 'Management Sciences', 'Criminology', 'Media & Communication Studies', 'Humanities & Arts'],
                    'Faculty of Computer Sciences' => ['Computer Science', 'Software Engineering', 'Information Technology', 'Artificial Intelligence', 'Cyber Security'],
                    'Faculty of Languages' => ['English', 'Urdu'],
                    'Faculty of Basic Sciences' => ['Allied Health & Molecular Sciences', 'Chemistry', 'Mathematics', 'Physics'],
                ];
                ?>
                <?php foreach ($facultyStructure as $faculty => $depts): ?>
                    <div class="card">
                        <div class="card-content">
                            <h3 class="card-title" style="font-size:1rem; color:var(--primary-color);"><?= htmlspecialchars($faculty, ENT_QUOTES, 'UTF-8') ?></h3>
                            <ul style="margin:0; padding-left:18px; font-size:0.88rem; color:var(--text-muted); line-height:1.8;">
                                <?php foreach ($depts as $dept): ?>
                                    <li>Dept. of <?= htmlspecialchars($dept, ENT_QUOTES, 'UTF-8') ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <p style="text-align:center; margin-top:18px;">
                <a href="/departments" class="btn btn-secondary btn-sm">Browse Past Papers by Department</a>
            </p>
        </div>

        <!-- Recognition & Accreditation -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Recognition &amp; Accreditation
            </h2>
            <ul style="padding-left:20px; color:var(--text-muted); line-height:1.9; font-size:0.98rem;">
                <li><strong style="color:var(--text-color);">Higher Education Commission of Pakistan (HEC)</strong> — fully recognized/chartered university</li>
                <li><strong style="color:var(--text-color);">National Computing Education Accreditation Council (NCEAC)</strong> — accredits Computer Science, Software Engineering, and IT programs</li>
                <li><strong style="color:var(--text-color);">National Business Education Accreditation Council (NBEAC)</strong> — accredits business/management programs</li>
            </ul>
            <p style="margin-top:12px; padding-left:20px;">
                <a href="https://www.hec.gov.pk" target="_blank" rel="noopener" style="font-size:0.88rem;">Verify on the official HEC university directory →</a>
            </p>
        </div>

        <!-- Research & Innovation -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Research &amp; Innovation
            </h2>
            <ul style="padding-left:20px; color:var(--text-muted); line-height:1.9; font-size:0.98rem;">
                <li><strong style="color:var(--text-color);">Office of Research, Innovation &amp; Commercialization (ORIC)</strong> — established in 2016 under HEC directives, runs the National Research Program for Universities (NRPU) and liaises between LGU and industry.</li>
                <li><strong style="color:var(--text-color);">Digital Forensics Research Center</strong> — the first of its kind in Pakistan, addressing the country's cyber security and digital-forensics training needs.</li>
                <li><strong style="color:var(--text-color);">LGU Research Journals</strong> — multiple HEC-recognized journals published across disciplines.</li>
                <li><strong style="color:var(--text-color);">IBTIDA Business Incubation Centre</strong> — supports student and faculty startups.</li>
                <li><strong style="color:var(--text-color);">Secretariat of Industrial Liaison (SIL)</strong> — connects academia with industry partners.</li>
                <li><strong style="color:var(--text-color);">LGU ACM Student Chapter</strong> — global tech society chapter; LGU students have represented Pakistan in international competitions (e.g., 1st Prize, Huawei ICT Global Competition 2026).</li>
            </ul>
        </div>

        <!-- Campus & Facilities -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Campus &amp; Facilities
            </h2>
            <p style="font-size:1.02rem; line-height:1.8;">
                LGU's main campus is located at <strong>Sector C, Avenue III, DHA Phase VI, Lahore</strong>, easily
                accessible via Lahore Ring Road. The campus spans <strong>65 kanals</strong> and comprises
                multi-story academic blocks, spacious classrooms, well-equipped computer and science labs, an
                auditorium, a library, a medical centre/nutrition clinic, and a cafeteria.
            </p>
        </div>

        <!-- Offices -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Offices That Support Students &amp; Faculty
            </h2>
            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                <?php foreach (['Registrar Office', 'Academics Branch', 'Internal Audit (DIA)', 'Treasurer', 'Human Resource Office (HR)', 'Information Technology (IT) Office', 'Quality Enhancement Cell (QEC)', 'Enterprise Resource Planning (ERP)', 'Student Affairs & Counselling (SA&C)', 'ORIC', 'Office of Internationalization (ION)', 'IBTIDA Business Incubation Centre', 'Procurement & Purchase Department', 'Project Department'] as $office): ?>
                    <span style="background:var(--bg-color); border:1px solid var(--border-color); border-radius:var(--radius-pill); padding:6px 14px; font-size:0.82rem; color:var(--text-muted);"><?= htmlspecialchars($office, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quick Facts -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:6px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                LGU at a Glance
            </h2>
            <p style="color:var(--text-muted); font-size:0.82rem; margin-bottom:16px; padding-left:16px;">
                As of <?= htmlspecialchars($today, ENT_QUOTES, 'UTF-8') ?> — figures change as LGU's own official page updates.
            </p>
            <div class="fee-table-wrap">
                <table class="fee-table">
                    <thead>
                        <tr><th>Metric</th><th>Figure</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $quickFacts = [
                            ['Students (approx.)', '6,040'],
                            ['Faculty Members', '366'],
                            ['Courses Offered', '1,075'],
                            ['Faculties', '4'],
                            ['Departments', '15'],
                            ['Degree Levels Offered', 'BS, MS/MPhil, PhD, BTEC HND'],
                            ['Campus Size', '65 kanals'],
                            ['Campus Location', 'DHA Phase VI, Sector C, Lahore'],
                            ['Established (Concept Approved)', '2010'],
                            ['Chartered by Government of Punjab', '2014'],
                            ['Accrediting / Regulatory Bodies', 'HEC, NCEAC, NBEAC'],
                        ];
                        ?>
                        <?php foreach ($quickFacts as $fact): ?>
                            <tr>
                                <td class="fee-cell--bold"><?= htmlspecialchars($fact[0], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($fact[1], ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Contact -->
        <div style="margin-bottom:36px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Contact LGU
            </h2>
            <ul style="padding-left:20px; color:var(--text-muted); line-height:1.9; font-size:0.98rem;">
                <li><strong style="color:var(--text-color);">Admission Office:</strong> 0322 2757543 / 042 37181827</li>
                <li><strong style="color:var(--text-color);">General Inquiries:</strong> 042-37181821-22</li>
                <li><strong style="color:var(--text-color);">Exam Office:</strong> 042 37181828</li>
                <li><strong style="color:var(--text-color);">Address:</strong> Sector C, DHA Phase VI, Lahore, Punjab, Pakistan</li>
                <li><strong style="color:var(--text-color);">Office Hours:</strong> Monday–Friday, 8:00 AM – 4:00 PM</li>
                <li><strong style="color:var(--text-color);">Social:</strong>
                    <a href="https://www.facebook.com/LGUOFFICIALCAMPUS" target="_blank" rel="noopener">Facebook</a> ·
                    <a href="https://www.instagram.com/lguofficialcampus/" target="_blank" rel="noopener">Instagram</a>
                </li>
            </ul>
        </div>

        <!-- FAQ -->
        <div style="margin-bottom:8px;">
            <h2 style="font-size:1.5rem; margin-bottom:16px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                Frequently Asked Questions
            </h2>
            <div class="fee-accordion">
                <?php foreach ($faqs as $faq): ?>
                    <div class="fee-panel" data-accordion>
                        <button type="button" class="fee-panel__header" data-accordion-toggle>
                            <span class="fee-panel__title" style="text-transform:none; font-size:0.98rem;"><?= htmlspecialchars($faq['q'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="fee-toggle-icon">+</span>
                        </button>
                        <div class="fee-panel__panel" data-accordion-panel>
                            <div class="fee-panel__inner" style="padding-top:0; border-top:none;">
                                <p style="color:var(--text-muted);"><?= htmlspecialchars($faq['a'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <div class="cta-band" style="margin-top:44px;">
        <h2>Find your department's past papers</h2>
        <p>Browse all 15 LGU departments, or check the fee structure and scholarship options before you apply.</p>
        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap; margin-top:8px;">
            <a href="/departments" class="btn btn-accent">Browse Departments</a>
            <a href="/fee-structure" class="btn btn-on-dark">Fee Structure</a>
        </div>
    </div>
</main>
