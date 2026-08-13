<h1>Edit Video Category</h1>
<div class="card">
<form method="POST" action="/admin/video-categories/<?= (int) $category->id ?>">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" value="<?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?= (int) $category->display_order ?>"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" <?= $category->is_active ? 'checked' : '' ?>> Active</label></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/video-categories">Cancel</a>
</form>
</div>
