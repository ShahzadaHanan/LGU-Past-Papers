<h1>Edit Announcement</h1>
<div class="card">
<form method="POST" action="/admin/announcements/<?= (int) $item->id ?>">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Message</label><input type="text" name="message" maxlength="255" value="<?= htmlspecialchars($item->message, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Link URL (optional)</label><input type="text" name="link_url" value="<?= htmlspecialchars($item->link_url ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="/departments or https://..."></div>
<div class="form-group"><label>Link Label (optional)</label><input type="text" name="link_label" value="<?= htmlspecialchars($item->link_label ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="e.g. Learn more"></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?= (int) $item->display_order ?>"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" <?= $item->is_active ? 'checked' : '' ?>> Active</label></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/announcements">Cancel</a>
</form>
</div>
