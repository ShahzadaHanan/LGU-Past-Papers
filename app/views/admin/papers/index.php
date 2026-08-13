<h1>Papers</h1>

<div class="card">
    <div class="card-header">
        <form method="GET" action="/admin/papers" style="display:flex; gap:8px;">
            <input type="text" name="search" placeholder="Search by subject or session..."
                   value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>" style="min-width:260px;">
            <button class="btn-secondary" type="submit">Search</button>
        </form>
        <a class="btn-primary" href="/admin/papers/create">+ Add Paper</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>Subject</th>
            <th>Sub-Department</th>
            <th>Exam Type</th>
            <th>Session</th>
            <th>Views</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($papers)): ?>
            <tr><td colspan="6">No papers found.</td></tr>
        <?php else: ?>
            <?php foreach ($papers as $paper): ?>
                <tr>
                    <td><?= htmlspecialchars($paper->subject_name, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($paper->sub_department_name ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><span class="badge badge-success"><?= htmlspecialchars(str_replace('_', ' ', $paper->exam_type), ENT_QUOTES, 'UTF-8') ?></span></td>
                    <td><?= htmlspecialchars($paper->session, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $paper->views_count ?></td>
                    <td>
                        <a class="btn-secondary" href="/admin/papers/<?= (int) $paper->id ?>/edit">Edit</a>
                        <form method="POST" action="/admin/papers/<?= (int) $paper->id ?>/delete" style="display:inline"
                              class="form-delete" onsubmit="return confirm('Delete this paper?')">
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
$totalPages = isset($total) ? (int) ceil($total / 15) : 1;
if ($totalPages > 1):
?>
<div style="display:flex; gap:6px; margin-top:16px;">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="btn-secondary" style="<?= $i === (int) $page ? 'font-weight:800;' : '' ?>"
           href="/admin/papers?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>
