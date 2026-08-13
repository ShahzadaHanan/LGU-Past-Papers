<h1>Sub Departments</h1>

<a href="/admin/sub-departments/create">
    Create Sub Department
</a>

<br><br>

<table>

    <thead>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Department ID</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

    <?php if (empty($subDepartments)): ?>

        <tr>
            <td colspan="5">
                No sub departments found.
            </td>
        </tr>

    <?php else: ?>

        <?php foreach ($subDepartments as $subDepartment): ?>

            <tr>

                <td>
                    <?= (int) $subDepartment->id ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $subDepartment->name,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </td>

                <td>
                    <?= (int) $subDepartment->department_id ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $subDepartment->description ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </td>

                <td>

                    <a
                        href="/admin/sub-departments/<?= (int) $subDepartment->id ?>/edit"
                    >
                        Edit
                    </a>

                    <form
                        method="POST"
                        action="/admin/sub-departments/<?= (int) $subDepartment->id ?>/delete"
                        style="display:inline"
                        onsubmit="return confirm('Delete Sub Department?')"
                    >

                        <?= \App\Helpers\Form::csrf($csrf) ?>

                        <button type="submit">
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

        <?php endforeach; ?>

    <?php endif; ?>

    </tbody>

</table>
