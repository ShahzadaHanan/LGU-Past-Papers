<h1>Submission Details</h1>
<div class="card">
<p><strong>Name:</strong> <?= htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($item->email, ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>WhatsApp:</strong> <?= htmlspecialchars($item->whatsapp, ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Subject:</strong> <?= htmlspecialchars($item->subject, ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Details submitted:</strong> <?= nl2br(htmlspecialchars($item->description ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
<p><strong>Status:</strong> <span class="badge <?= $item->status === 'approved' ? 'badge-success' : ($item->status === 'rejected' ? 'badge-danger' : 'badge-warning') ?>"><?= ucfirst($item->status) ?></span></p>
<?php if ($item->image_path): ?><p><img src="<?= htmlspecialchars($item->image_path, ENT_QUOTES, 'UTF-8') ?>" style="max-width:320px; border-radius:8px;"></p><?php endif; ?>

<?php if ($item->published_paper_id): ?>

<div class="alert alert-success">
    This submission has already been published.
    <a href="/admin/papers/<?= (int) $item->published_paper_id ?>/edit">View / edit the paper</a>
</div>

<?php elseif ($item->status === 'rejected'): ?>

<div class="alert alert-danger">This submission was rejected.</div>

<?php else: ?>

<hr style="margin:24px 0; border:none; border-top:1px solid var(--border-color, #333);">
<h3>Approve &amp; Publish</h3>
<p style="color:var(--text-muted); margin-bottom:16px;">
    Classify this paper to publish it — the submitted image will be moved into the Papers catalogue and become visible on the live site immediately.
</p>

<form method="POST" action="/admin/paper-submissions/<?= (int) $item->id ?>/approve" enctype="multipart/form-data">
<?= \App\Helpers\Form::csrf($csrf) ?>

<div class="form-group"><label>Department</label>
<select id="department_select" required>
<option value="">Select department</option>
<?php foreach ($departments as $d): ?><option value="<?= (int) $d['id'] ?>"><?= htmlspecialchars($d['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
</select></div>

<div class="form-group"><label>Degree Program</label>
<select name="sub_department_id" id="sub_department_select" required>
<option value="">Select department first</option>
</select></div>

<div class="form-group"><label>Exam Type</label>
<select name="exam_type" required>
<option value="">Select exam type</option>
<option value="mids">Mid-Term</option>
<option value="finals">Final-Term</option>
<option value="summer_mids">Summer Mid-Term</option>
<option value="summer_finals">Summer Final-Term</option>
</select></div>

<div class="form-group"><label>Session</label>
<input type="text" name="session" placeholder="e.g. Fall 2024" required></div>

<div class="form-group"><label>Subject Name</label>
<input type="text" name="subject_name" value="<?= htmlspecialchars($item->subject, ENT_QUOTES, 'UTF-8') ?>" required></div>

<div class="form-group"><label>Solution Image (optional)</label>
<input type="file" name="solution_image" accept="image/*"></div>

<div class="form-group"><label>Solution Text (optional)</label>
<textarea name="solution_text" rows="6" placeholder="Type the worked solution here — shown as text on the paper page."></textarea></div>

<button class="btn-primary" type="submit">Approve &amp; Publish Paper</button>
</form>

<form method="POST" action="/admin/paper-submissions/<?= (int) $item->id ?>/reject" style="display:inline; margin-top:10px;" class="form-delete" onsubmit="return confirm('Reject this submission?')">
<?= \App\Helpers\Form::csrf($csrf) ?><button class="btn-danger" type="submit">Reject Submission</button></form>

<?php endif; ?>

<p style="margin-top:20px;"><a class="btn-secondary" href="/admin/paper-submissions">Back</a></p>
</div>

<script>
window.SUB_DEPARTMENTS = <?= json_encode(array_map(fn($sd) => ['id' => $sd->id, 'department_id' => $sd->department_id, 'name' => $sd->name], $subDepartments), JSON_UNESCAPED_UNICODE) ?>;
</script>
