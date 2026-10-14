<h1>Edit Subject</h1>

<div class="card">
<form method="POST" action="/admin/subjects/<?= (int) $subject->id ?>">
    <?= \App\Helpers\Form::csrf($csrf) ?>

    <div class="form-group">
        <label for="sub_department_id">Degree</label>
        <select id="sub_department_id" name="sub_department_id" required>
            <option value="">Select degree</option>
            <?php foreach ($subDepartments as $sd): ?>
                <option value="<?= (int) $sd->id ?>" <?= (int) $sd->id === $subject->sub_department_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sd->name, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="name">Subject Name</label>
        <input id="name" type="text" name="name" value="<?= htmlspecialchars($subject->name, ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="form-group">
        <label for="code">Course Code (optional)</label>
        <input id="code" type="text" name="code" value="<?= htmlspecialchars($subject->code ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="form-group">
        <label for="semester">Semester (optional)</label>
        <input id="semester" type="number" name="semester" min="1" max="12" value="<?= $subject->semester !== null ? (int) $subject->semester : '' ?>">
    </div>

    <div class="form-group">
        <label for="display_order">Display Order</label>
        <input id="display_order" type="number" name="display_order" value="<?= (int) $subject->display_order ?>">
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="is_active" <?= $subject->is_active ? 'checked' : '' ?>> Active</label>
    </div>

    <button class="btn-primary" type="submit">Update Subject</button>
    <a class="btn-secondary" href="/admin/subjects">Cancel</a>
</form>
</div>
