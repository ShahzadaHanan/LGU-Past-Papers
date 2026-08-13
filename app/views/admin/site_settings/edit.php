<h1>Edit Setting</h1>
<div class="card">
<form method="POST" action="/admin/site-settings/<?= (int) $setting->id ?>">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Setting Key</label><input type="text" name="setting_key" value="<?= htmlspecialchars($setting->setting_key, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Setting Value</label><textarea name="setting_value" rows="4"><?= htmlspecialchars($setting->setting_value ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/site-settings">Cancel</a>
</form>
</div>
