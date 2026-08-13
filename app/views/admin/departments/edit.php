<h1>Edit Department</h1>

<form
method="POST"
action="/admin/departments/<?= $department['id'] ?>">


<input
type="hidden"
name="_token"
value="<?= $csrf->token(); ?>">


<label>

Department Name

</label>

<br>

<input
type="text"
name="name"
value="<?= htmlspecialchars($department['name']) ?>">

<br><br>

<?php if($department['hero_image']): ?>

<img
src="/<?= $department['hero_image']; ?>"
width="180">

<?php endif; ?>

<button>

Update Department

</button>

</form>