<h1>Add Video Category</h1>
<div class="card">
<form method="POST" action="/admin/video-categories">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" required></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="0"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" checked> Active</label></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/video-categories">Cancel</a>
</form>
</div>
