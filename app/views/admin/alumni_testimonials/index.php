<h1>Alumni</h1>
<div class="card">
<div class="card-header"><h3></h3><a class="btn-primary" href="/admin/alumni-testimonials/create">+ Add Testimonial</a></div>
<table><thead><tr><th>Photo</th><th>Name</th><th>Tenure</th><th>Degree</th><th>Current Role</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($items)): ?><tr><td colspan="7">No testimonials found.</td></tr><?php else: foreach ($items as $a): ?>
<tr>
<td><?php if ($a->photo): ?><img src="<?= htmlspecialchars($a->photo, ENT_QUOTES, 'UTF-8') ?>" width="45" style="border-radius:50%;"><?php else: ?>—<?php endif; ?></td>
<td><?= htmlspecialchars($a->name, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars(($a->tenure_start_year ? $a->tenure_start_year . ' – ' : '') . $a->batch_year, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($a->degree, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($a->current_position ?? '—', ENT_QUOTES, 'UTF-8') ?><?= $a->current_field ? ' · ' . htmlspecialchars($a->current_field, ENT_QUOTES, 'UTF-8') : '' ?></td>
<td><span class="badge <?= $a->is_active ? 'badge-success' : 'badge-danger' ?>"><?= $a->is_active ? 'Active' : 'Inactive' ?></span></td>
<td>
<a class="btn-secondary" href="/admin/alumni-testimonials/<?= (int) $a->id ?>/edit">Edit</a>
<form method="POST" action="/admin/alumni-testimonials/<?= (int) $a->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this testimonial?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
