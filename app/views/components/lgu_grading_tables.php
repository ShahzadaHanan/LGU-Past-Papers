<?php
/**
 * LGU grade-point table + absolute-grading marks chart, shared by both GPA
 * calculator pages. Expects $grading (app/data/LguGradingData.php).
 */
?>
<div class="section-head gpa-section-head">
    <span class="eyebrow">Official Grading Scale</span>
    <h2>LGU grading system &amp; grade points</h2>
    <p>Lahore Garrison University grades every course on a 4.00 scale. Classes of 20 or more students are graded
        <strong>relatively</strong> (your grade depends on where you stand in the class histogram), so there is no
        fixed percentage for each grade in those classes.</p>
</div>

<div class="gpa-tables">
    <div>
        <h3 class="gpa-table-title">Letter grade to grade point</h3>
        <div class="fee-table-wrap">
            <table class="fee-table gpa-table">
                <thead>
                    <tr>
                        <th scope="col">Grade</th>
                        <th scope="col">Meaning</th>
                        <th scope="col">Grade Point</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grading['grades'] as $g): ?>
                        <tr>
                            <td class="fee-cell--bold"><?= htmlspecialchars($g['grade'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($g['note'] !== '' ? $g['note'] : '—', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="fee-cell--bold"><?= $g['points'] === null ? 'Not counted' : number_format($g['points'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h3 class="gpa-table-title">Marks to grade (absolute grading)</h3>
        <div class="fee-table-wrap">
            <table class="fee-table gpa-table">
                <thead>
                    <tr>
                        <th scope="col">Marks (%)</th>
                        <th scope="col">Grade</th>
                        <th scope="col">Grade Point</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grading['absolute'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['marks'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="fee-cell--bold"><?= htmlspecialchars($row['grade'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="fee-cell--bold"><?= htmlspecialchars($row['points'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="gpa-fineprint">This chart applies only when your class has <strong>fewer than 20 students</strong>.
            In larger classes the instructor sets the ranges from the class histogram; LGU's preferred lower limit
            for a D in that case is 40%.</p>
    </div>
</div>
