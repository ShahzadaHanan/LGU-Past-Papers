<h1>Edit Testimonial</h1>
<div class="card">
<form method="POST" action="/admin/testimonials/<?= (int) $item->id ?>" enctype="multipart/form-data">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" value="<?= htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Degree (optional)</label><input type="text" name="degree" value="<?= htmlspecialchars($item->degree ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="BS Computer Science"></div>
<div class="form-group"><label>Quote</label><textarea name="quote" rows="4" required><?= htmlspecialchars($item->quote, ENT_QUOTES, 'UTF-8') ?></textarea></div>
<div class="form-group"><label>Photo</label><br>
<?php if ($item->photo): ?><img src="<?= htmlspecialchars($item->photo, ENT_QUOTES, 'UTF-8') ?>" width="70" style="border-radius:50%; margin-bottom:8px;"><br><?php endif; ?>
<input type="file" name="photo" accept="image/*"></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?= (int) $item->display_order ?>"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" <?= $item->is_active ? 'checked' : '' ?>> Active</label></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/testimonials">Cancel</a>
</form>
</div>
