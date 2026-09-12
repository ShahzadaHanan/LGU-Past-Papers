<div class="auth-card">

    <h1><?= htmlspecialchars($title ?? 'Admin Login', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if ($error = $session->flash('error')): ?>
        <p class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($success = $session->flash('success')): ?>
        <p class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars($action ?? '/admin/login', ENT_QUOTES, 'UTF-8') ?>">

        <?= \App\Helpers\Form::csrf($csrf) ?>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="you@lgu.edu.pk" required autofocus>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

    </form>

</div>
