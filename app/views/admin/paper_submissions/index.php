<h1><?= htmlspecialchars($title ?? 'Paper Submissions', ENT_QUOTES, 'UTF-8') ?></h1>
<div class="card">
<div class="card-header">
<h3></h3>
<div style="display:flex; gap:10px;">
<a class="btn-secondary" href="/admin/paper-submissions">All</a>
<a class="btn-secondary" href="/admin/paper-submissions/list/pending">Pending Only</a>
</div>
</div>
<table><thead><tr><th>Image</th><th>Name</th><th>Email</th><th>WhatsApp</th><th>Subject</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if (empty($items)): ?><tr><td colspan="7">No submissions found.</td></tr><?php else: foreach ($items as $s): ?>
<tr>
<td><?php if ($s->image_path): ?><img src="<?= htmlspecialchars($s->image_path, ENT_QUOTES, 'UTF-8') ?>" width="60" style="border-radius:6px;"><?php else: ?>—<?php endif; ?></td>
<td><?= htmlspecialchars($s->name, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($s->email, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($s->whatsapp, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($s->subject, ENT_QUOTES, 'UTF-8') ?></td>
<td><span class="badge <?= $s->status === 'approved' ? 'badge-success' : ($s->status === 'rejected' ? 'badge-danger' : 'badge-warning') ?>"><?= ucfirst($s->status) ?></span></td>
<td>
<a class="btn-secondary" href="/admin/paper-submissions/<?= (int) $s->id ?>/edit">View</a>
<?php if ($s->status === 'pending'): ?>
<form method="POST" action="/admin/paper-submissions/<?= (int) $s->id ?>/approve" style="display:inline">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-primary" type="submit">Approve</button></form>
<form method="POST" action="/admin/paper-submissions/<?= (int) $s->id ?>/reject" style="display:inline">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Reject</button></form>
<?php endif; ?>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody></table>
</div>
