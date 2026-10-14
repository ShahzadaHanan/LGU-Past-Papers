<h1>Registered Users</h1>

<div class="card">
    <div class="card-header">
        <form method="GET" action="/admin/site-users" style="display:flex; gap:8px;">
            <input type="text" name="search" placeholder="Search by name or email..."
                   value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>" style="min-width:260px;">
            <button class="btn-secondary" type="submit">Search</button>
        </form>
    </div>

    <table>
        <thead>
        <tr>
            <th>Avatar</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Sign-up Method</th>
            <th>Registered</th>
            <th>Last Login</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php if (empty($users)): ?>

            <tr><td colspan="8">No registered users found.</td></tr>

        <?php else: ?>

            <?php foreach ($users as $user): ?>

                <tr>
                    <td>
                        <?php if (!empty($user['profile_image'])): ?>
                            <img src="<?= htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8') ?>" alt=""
                                 style="width:36px; height:36px; border-radius:50%; object-fit:cover; display:block;">
                        <?php else: ?>
                            <span style="color:var(--text-muted); font-size:0.85rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['phone'] ?: '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if (($user['auth_provider'] ?? 'local') === 'google'): ?>
                            <span class="badge badge-warning">Google</span>
                        <?php else: ?>
                            <span class="badge badge-success">Email</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['last_login_at'] ?? 'Never', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <form method="POST" action="/admin/site-users/<?= (int) $user['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Delete this user?')">
                            <?= \App\Helpers\Form::csrf($csrf) ?>
                            <button class="btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php
$totalPages = isset($total) ? (int) ceil($total / $perPage) : 1;
if ($totalPages > 1):
?>
<div style="display:flex; gap:6px; margin-top:16px; flex-wrap:wrap;">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="btn-secondary" style="<?= $i === (int) $page ? 'font-weight:800;' : '' ?>"
           href="/admin/site-users?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>
