<h1>Edit Subscriber</h1>
<div class="card">
<form method="POST" action="/admin/newsletter-subscribers/<?= (int) $item->id ?>">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($item->email, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label><input type="checkbox" name="is_verified" <?= $item->is_verified ? 'checked' : '' ?>> Verified</label></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/newsletter-subscribers">Cancel</a>
</form>
</div>
