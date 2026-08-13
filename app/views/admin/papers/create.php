<h1>Add Paper</h1>

<div class="card">
<form method="POST" action="/admin/papers" enctype="multipart/form-data">
    <?= \App\Helpers\Form::csrf($csrf) ?>

    <div class="form-group">
        <label>Sub-Department</label>
        <select name="sub_department_id" required>
            <option value="">Select sub-department</option>
            <?php foreach ($subDepartments as $sd): ?>
                <option value="<?= (int) $sd['id'] ?>"><?= htmlspecialchars($sd['name'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Subject Name</label>
        <input type="text" name="subject_name" required>
    </div>

    <div class="form-group">
        <label>Exam Type</label>
        <select name="exam_type" required>
            <option value="mids">Mids</option>
            <option value="finals">Finals</option>
            <option value="summer_mids">Summer Mids</option>
            <option value="summer_finals">Summer Finals</option>
        </select>
    </div>

    <div class="form-group">
        <label>Session (e.g. Fall 2024)</label>
        <input type="text" name="session" required>
    </div>

    <div class="form-group">
        <label>Paper Image (required)</label>
        <input type="file" name="paper_image" accept="image/*" required>
    </div>

    <div class="form-group">
        <label>Solution Image (optional)</label>
        <input type="file" name="solution_image" accept="image/*">
    </div>

    <div class="form-group">
        <label>Downloadable File (PDF, optional)</label>
        <input type="file" name="download_file" accept="application/pdf">
    </div>

    <div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="meta_title">
    </div>

    <div class="form-group">
        <label>Meta Description</label>
        <textarea name="meta_description" rows="3"></textarea>
    </div>

    <button class="btn-primary" type="submit">Save Paper</button>
    <a class="btn-secondary" href="/admin/papers">Cancel</a>
</form>
</div>
