<h1>Video Categories</h1>
<div class="card">
<div class="card-header"><h3></h3><a class="btn-primary" href="/admin/video-categories/create">+ Add Category</a></div>
<table><thead><tr><th>Name</th><th>Slug</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($categories)): ?><tr><td colspan="5">No categories found.</td></tr><?php else: foreach ($categories as $c): ?>
<tr>
<td><?= htmlspecialchars($c->name, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($c->slug, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= (int) $c->display_order ?></td>
<td><span class="badge <?= $c->is_active ? 'badge-success' : 'badge-danger' ?>"><?= $c->is_active ? 'Active' : 'Inactive' ?></span></td>
<td>
<a class="btn-secondary" href="/admin/video-categories/<?= (int) $c->id ?>/edit">Edit</a>
<form method="POST" action="/admin/video-categories/<?= (int) $c->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this category?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
