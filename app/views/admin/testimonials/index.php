<h1>Testimonials</h1>
<div class="card">
<div class="card-header"><h3>Quote cards shown in the homepage carousel</h3><a class="btn-primary" href="/admin/testimonials/create">+ Add Testimonial</a></div>
<table><thead><tr><th>Photo</th><th>Name</th><th>Degree</th><th>Quote</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($items)): ?><tr><td colspan="6">No testimonials found.</td></tr><?php else: foreach ($items as $t): ?>
<tr>
<td><?php if ($t->photo): ?><img src="<?= htmlspecialchars($t->photo, ENT_QUOTES, 'UTF-8') ?>" width="45" style="border-radius:50%;"><?php else: ?>—<?php endif; ?></td>
<td><?= htmlspecialchars($t->name, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($t->degree ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars(mb_strimwidth($t->quote, 0, 60, '…'), ENT_QUOTES, 'UTF-8') ?></td>
<td><span class="badge <?= $t->is_active ? 'badge-success' : 'badge-danger' ?>"><?= $t->is_active ? 'Active' : 'Inactive' ?></span></td>
<td>
<a class="btn-secondary" href="/admin/testimonials/<?= (int) $t->id ?>/edit">Edit</a>
<form method="POST" action="/admin/testimonials/<?= (int) $t->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this testimonial?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
