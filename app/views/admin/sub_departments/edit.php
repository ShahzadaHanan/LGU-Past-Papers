<h1>Edit Sub Department</h1>

<form
    method="POST"
    action="/admin/sub-departments/<?= (int) $subDepartment->id ?>"
>

    <?= \App\Helpers\Form::csrf($csrf) ?>

    <div>

        <label for="department_id">
            Department
        </label>

        <select
            id="department_id"
            name="department_id"
            required
        >

            <option value="">
                Select Department
            </option>

            <?php foreach ($departments as $department): ?>

                <option
                    value="<?= (int) $department['id'] ?>"
                    <?= (int) $department['id'] ===
                        (int) $subDepartment->department_id
                        ? 'selected'
                        : ''
                    ?>
                >
                    <?= htmlspecialchars(
                        $department['name'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <br>

    <div>

        <label for="name">
            Name
        </label>

        <input
            id="name"
            type="text"
            name="name"
            value="<?= htmlspecialchars(
                $subDepartment->name,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            required
        >

    </div>

    <br>

    <div>

        <label for="description">
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="6"
        ><?= htmlspecialchars(
            $subDepartment->description ?? '',
            ENT_QUOTES,
            'UTF-8'
        ) ?></textarea>

    </div>

    <br>

    <button type="submit">
        Update Sub Department
    </button>

    <a href="/admin/sub-departments">
        Cancel
    </a>

</form>
