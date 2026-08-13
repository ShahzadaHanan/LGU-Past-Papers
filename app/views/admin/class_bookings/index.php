<h1>Class Bookings</h1>
<div class="card">
<div class="card-header"><h3></h3>
<div style="display:flex; gap:10px;">
<a class="btn-secondary" href="/admin/class-bookings/export/csv">Export CSV</a>
<a class="btn-primary" href="/admin/class-bookings/create">+ Add Booking</a>
</div>
</div>
<table><thead><tr><th>Name</th><th>Email</th><th>WhatsApp</th><th>Subject</th><th>Fee Type</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($items)): ?><tr><td colspan="7">No bookings found.</td></tr><?php else: foreach ($items as $b): ?>
<tr>
<td><?= htmlspecialchars($b->name, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($b->email, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($b->whatsapp, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($b->subject, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars(str_replace('_', ' ', $b->fee_type), ENT_QUOTES, 'UTF-8') ?></td>
<td>
<form method="POST" action="/admin/class-bookings/<?= (int) $b->id ?>/status" style="display:inline">
<?= \App\Helpers\Form::csrf($csrf) ?>
<select name="status" onchange="this.form.submit()">
<?php foreach (['new', 'contacted', 'confirmed', 'completed', 'cancelled'] as $s): ?>
<option value="<?= $s ?>" <?= $b->status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
<?php endforeach; ?>
</select>
</form>
</td>
<td>
<a class="btn-secondary" href="/admin/class-bookings/<?= (int) $b->id ?>/edit">Edit</a>
<form method="POST" action="/admin/class-bookings/<?= (int) $b->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this booking?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
