<h1>Add Video</h1>
<div class="card">
<form method="POST" action="/admin/videos">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Category</label>
<select name="category_id" required>
<option value="">Select category</option>
<?php foreach ($categories as $c): ?><option value="<?= (int) $c->id ?>"><?= htmlspecialchars($c->name, ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
</select></div>
<div class="form-group"><label>Title</label><input type="text" name="title" required></div>
<div class="form-group"><label>YouTube URL</label><input type="url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." required></div>
<div class="form-group"><label>Description</label><textarea name="description" rows="4"></textarea></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="0"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" checked> Active</label></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/videos">Cancel</a>
</form>
</div>
