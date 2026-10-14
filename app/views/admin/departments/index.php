<h1>Departments</h1>

<div class="card">
    <div class="card-header">
        <form method="GET" action="/admin/departments" style="display:flex; gap:8px;">
            <input type="text" name="search" placeholder="Search by name..."
                   value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>" style="min-width:260px;">
            <button class="btn-secondary" type="submit">Search</button>
        </form>
        <a class="btn-primary" href="/admin/departments/create">+ Add Department</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>Hero Image</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Order</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($departments)): ?>
            <tr><td colspan="6">No departments found.</td></tr>
        <?php else: ?>
            <?php foreach ($departments as $department): ?>
                <tr>
                    <td>
                        <?php if (!empty($department['hero_image'])): ?>
                            <img src="<?= htmlspecialchars($department['hero_image'], ENT_QUOTES, 'UTF-8') ?>"
                                 alt="<?= htmlspecialchars($department['name'], ENT_QUOTES, 'UTF-8') ?>"
                                 style="width:56px; height:56px; object-fit:cover; border-radius:var(--radius-sm);">
                        <?php else: ?>
                            <span style="color:var(--text-muted); font-size:0.85rem;">No image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($department['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($department['slug'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $department['display_order'] ?></td>
                    <td>
                        <?php if (!empty($department['is_active'])): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="btn-secondary" href="/admin/departments/<?= (int) $department['id'] ?>/edit">Edit</a>
                        <form method="POST" action="/admin/departments/<?= (int) $department['id'] ?>/delete" style="display:inline"
                              onsubmit="return confirm('Delete Department?')">
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
           href="/admin/departments?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>
