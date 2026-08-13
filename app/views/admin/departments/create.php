<h1>Create Department</h1>

<form
method="POST"
action="/admin/departments"
enctype="multipart/form-data">


<input
type="hidden"
name="_token"
value="<?= $csrf->token() ?>">

<label>Name</label>

<input
type="text"
name="name">

<br><br>

<label>Description</label>

<textarea
name="description"
rows="6"></textarea>

<br><br>

<label>Hero Image</label>

<input
type="file"
name="hero_image">

<br><br>

<label>Meta Title</label>

<input
type="text"
name="meta_title">

<br><br>

<label>Meta Description</label>

<textarea
name="meta_description"
rows="4"></textarea>

<br><br>

<label>Display Order</label>

<input
type="number"
name="display_order"
value="0">

<br><br>

<label>

<input
type="checkbox"
name="is_active"
checked>

Active

</label>

<br><br>

<button>

Save Department

</button>

</form>