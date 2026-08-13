<h1>Hero Slides</h1>
<div class="card">
<div class="card-header"><h3></h3><a class="btn-primary" href="/admin/hero-slides/create">+ Add Slide</a></div>
<table><thead><tr><th>Image</th><th>Caption</th><th>Link</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($slides)): ?><tr><td colspan="6">No slides found.</td></tr><?php else: foreach ($slides as $s): ?>
<tr>
<td><?php if ($s->image_path): ?><img src="<?= htmlspecialchars($s->image_path, ENT_QUOTES, 'UTF-8') ?>" width="70" style="border-radius:6px;"><?php else: ?>—<?php endif; ?></td>
<td><?= htmlspecialchars($s->caption ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($s->link_url ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
<td><?= (int) $s->display_order ?></td>
<td><span class="badge <?= $s->is_active ? 'badge-success' : 'badge-danger' ?>"><?= $s->is_active ? 'Active' : 'Inactive' ?></span></td>
<td>
<a class="btn-secondary" href="/admin/hero-slides/<?= (int) $s->id ?>/edit">Edit</a>
<form method="POST" action="/admin/hero-slides/<?= (int) $s->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this slide?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
