<h1>Edit Alumni</h1>
<div class="card">
<form method="POST" action="/admin/alumni-testimonials/<?= (int) $item->id ?>" enctype="multipart/form-data">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" value="<?= htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Tenure Start Year</label><input type="text" name="tenure_start_year" value="<?= htmlspecialchars($item->tenure_start_year ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="2017"></div>
<div class="form-group"><label>Batch / Graduation Year</label><input type="text" name="batch_year" value="<?= htmlspecialchars($item->batch_year, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Degree</label><input type="text" name="degree" value="<?= htmlspecialchars($item->degree, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Current Position</label><input type="text" name="current_position" value="<?= htmlspecialchars($item->current_position ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Software Engineer"></div>
<div class="form-group"><label>Current Field / Industry</label><input type="text" name="current_field" value="<?= htmlspecialchars($item->current_field ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Software & IT Services"></div>
<div class="form-group"><label>LinkedIn URL</label><input type="url" name="linkedin_url" value="<?= htmlspecialchars($item->linkedin_url ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
<div class="form-group"><label>Photo</label><br>
<?php if ($item->photo): ?><img src="<?= htmlspecialchars($item->photo, ENT_QUOTES, 'UTF-8') ?>" width="70" style="border-radius:50%; margin-bottom:8px;"><br><?php endif; ?>
<input type="file" name="photo" accept="image/*"></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?= (int) $item->display_order ?>"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" <?= $item->is_active ? 'checked' : '' ?>> Active</label></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/alumni-testimonials">Cancel</a>
</form>
</div>
