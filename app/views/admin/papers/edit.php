<h1>Edit Paper</h1>

<div class="card">
<form method="POST" action="/admin/papers/<?= (int) $paper->id ?>" enctype="multipart/form-data">
    <?= \App\Helpers\Form::csrf($csrf) ?>

    <div class="form-group">
        <label>Sub-Department</label>
        <select name="sub_department_id" required>
            <?php foreach ($subDepartments as $sd): ?>
                <option value="<?= (int) $sd['id'] ?>" <?= (int) $sd['id'] === $paper->sub_department_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sd['name'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Subject Name</label>
        <input type="text" name="subject_name" value="<?= htmlspecialchars($paper->subject_name, ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="form-group">
        <label>Exam Type</label>
        <select name="exam_type" required>
            <?php foreach (['mids' => 'Mids', 'finals' => 'Finals', 'summer_mids' => 'Summer Mids', 'summer_finals' => 'Summer Finals'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= $paper->exam_type === $val ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Session</label>
        <input type="text" name="session" value="<?= htmlspecialchars($paper->session, ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="form-group">
        <label>Paper Image</label><br>
        <?php if ($paper->paper_image): ?><img src="<?= htmlspecialchars($paper->paper_image, ENT_QUOTES, 'UTF-8') ?>" width="100" style="border-radius:8px; margin-bottom:8px;"><br><?php endif; ?>
        <input type="file" name="paper_image" accept="image/*">
    </div>

    <div class="form-group">
        <label>Solution Image</label><br>
        <?php if ($paper->solution_image): ?><img src="<?= htmlspecialchars($paper->solution_image, ENT_QUOTES, 'UTF-8') ?>" width="100" style="border-radius:8px; margin-bottom:8px;"><br><?php endif; ?>
        <input type="file" name="solution_image" accept="image/*">
    </div>

    <div class="form-group">
        <label>Downloadable File (PDF)</label><br>
        <?php if ($paper->download_file): ?><a href="<?= htmlspecialchars($paper->download_file, ENT_QUOTES, 'UTF-8') ?>" target="_blank">Current file</a><br><?php endif; ?>
        <input type="file" name="download_file" accept="application/pdf">
    </div>

    <div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="meta_title" value="<?= htmlspecialchars($paper->meta_title ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="form-group">
        <label>Meta Description</label>
        <textarea name="meta_description" rows="3"><?= htmlspecialchars($paper->meta_description ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button class="btn-primary" type="submit">Update Paper</button>
    <a class="btn-secondary" href="/admin/papers">Cancel</a>
</form>
</div>
