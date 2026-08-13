<h1>Edit Content Block</h1>
<div class="card">
<form method="POST" action="/admin/content-blocks/<?= (int) $block->id ?>">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Page Key</label><input type="text" name="page_key" value="<?= htmlspecialchars($block->page_key, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Heading</label><input type="text" name="heading" value="<?= htmlspecialchars($block->heading, ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Body</label><textarea name="body" rows="8"><?= htmlspecialchars($block->body ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
<div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?= (int) $block->display_order ?>"></div>
<div class="form-group"><label><input type="checkbox" name="is_active" <?= $block->is_active ? 'checked' : '' ?>> Active</label></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/content-blocks">Cancel</a>
</form>
</div>
