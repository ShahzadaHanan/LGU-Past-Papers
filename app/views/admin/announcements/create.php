<h1>Add Announcement</h1>
<div class="card">
<form method="POST" action="/admin/announcements">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Message</label><input type="text" name="message" maxlength="255" required placeholder="e.g. Fall 2026 admissions now open"></div>
<div class="form-group"><label>Link URL (optional)</label><input type="text" name="link_url" placeholder="/departments or https://..."></div>
<div class="form-group"><label>Link Label (optional)</label><input type="text" name="link_label" placeholder="e.g. Learn more"></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="0"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" checked> Active</label></div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/announcements">Cancel</a>
</form>
</div>
