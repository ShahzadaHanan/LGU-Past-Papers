<h1>Add Subject</h1>

<div class="card">
<form method="POST" action="/admin/subjects">
    <?= \App\Helpers\Form::csrf($csrf) ?>

    <div class="form-group">
        <label for="sub_department_id">Degree</label>
        <select id="sub_department_id" name="sub_department_id" required>
            <option value="">Select degree</option>
            <?php foreach ($subDepartments as $sd): ?>
                <option value="<?= (int) $sd->id ?>"><?= htmlspecialchars($sd->name, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="name">Subject Name</label>
        <input id="name" type="text" name="name" required>
    </div>

    <div class="form-group">
        <label for="code">Course Code (optional)</label>
        <input id="code" type="text" name="code">
    </div>

    <div class="form-group">
        <label for="semester">Semester (optional)</label>
        <input id="semester" type="number" name="semester" min="1" max="12">
    </div>

    <div class="form-group">
        <label for="display_order">Display Order</label>
        <input id="display_order" type="number" name="display_order" value="0">
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="is_active" checked> Active</label>
    </div>

    <button class="btn-primary" type="submit">Save Subject</button>
    <a class="btn-secondary" href="/admin/subjects">Cancel</a>
</form>
</div>
