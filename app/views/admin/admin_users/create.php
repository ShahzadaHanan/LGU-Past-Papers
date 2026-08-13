<h1>Add Admin User</h1>
<div class="card">
<form method="POST" action="/admin/users">
<?= \App\Helpers\Form::csrf($csrf) ?>
<div class="form-group"><label>Name</label><input type="text" name="name" required></div>
<div class="form-group"><label>Email</label><input type="email" name="email" required></div>
<div class="form-group"><label>Password</label><input type="password" name="password" required></div>
<div class="form-group"><label>Role</label>
<select name="role"><option value="editor">Editor</option><option value="super_admin">Super Admin</option></select>
</div>
<button class="btn-primary" type="submit">Save</button>
<a class="btn-secondary" href="/admin/users">Cancel</a>
</form>
</div>
