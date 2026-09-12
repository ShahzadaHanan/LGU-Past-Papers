<h1>Add Alumni</h1>
<div class="card">
<form method="POST" action="/admin/alumni-testimonials" enctype="multipart/form-data">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" required></div>
<div class="form-group"><label>Tenure Start Year</label><input type="text" name="tenure_start_year" placeholder="2017"></div>
<div class="form-group"><label>Batch / Graduation Year</label><input type="text" name="batch_year" placeholder="2021" required></div>
<div class="form-group"><label>Degree</label><input type="text" name="degree" placeholder="BS Computer Science" required></div>
<div class="form-group"><label>Current Position</label><input type="text" name="current_position" placeholder="Software Engineer"></div>
<div class="form-group"><label>Current Field / Industry</label><input type="text" name="current_field" placeholder="Software & IT Services"></div>
<div class="form-group"><label>LinkedIn URL (optional)</label><input type="url" name="linkedin_url"></div>
<div class="form-group"><label>Photo (optional)</label><input type="file" name="photo" accept="image/*"></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="0"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" checked> Active</label></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/alumni-testimonials">Cancel</a>
</form>
</div>
