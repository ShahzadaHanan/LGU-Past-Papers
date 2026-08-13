<h1>Newsletter Subscribers</h1>
<div class="card">
<div class="card-header"><h3></h3>
<div style="display:flex; gap:10px;">
<a class="btn-secondary" href="/admin/newsletter-subscribers/export/csv">Export CSV</a>
<a class="btn-primary" href="/admin/newsletter-subscribers/create">+ Add Subscriber</a>
</div>
</div>
<table><thead><tr><th>Email</th><th>Status</th><th>Subscribed At</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($items)): ?><tr><td colspan="4">No subscribers found.</td></tr><?php else: foreach ($items as $s): ?>
<tr>
<td><?= htmlspecialchars($s->email, ENT_QUOTES, 'UTF-8') ?></td>
<td><span class="badge <?= $s->is_verified ? 'badge-success' : 'badge-warning' ?>"><?= $s->is_verified ? 'Verified' : 'Unverified' ?></span></td>
<td><?= htmlspecialchars($s->subscribed_at, ENT_QUOTES, 'UTF-8') ?></td>
<td>
<form method="POST" action="/admin/newsletter-subscribers/<?= (int) $s->id ?>/toggle" style="display:inline">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-secondary" type="submit">Toggle</button></form>
<a class="btn-secondary" href="/admin/newsletter-subscribers/<?= (int) $s->id ?>/edit">Edit</a>
<form method="POST" action="/admin/newsletter-subscribers/<?= (int) $s->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this subscriber?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
