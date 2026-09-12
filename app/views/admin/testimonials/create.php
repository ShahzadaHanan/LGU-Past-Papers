<h1>Add Testimonial</h1>
<div class="card">
<form method="POST" action="/admin/testimonials" enctype="multipart/form-data">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" required></div>
<div class="form-group"><label>Degree (optional)</label><input type="text" name="degree" placeholder="BS Computer Science"></div>
<div class="form-group"><label>Quote</label><textarea name="quote" rows="4" required></textarea></div>
<div class="form-group"><label>Photo (optional)</label><input type="file" name="photo" accept="image/*"></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="0"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" checked> Active</label></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/testimonials">Cancel</a>
</form>
</div>
