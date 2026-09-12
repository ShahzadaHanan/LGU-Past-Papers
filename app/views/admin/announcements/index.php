<h1>News Bar</h1>
<div class="card">
<div class="card-header"><h3>Announcements shown above the header on the public site, in order</h3><a class="btn-primary" href="/admin/announcements/create">+ Add Announcement</a></div>
<table><thead><tr><th>Message</th><th>Link</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($items)): ?><tr><td colspan="5">No announcements yet.</td></tr><?php else: foreach ($items as $item): ?>
<tr>
<td><?= htmlspecialchars($item->message, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= $item->link_url ? htmlspecialchars($item->link_label ?: $item->link_url, ENT_QUOTES, 'UTF-8') : '—' ?></td>
<td><?= (int) $item->display_order ?></td>
<td><span class="badge <?= $item->is_active ? 'badge-success' : 'badge-danger' ?>"><?= $item->is_active ? 'Active' : 'Inactive' ?></span></td>
<td>
<a class="btn-secondary" href="/admin/announcements/<?= (int) $item->id ?>/edit">Edit</a>
<form method="POST" action="/admin/announcements/<?= (int) $item->id ?>/toggle" style="display:inline">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-secondary" type="submit"><?= $item->is_active ? 'Deactivate' : 'Activate' ?></button></form>
<form method="POST" action="/admin/announcements/<?= (int) $item->id ?>/notify" style="display:inline" onsubmit="return confirm('Email all active subscribers about this announcement?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-secondary" type="submit">Notify Subscribers</button></form>
<form method="POST" action="/admin/announcements/<?= (int) $item->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this announcement?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
