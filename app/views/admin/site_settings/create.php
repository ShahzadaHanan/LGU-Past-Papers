<h1>Add Setting</h1>
<div class="card">
<form method="POST" action="/admin/site-settings">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Setting Key</label><input type="text" name="setting_key" placeholder="e.g. contact_email" required></div>
<div class="form-group"><label>Setting Value</label><textarea name="setting_value" rows="4"></textarea></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/site-settings">Cancel</a>
</form>
</div>
