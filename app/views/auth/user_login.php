<div class="auth-card">

    <h1><?= htmlspecialchars($title ?? 'Login', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if ($error = $session->flash('error')): ?>
        <p class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($success = $session->flash('success')): ?>
        <p class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars($action ?? '/account/login', ENT_QUOTES, 'UTF-8') ?>">

        <?= \App\Helpers\Form::csrf($csrf) ?>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="you@example.com" required autofocus>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

    </form>

    <p style="text-align:center; margin-top:16px; font-size:0.9rem;">
        Don't have an account? <a href="/account/register">Create one</a>
    </p>

</div>
