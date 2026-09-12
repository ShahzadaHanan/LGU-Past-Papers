<h1>Edit Video</h1>
<div class="card">
<form method="POST" action="/admin/videos/<?= (int) $video->id ?>">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Category</label>
<select name="category_id" required>
<?php foreach ($categories as $c): ?><option value="<?= (int) $c->id ?>" <?= (int) $c->id === $video->category_id ? 'selected' : '' ?>><?= htmlspecialchars($c->name, ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
</select></div>
<div class="form-group"><label>Department</label>
<select id="department_select">
<option value="">Select department</option>
<?php foreach ($departments as $d): ?><option value="<?= (int) $d['id'] ?>" <?= (int) $d['id'] === (int) ($video->department_id ?? 0) ? 'selected' : '' ?>><?= htmlspecialchars($d['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
</select></div>
<div class="form-group"><label>Degree Program</label>
<select name="sub_department_id" id="sub_department_select" data-selected="<?= (int) ($video->sub_department_id ?? 0) ?>">
<option value="">Select department first</option>
</select></div>
<div class="form-group"><label>Subject</label><input type="text" name="subject_name" id="subject_input" value="<?= htmlspecialchars($video->subject_name ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="e.g. Data Structures & Algorithms"></div>
<div class="form-group"><label>Title</label><input type="text" name="title" value="<?= htmlspecialchars($video->title, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>YouTube URL</label><input type="url" name="youtube_url" value="<?= htmlspecialchars($video->youtube_url, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Description</label><textarea name="description" rows="4"><?= htmlspecialchars($video->description ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?= (int) $video->display_order ?>"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" <?= $video->is_active ? 'checked' : '' ?>> Active</label></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/videos">Cancel</a>
</form>
</div>
<script>
window.SUB_DEPARTMENTS = <?= json_encode(array_map(fn($sd) => ['id' => $sd->id, 'department_id' => $sd->department_id, 'name' => $sd->name], $subDepartments), JSON_UNESCAPED_UNICODE) ?>;
</script>
