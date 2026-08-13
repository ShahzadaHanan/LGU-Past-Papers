<h1>Edit Hero Slide</h1>
<div class="card">
<form method="POST" action="/admin/hero-slides/<?= (int) $slide->id ?>" enctype="multipart/form-data">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Slide Image</label><br>
<?php if ($slide->image_path): ?><img src="<?= htmlspecialchars($slide->image_path, ENT_QUOTES, 'UTF-8') ?>" width="120" style="border-radius:8px; margin-bottom:8px;"><br><?php endif; ?>
<input type="file" name="image" accept="image/*"></div>
<div class="form-group"><label>Caption</label><input type="text" name="caption" value="<?= htmlspecialchars($slide->caption ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
<div class="form-group"><label>Link URL</label><input type="text" name="link_url" value="<?= htmlspecialchars($slide->link_url ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?= (int) $slide->display_order ?>"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" <?= $slide->is_active ? 'checked' : '' ?>> Active</label></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/hero-slides">Cancel</a>
</form>
</div>
