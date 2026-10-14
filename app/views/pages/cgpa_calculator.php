<?php
$sourceUrl = htmlspecialchars($grading['source_url'], ENT_QUOTES, 'UTF-8');
$sourceName = htmlspecialchars($grading['source_name'], ENT_QUOTES, 'UTF-8');
?>
<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'CGPA Calculator', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>LGU CGPA Calculator</h1>
        <p>Calculate your Lahore Garrison University cumulative GPA on the 4.00 scale. Enter each semester's GPA
            and credit hours to see your CGPA and where you stand under LGU's academic rules.</p>
    </div>
</section>

<main class="container section">
    <div class="gpa-layout">
        <section class="gpa-calc gpa-calc--cgpa" data-gpa-calc="cgpa" data-gpa-initial="2" aria-labelledby="cgpa-calc-title">
            <h2 id="cgpa-calc-title" class="gpa-calc__title">Your semesters</h2>
            <p class="gpa-calc__hint">Already know your current CGPA? Enter it as one row with your total credit hours so far,
                then add the new semester below it. Don't know a semester's GPA? Use the
                <a href="/lgu-sgpa-calculator">SGPA calculator</a> first.</p>

            <div class="gpa-calc__head" aria-hidden="true">
                <span>Semester</span><span>GPA (0–4)</span><span>Credit hours</span><span></span>
            </div>
            <div class="gpa-calc__rows" data-gpa-rows></div>

            <template data-gpa-row-template>
                <div class="gpa-row" data-gpa-row>
                    <label class="gpa-row__field">
                        <span class="gpa-row__label">Semester</span>
                        <input type="text" maxlength="40" placeholder="e.g. Fall 2025" autocomplete="off">
                    </label>
                    <label class="gpa-row__field">
                        <span class="gpa-row__label">GPA (0–4)</span>
                        <input type="number" inputmode="decimal" min="0" max="4" step="0.01" placeholder="3.25" data-gpa-semester-gpa>
                    </label>
                    <label class="gpa-row__field">
                        <span class="gpa-row__label">Credit hours</span>
                        <input type="number" inputmode="numeric" min="1" max="200" step="1" placeholder="18" data-gpa-semester-credits>
                    </label>
                    <button type="button" class="gpa-row__remove" data-gpa-remove aria-label="Remove this semester">&times;</button>
                </div>
            </template>

            <noscript><p class="alert alert-danger">The calculator needs JavaScript. You can still use the formula below.</p></noscript>

            <div class="gpa-calc__actions">
                <button type="button" class="btn btn-secondary btn-sm" data-gpa-add>+ Add semester</button>
                <button type="button" class="btn btn-ghost btn-sm" data-gpa-reset>Reset</button>
            </div>
        </section>

        <aside class="gpa-result" aria-live="polite">
            <span class="gpa-result__label">Your CGPA</span>
            <strong class="gpa-result__value" data-gpa-value>—</strong>
            <span class="gpa-result__scale">out of 4.00</span>
            <dl class="gpa-result__totals">
                <div><dt>Total credit hours</dt><dd data-gpa-credits-total>0</dd></div>
                <div><dt>Total grade points</dt><dd data-gpa-points-total>0.00</dd></div>
            </dl>
            <ul class="gpa-result__notes" data-gpa-notes></ul>
            <a href="/lgu-sgpa-calculator" class="btn btn-accent btn-block">Calculate a semester GPA →</a>
        </aside>
    </div>

    <article class="gpa-content">
        <div class="section-head gpa-section-head">
            <span class="eyebrow">Step by Step</span>
            <h2>How to calculate CGPA at LGU</h2>
            <p>CGPA (Cumulative Grade Point Average) combines every semester of your degree into one number. Each
                semester counts in proportion to its credit hours, not equally.</p>
        </div>

        <ol class="gpa-steps">
            <li><strong>Get each semester's GPA and attempted credit hours</strong> from your result card or the LGU student portal.</li>
            <li><strong>Turn each semester into grade points:</strong> multiply its GPA by its credit hours (3.00 × 15 = 45.00).</li>
            <li><strong>Add up the grade points</strong> of all semesters, including summer semesters.</li>
            <li><strong>Divide by the total credit hours</strong> attempted across all those semesters.</li>
            <li><strong>Round to two decimals</strong>, rounding up when the third decimal is 5 or more.</li>
        </ol>

        <div class="gpa-formula" role="note">
            <span>CGPA</span> = <span class="gpa-formula__frac"><span>Sum of all grade points</span><span>Sum of all attempted credit hours</span></span>
        </div>

        <h3 class="gpa-table-title">Worked example (from the LGU Student Handbook)</h3>
        <div class="fee-table-wrap">
            <table class="fee-table gpa-table">
                <thead>
                    <tr><th scope="col">Semester</th><th scope="col">GPA</th><th scope="col">Credit Hours</th><th scope="col">Grade Points</th></tr>
                </thead>
                <tbody>
                    <tr><td>1st semester</td><td class="fee-cell--bold">2.75</td><td>12</td><td>33.00</td></tr>
                    <tr><td>2nd semester</td><td class="fee-cell--bold">3.00</td><td>15</td><td>45.00</td></tr>
                    <tr class="fee-row--shaded"><td class="fee-cell--bold">Total</td><td></td><td class="fee-cell--bold">27</td><td class="fee-cell--bold">78.00</td></tr>
                </tbody>
            </table>
        </div>
        <p class="gpa-fineprint">CGPA = 78.00 ÷ 27 = 2.888… = <strong>2.89</strong>. A plain average of 2.75 and 3.00 would give
            2.88, which is wrong because the second semester carried more credit hours.</p>

        <div class="section-head gpa-section-head">
            <span class="eyebrow">Academic Standing</span>
            <h2>What your CGPA means at LGU</h2>
        </div>

        <div class="fee-table-wrap gpa-standing">
            <table class="fee-table gpa-table">
                <thead>
                    <tr><th scope="col">CGPA</th><th scope="col">What it means</th></tr>
                </thead>
                <tbody>
                    <tr><td class="fee-cell--bold">3.50 and above</td><td>Meets the minimum CGPA for LGU's gold, silver and bronze academic medals (other conditions apply).</td></tr>
                    <tr><td class="fee-cell--bold">3.00 and above</td><td>The CGPA LGU requires in your MS/MPhil to be admitted to a PhD.</td></tr>
                    <tr><td class="fee-cell--bold">2.50 and above</td><td>The minimum BS CGPA for admission to LGU's MS/MPhil and MBA programmes.</td></tr>
                    <tr><td class="fee-cell--bold">2.00 and above</td><td>Good academic standing, and the minimum CGPA needed to be awarded a BS degree.</td></tr>
                    <tr><td class="fee-cell--bold">Below 2.00</td><td>Academic deficiency: probation, re-admission or, eventually, separation from the programme.</td></tr>
                </tbody>
            </table>
        </div>

        <div class="gpa-rules">
            <div class="gpa-rule">
                <h3>Probation</h3>
                <p>The first time your GPA falls below 2.00 you're placed on first probation and promoted to the next
                    regular semester; a second time means second probation. A four-year BS allows at most two, and both
                    can't be used in the first four semesters. You return to good standing once you're back at 2.00 or above.</p>
            </div>
            <div class="gpa-rule">
                <h3>Improving your CGPA</h3>
                <p>You can repeat courses graded <strong>D or C-</strong> in a regular or summer semester. Both grades show on
                    your transcript, but only the better one counts. You can improve at most <strong>5 courses</strong> in the whole degree.</p>
            </div>
            <div class="gpa-rule">
                <h3>Clearing an F or SF</h3>
                <p>Repeat the course when the department offers it. Your transcript shows the better attempt and only one
                    F is counted, however many times you take it.</p>
            </div>
            <div class="gpa-rule">
                <h3>Degree requirements</h3>
                <p>A BS needs a <strong>CGPA of at least 2.00</strong>, the programme's minimum credit hours (120 for one Major
                    under the new policy), and no uncleared F, SF, I or W grade. If you finish below 2.00 you may re-enrol in
                    courses to raise it.</p>
            </div>
            <div class="gpa-rule">
                <h3>Medals and honours</h3>
                <p>Medals need a CGPA of 3.50 or more, the top standing in your session, and a degree finished in minimum time
                    with every course cleared on the first attempt. Repeats, F grades, withdrawals and semester freezes all rule you out.</p>
            </div>
            <div class="gpa-rule">
                <h3>Accuracy note</h3>
                <p>LGU computes CGPA from the exact grade points of every course. Rebuilding it from rounded semester GPAs can
                    be off by 0.01 now and then. To get closer, enter your current CGPA and total credit hours from the portal as
                    one row, then add only the new semester.</p>
            </div>
        </div>

        <?php require basePath('app/views/components/lgu_grading_tables.php'); ?>

        <div class="section-head gpa-section-head">
            <span class="eyebrow">FAQ</span>
            <h2>LGU CGPA — frequently asked questions</h2>
        </div>
        <?php require basePath('app/views/components/faq_accordion.php'); ?>

        <p class="gpa-source">
            Source: formula, standing, probation, improvement and medal rules are taken from the
            <a href="<?= $sourceUrl ?>" target="_blank" rel="noopener"><?= $sourceName ?></a>.
            LGU Hub is an independent student resource; your official transcript on the LGU student portal always takes precedence.
        </p>
    </article>

    <div class="cta-band" style="margin-top:44px;">
        <h2>Want to raise your CGPA?</h2>
        <p>Practise with real LGU past papers from your own department and degree before your next mids and finals.</p>
        <a href="/departments" class="btn btn-accent">Browse Past Papers</a>
    </div>
</main>
