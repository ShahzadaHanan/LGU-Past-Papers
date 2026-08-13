<h1>Admin Users</h1>
<div class="card">
<div class="card-header"><h3></h3><a class="btn-primary" href="/admin/users/create">+ Add Admin User</a></div>
<table><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($items)): ?><tr><td colspan="4">No admin users found.</td></tr><?php else: foreach ($items as $u): ?>
<tr>
<td><?= htmlspecialchars($u['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($u['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
<td><span class="badge badge-success"><?= htmlspecialchars(str_replace('_', ' ', $u['role'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></td>
<td>
<a class="btn-secondary" href="/admin/users/<?= (int) $u['id'] ?>/edit">Edit</a>
<form method="POST" action="/admin/users/<?= (int) $u['id'] ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this admin user?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
