<h1>Content Blocks</h1>
<div class="card">
<div class="card-header"><h3></h3><a class="btn-primary" href="/admin/content-blocks/create">+ Add Block</a></div>
<table><thead><tr><th>Page Key</th><th>Heading</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($blocks)): ?><tr><td colspan="5">No content blocks found.</td></tr><?php else: foreach ($blocks as $b): ?>
<tr>
<td><code><?= htmlspecialchars($b->page_key, ENT_QUOTES, 'UTF-8') ?></code></td>
<td><?= htmlspecialchars($b->heading, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= (int) $b->display_order ?></td>
<td><span class="badge <?= $b->is_active ? 'badge-success' : 'badge-danger' ?>"><?= $b->is_active ? 'Active' : 'Inactive' ?></span></td>
<td>
<a class="btn-secondary" href="/admin/content-blocks/<?= (int) $b->id ?>/edit">Edit</a>
<form method="POST" action="/admin/content-blocks/<?= (int) $b->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this block?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
