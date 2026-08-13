<h1>Add Content Block</h1>
<div class="card">
<form method="POST" action="/admin/content-blocks">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Page Key</label>
<select name="page_key" required>
<option value="home">Home</option>
<option value="about_lgu">About LGU</option>
<option value="about_us">About Us</option>
</select>
<small style="color:var(--text-muted)">Or type a custom key like <code>dept-3</code> for a specific department page.</small>
</div>
<div class="form-group"><label>Heading</label><input type="text" name="heading" required></div>
<div class="form-group"><label>Body</label><textarea name="body" rows="8"></textarea></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="0"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" checked> Active</label></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/content-blocks">Cancel</a>
</form>
</div>
