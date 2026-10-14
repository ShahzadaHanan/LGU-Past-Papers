<div class="auth-card">

    <h1><?= htmlspecialchars($title ?? 'Create Account', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if ($error = $session->flash('error')): ?>
        <p class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars($action ?? '/account/register', ENT_QUOTES, 'UTF-8') ?>">

        <?= \App\Helpers\Form::csrf($csrf) ?>

        <label for="name">Name</label>
        <input id="name" type="text" name="name" placeholder="Your name" required autofocus>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="you@example.com" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="At least 8 characters" required minlength="8">

        <button type="submit">Create Account</button>

    </form>

    <p style="text-align:center; margin-top:16px; font-size:0.9rem;">
        Already have an account? <a href="/account/login">Login</a>
    </p>

</div>
