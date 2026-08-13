<h1>Create Sub Department</h1>

<form
    method="POST"
    action="/admin/sub-departments"
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
        ></textarea>

    </div>

    <br>

    <button type="submit">
        Save Sub Department
    </button>

    <a href="/admin/sub-departments">
        Cancel
    </a>

</form>
