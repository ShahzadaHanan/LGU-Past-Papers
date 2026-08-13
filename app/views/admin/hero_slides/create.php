<h1>Add Hero Slide</h1>
<div class="card">
<form method="POST" action="/admin/hero-slides" enctype="multipart/form-data">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Slide Image (required)</label><input type="file" name="image" accept="image/*" required></div>
<div class="form-group"><label>Caption</label><input type="text" name="caption"></div>
<div class="form-group"><label>Link URL</label><input type="text" name="link_url" placeholder="/departments"></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="0"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" checked> Active</label></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/hero-slides">Cancel</a>
</form>
</div>
