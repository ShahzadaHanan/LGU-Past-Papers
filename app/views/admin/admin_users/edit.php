<h1>Edit Admin User</h1>
<div class="card">
<form method="POST" action="/admin/users/<?= (int) $item['id'] ?>">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" value="<?= htmlspecialchars($item['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($item['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="form-group"><label>New Password (leave blank to keep current)</label><input type="password" name="password"></div>
<div class="form-group"><label>Role</label>
<select name="role">
<option value="editor" <?= ($item['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor</option>
<option value="super_admin" <?= ($item['role'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
</select></div>
<button class="btn-primary" type="submit">Update</button>
<a class="btn-secondary" href="/admin/users">Cancel</a>
</form>
</div>
