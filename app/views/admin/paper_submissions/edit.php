<h1>Submission Details</h1>
<div class="card">
<p><strong>Name:</strong> <?= htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($item->email, ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>WhatsApp:</strong> <?= htmlspecialchars($item->whatsapp, ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Subject:</strong> <?= htmlspecialchars($item->subject, ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Description:</strong> <?= nl2br(htmlspecialchars($item->description ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
<p><strong>Status:</strong> <span class="badge <?= $item->status === 'approved' ? 'badge-success' : ($item->status === 'rejected' ? 'badge-danger' : 'badge-warning') ?>"><?= ucfirst($item->status) ?></span></p>
<?php if ($item->image_path): ?><p><img src="<?= htmlspecialchars($item->image_path, ENT_QUOTES, 'UTF-8') ?>" style="max-width:320px; border-radius:8px;"></p><?php endif; ?>

<?php if ($item->status === 'pending'): ?>
<form method="POST" action="/admin/paper-submissions/<?= (int) $item->id ?>/approve" style="display:inline">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-primary" type="submit">Approve</button></form>
<form method="POST" action="/admin/paper-submissions/<?= (int) $item->id ?>/reject" style="display:inline">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Reject</button></form>
<?php endif; ?>
<a class="btn-secondary" href="/admin/paper-submissions">Back</a>
</div>
