<h1>Subjects</h1>

<div class="card">
    <div class="card-header">
        <form method="GET" action="/admin/subjects" style="display:flex; gap:8px; flex-wrap:wrap;">
            <input type="text" name="search" placeholder="Search by subject name..."
                   value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>" style="min-width:220px;">

            <select name="degree" onchange="this.form.submit()">
                <option value="">All Degrees</option>
                <?php foreach ($subDepartments as $sd): ?>
                    <option value="<?= (int) $sd->id ?>" <?= $selectedSubDepartment === $sd->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sd->name, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="btn-secondary" type="submit">Filter</button>
        </form>
        <a class="btn-primary" href="/admin/subjects/create">+ Add Subject</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Degree</th>
            <th>Semester</th>
            <th>Code</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php if (empty($subjects)): ?>

            <tr>
                <td colspan="6">No subjects found.</td>
            </tr>

        <?php else: ?>

            <?php
            $subDepartmentNames = [];
            foreach ($subDepartments as $sd) {
                $subDepartmentNames[$sd->id] = $sd->name;
            }
            ?>

            <?php foreach ($subjects as $subject): ?>

                <tr>
                    <td><?= htmlspecialchars($subject->name, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($subDepartmentNames[$subject->sub_department_id] ?? ('#' . $subject->sub_department_id), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $subject->semester !== null ? (int) $subject->semester : '—' ?></td>
                    <td><?= htmlspecialchars($subject->code ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if ($subject->is_active): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="btn-secondary" href="/admin/subjects/<?= (int) $subject->id ?>/edit">Edit</a>
                        <form method="POST" action="/admin/subjects/<?= (int) $subject->id ?>/delete" style="display:inline" onsubmit="return confirm('Delete Subject?')">
                            <?= \App\Helpers\Form::csrf($csrf) ?>
                            <button class="btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php
$totalPages = isset($total) ? (int) ceil($total / 20) : 1;
if ($totalPages > 1):
?>
<div style="display:flex; gap:6px; margin-top:16px; flex-wrap:wrap;">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="btn-secondary" style="<?= $i === (int) $page ? 'font-weight:800;' : '' ?>"
           href="/admin/subjects?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&degree=<?= (int) $selectedSubDepartment ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>
