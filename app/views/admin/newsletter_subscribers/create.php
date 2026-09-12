<h1>Add Subscriber</h1>
<div class="card">
<form method="POST" action="/admin/newsletter-subscribers">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Email</label><input type="email" name="email" required></div>
<div class="form-group"><label><input type="checkbox" name="is_verified" checked> Active (receives update emails)</label></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/newsletter-subscribers">Cancel</a>
</form>
</div>
