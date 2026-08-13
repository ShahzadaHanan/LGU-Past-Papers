<h1>Departments</h1>

<a href="/admin/departments/create">
    Create Department
</a>

<br><br>

<form
    method="GET"
    action="/admin/departments"
>

    <label for="search">
        Search
    </label>

    <input
        id="search"
        type="text"
        name="search"
        value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>"
    >

    <button type="submit">
        Search
    </button>

</form>

<br>

<table>

    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Hero Image</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

    <?php if (empty($departments)): ?>

        <tr>
            <td colspan="6">
                No departments found.
            </td>
        </tr>

    <?php else: ?>

        <?php foreach ($departments as $department): ?>

            <tr>

                <td>
                    <?= (int) $department['id'] ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $department['name'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $department['slug'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </td>

                <td>

                    <?php if (!empty($department['hero_image'])): ?>

                        <img
                            src="<?= htmlspecialchars(
                                $department['hero_image'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            width="80"
                            alt="<?= htmlspecialchars(
                                $department['name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >

                    <?php else: ?>

                        No image

                    <?php endif; ?>

                </td>

                <td>
                    <?= !empty($department['is_active'])
                        ? 'Active'
                        : 'Inactive'
                    ?>
                </td>

                <td>

                    <a
                        href="/admin/departments/<?= (int) $department['id'] ?>/edit"
                    >
                        Edit
                    </a>

                    <form
                        method="POST"
                        action="/admin/departments/<?= (int) $department['id'] ?>/delete"
                        style="display:inline"
                        onsubmit="return confirm('Delete Department?')"
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