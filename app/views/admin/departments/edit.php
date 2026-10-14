<h1>Edit Department</h1>

<div class="card">
<form method="POST" action="/admin/departments/<?= (int) $department['id'] ?>" enctype="multipart/form-data">
    <?= \App\Helpers\Form::csrf($csrf) ?>

    <div class="form-group">
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="<?= htmlspecialchars($department['name'], ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="6"><?= htmlspecialchars($department['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="form-group">
        <label for="hero_image">Hero Image</label><br>
        <?php if (!empty($department['hero_image'])): ?>
            <img src="<?= htmlspecialchars($department['hero_image'], ENT_QUOTES, 'UTF-8') ?>" width="140" style="border-radius:var(--radius-sm); margin-bottom:8px; display:block;">
        <?php endif; ?>
        <input id="hero_image" type="file" name="hero_image" accept="image/*">
    </div>

    <div class="form-group">
        <label for="meta_title">Meta Title</label>
        <input id="meta_title" type="text" name="meta_title" value="<?= htmlspecialchars($department['meta_title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="form-group">
        <label for="meta_description">Meta Description</label>
        <textarea id="meta_description" name="meta_description" rows="4"><?= htmlspecialchars($department['meta_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="form-group">
        <label for="display_order">Display Order</label>
        <input id="display_order" type="number" name="display_order" value="<?= (int) ($department['display_order'] ?? 0) ?>">
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="is_active" <?= !empty($department['is_active']) ? 'checked' : '' ?>> Active</label>
    </div>

    <button class="btn-primary" type="submit">Update Department</button>
    <a class="btn-secondary" href="/admin/departments">Cancel</a>
</form>
</div>
