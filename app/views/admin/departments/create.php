<h1>Add Department</h1>

<div class="card">
<form method="POST" action="/admin/departments" enctype="multipart/form-data">
    <?= \App\Helpers\Form::csrf($csrf) ?>

    <div class="form-group">
        <label for="name">Name</label>
        <input id="name" type="text" name="name" required>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="6"></textarea>
    </div>

    <div class="form-group">
        <label for="hero_image">Hero Image</label>
        <input id="hero_image" type="file" name="hero_image" accept="image/*">
    </div>

    <div class="form-group">
        <label for="meta_title">Meta Title</label>
        <input id="meta_title" type="text" name="meta_title">
    </div>

    <div class="form-group">
        <label for="meta_description">Meta Description</label>
        <textarea id="meta_description" name="meta_description" rows="4"></textarea>
    </div>

    <div class="form-group">
        <label for="display_order">Display Order</label>
        <input id="display_order" type="number" name="display_order" value="0">
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="is_active" checked> Active</label>
    </div>

    <button class="btn-primary" type="submit">Save Department</button>
    <a class="btn-secondary" href="/admin/departments">Cancel</a>
</form>
</div>
