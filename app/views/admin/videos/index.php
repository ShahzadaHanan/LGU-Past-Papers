<h1>Videos</h1>
<div class="card">
<div class="card-header"><h3></h3><a class="btn-primary" href="/admin/videos/create">+ Add Video</a></div>
<table><thead><tr><th>Title</th><th>Category</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($videos)): ?><tr><td colspan="5">No videos found.</td></tr><?php else: foreach ($videos as $v): ?>
<tr>
<td><?= htmlspecialchars($v->title, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($v->category_name ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
<td><?= (int) $v->display_order ?></td>
<td><span class="badge <?= $v->is_active ? 'badge-success' : 'badge-danger' ?>"><?= $v->is_active ? 'Active' : 'Inactive' ?></span></td>
<td>
<a class="btn-secondary" href="/admin/videos/<?= (int) $v->id ?>/edit">Edit</a>
<form method="POST" action="/admin/videos/<?= (int) $v->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this video?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
