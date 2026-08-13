<h1>Site Settings</h1>
<div class="card">
<div class="card-header"><h3></h3><a class="btn-primary" href="/admin/site-settings/create">+ Add Setting</a></div>
<table><thead><tr><th>Key</th><th>Value</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($settings)): ?><tr><td colspan="3">No settings found.</td></tr><?php else: foreach ($settings as $s): ?>
<tr>
<td><code><?= htmlspecialchars($s->setting_key, ENT_QUOTES, 'UTF-8') ?></code></td>
<td><?= htmlspecialchars(mb_strimwidth((string) $s->setting_value, 0, 80, '…'), ENT_QUOTES, 'UTF-8') ?></td>
<td>
<a class="btn-secondary" href="/admin/site-settings/<?= (int) $s->id ?>/edit">Edit</a>
<form method="POST" action="/admin/site-settings/<?= (int) $s->id ?>/delete" style="display:inline" class="form-delete" onsubmit="return confirm('Delete this setting?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Delete</button></form>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
