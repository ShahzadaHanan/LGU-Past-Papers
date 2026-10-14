<?php
$sourceUrl = htmlspecialchars($grading['source_url'], ENT_QUOTES, 'UTF-8');
$sourceName = htmlspecialchars($grading['source_name'], ENT_QUOTES, 'UTF-8');
?>
<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'SGPA Calculator', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>LGU SGPA Calculator</h1>
        <p>Calculate your Lahore Garrison University semester GPA on the official 4.00 scale. Pick your grades,
            set the credit hours and your SGPA updates instantly.</p>
    </div>
</section>

<main class="container section">
    <div class="gpa-layout">
        <section class="gpa-calc" data-gpa-calc="sgpa" data-gpa-initial="5" aria-labelledby="sgpa-calc-title">
            <h2 id="sgpa-calc-title" class="gpa-calc__title">Your courses this semester</h2>
            <p class="gpa-calc__hint">Leave the grade empty for any course you don't want to include. Course names are optional.</p>

            <div class="gpa-calc__head" aria-hidden="true">
                <span>Course</span><span>Credit hours</span><span>Grade</span><span></span>
            </div>
            <div class="gpa-calc__rows" data-gpa-rows></div>

            <template data-gpa-row-template>
                <div class="gpa-row" data-gpa-row>
                    <label class="gpa-row__field">
                        <span class="gpa-row__label">Course</span>
                        <input type="text" maxlength="80" placeholder="e.g. Data Structures" autocomplete="off">
                    </label>
                    <label class="gpa-row__field">
                        <span class="gpa-row__label">Credit hours</span>
                        <select data-gpa-credits>
                            <?php for ($ch = 1; $ch <= 6; $ch++): ?>
                                <option value="<?= $ch ?>"<?= $ch === 3 ? ' selected' : '' ?>><?= $ch ?></option>
                            <?php endfor; ?>
                        </select>
                    </label>
                    <label class="gpa-row__field">
                        <span class="gpa-row__label">Grade</span>
                        <select data-gpa-grade>
                            <option value="">Select grade</option>
                            <?php foreach ($grading['grades'] as $g): ?>
                                <option value="<?= $g['points'] === null ? 'x' : number_format($g['points'], 2) ?>">
                                    <?= htmlspecialchars($g['grade'], ENT_QUOTES, 'UTF-8') ?><?= $g['points'] === null ? ' (not counted)' : ' — ' . number_format($g['points'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <button type="button" class="gpa-row__remove" data-gpa-remove aria-label="Remove this course">&times;</button>
                </div>
            </template>

            <noscript><p class="alert alert-danger">The calculator needs JavaScript. You can still use the formula and grade table below.</p></noscript>

            <div class="gpa-calc__actions">
                <button type="button" class="btn btn-secondary btn-sm" data-gpa-add>+ Add course</button>
                <button type="button" class="btn btn-ghost btn-sm" data-gpa-reset>Reset</button>
            </div>
        </section>

        <aside class="gpa-result" aria-live="polite">
            <span class="gpa-result__label">Your SGPA</span>
            <strong class="gpa-result__value" data-gpa-value>—</strong>
            <span class="gpa-result__scale">out of 4.00</span>
            <dl class="gpa-result__totals">
                <div><dt>Credit hours counted</dt><dd data-gpa-credits-total>0</dd></div>
                <div><dt>Total grade points</dt><dd data-gpa-points-total>0.00</dd></div>
            </dl>
            <ul class="gpa-result__notes" data-gpa-notes></ul>
            <a href="/lgu-cgpa-calculator" class="btn btn-accent btn-block">Calculate your CGPA →</a>
        </aside>
    </div>

    <article class="gpa-content">
        <div class="section-head gpa-section-head">
            <span class="eyebrow">Step by Step</span>
            <h2>How to calculate SGPA at LGU</h2>
            <p>SGPA (Semester Grade Point Average) shows how you performed in one semester. LGU calculates it with
                the same formula this calculator uses.</p>
        </div>

        <ol class="gpa-steps">
            <li><strong>Find each course's grade point.</strong> Convert your letter grade using the LGU table below (A = 4.00, B+ = 3.30, C = 2.00, and so on).</li>
            <li><strong>Multiply by credit hours.</strong> A B (3.00) in a 3-credit-hour course earns 3 × 3.00 = 9.00 grade points.</li>
            <li><strong>Add up the grade points</strong> of every course you attempted that semester.</li>
            <li><strong>Divide by the attempted credit hours.</strong> Courses with F or SF count here; courses with W do not.</li>
            <li><strong>Round to two decimals.</strong> If the third decimal is 5 or more, round the second decimal up (2.666 → 2.67).</li>
        </ol>

        <div class="gpa-formula" role="note">
            <span>SGPA</span> = <span class="gpa-formula__frac"><span>Sum of (grade point × credit hours)</span><span>Sum of attempted credit hours</span></span>
        </div>

        <h3 class="gpa-table-title">Worked example (from the LGU Student Handbook)</h3>
        <div class="fee-table-wrap">
            <table class="fee-table gpa-table">
                <thead>
                    <tr><th scope="col">Course</th><th scope="col">Grade</th><th scope="col">Credit Hours</th><th scope="col">Grade Points</th></tr>
                </thead>
                <tbody>
                    <tr><td>Course 1</td><td class="fee-cell--bold">B</td><td>4</td><td>4 × 3.00 = 12.00</td></tr>
                    <tr><td>Course 2</td><td class="fee-cell--bold">A-</td><td>3</td><td>3 × 3.70 = 11.10</td></tr>
                    <tr><td>Course 3</td><td class="fee-cell--bold">B+</td><td>3</td><td>3 × 3.30 = 9.90</td></tr>
                    <tr><td>Course 4</td><td class="fee-cell--bold">W</td><td>3 (not counted)</td><td>—</td></tr>
                    <tr><td>Course 5</td><td class="fee-cell--bold">F</td><td>2</td><td>2 × 0.00 = 0.00</td></tr>
                    <tr class="fee-row--shaded"><td class="fee-cell--bold">Total</td><td></td><td class="fee-cell--bold">12</td><td class="fee-cell--bold">33.00</td></tr>
                </tbody>
            </table>
        </div>
        <p class="gpa-fineprint">SGPA = 33.00 ÷ 12 = <strong>2.75</strong>. The withdrawn course is left out completely, while the
            F still adds 2 attempted credit hours with zero points.</p>

        <?php require basePath('app/views/components/lgu_grading_tables.php'); ?>

        <div class="section-head gpa-section-head">
            <span class="eyebrow">Know the Rules</span>
            <h2>LGU rules that affect your semester GPA</h2>
        </div>

        <div class="gpa-rules">
            <div class="gpa-rule">
                <h3>Minimum passing grade</h3>
                <p>You must earn at least a <strong>D (1.00)</strong> to clear a course. Anything lower is an F.</p>
            </div>
            <div class="gpa-rule">
                <h3>F and SF grades</h3>
                <p>Both carry 0.00 points but their credit hours still count. SF is awarded when attendance is short
                    and you are barred from the final exam. Missing the final without an accepted reason is also an F.</p>
            </div>
            <div class="gpa-rule">
                <h3>W (withdrawal)</h3>
                <p>A W stays on your transcript but is left out of both GPA and CGPA. You can't withdraw once an F or SF
                    has been awarded, and the course fee isn't refunded.</p>
            </div>
            <div class="gpa-rule">
                <h3>I (incomplete)</h3>
                <p>Given when you miss an exam for an approved reason (medical, a death in the family, an online exam fault,
                    university sports). It is replaced by your actual grade after the Grade-I exam.</p>
            </div>
            <div class="gpa-rule">
                <h3>SGPA below 2.00</h3>
                <p>A semester GPA under 2.00, or any F, is an academic deficiency. The first time puts you on probation;
                    at most two probations are allowed in a four-year BS.</p>
            </div>
            <div class="gpa-rule">
                <h3>How your marks are weighted</h3>
                <p>For theory courses LGU's recommended pattern is a mid-term worth 20–25% and a final worth 35–40%, with
                    the rest from quizzes, assignments, presentations and sessionals. Practical courses use 30% coursework,
                    30% lab work and a 40% final.</p>
            </div>
        </div>

        <div class="section-head gpa-section-head">
            <span class="eyebrow">FAQ</span>
            <h2>LGU SGPA — frequently asked questions</h2>
        </div>
        <?php require basePath('app/views/components/faq_accordion.php'); ?>

        <p class="gpa-source">
            Source: grading scale, formula and rules are taken from the
            <a href="<?= $sourceUrl ?>" target="_blank" rel="noopener"><?= $sourceName ?></a>.
            LGU Hub is an independent student resource; your official result on the LGU student portal always takes precedence.
        </p>
    </article>

    <div class="cta-band" style="margin-top:44px;">
        <h2>Got your SGPA? Work out your CGPA next</h2>
        <p>Add every semester's GPA and credit hours to see your cumulative GPA and where you stand for probation, your degree and medals.</p>
        <a href="/lgu-cgpa-calculator" class="btn btn-accent">Open CGPA Calculator</a>
    </div>
</main>
