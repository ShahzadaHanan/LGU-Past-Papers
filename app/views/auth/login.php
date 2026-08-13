<?php $title = $title ?? 'Admin Login'; ?>

<div class="login-wrapper">
<div class="login-card">

<h1>LGU Admin Login</h1>
<p class="login-subtitle">Sign in to manage past papers, lectures &amp; site content.</p>

<?php if ($error = $session->flash('error')): ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>

<?php endif; ?>

<?php if ($success = $session->flash('success')): ?>

    <div class="alert alert-success">
        <?= htmlspecialchars($success) ?>
    </div>

<?php endif; ?>

<form
    method="POST"
    action="<?= $action ?? '/admin/login' ?>">

    <input
        type="hidden"
        name="_token"
        value="<?= $csrf->token() ?>">

    <div class="form-group">

        <label>Email</label>

        <input
            type="email"
            name="email"
            required
            autofocus>

    </div>

    <div class="form-group">

        <label>Password</label>

        <input
            type="password"
            name="password"
            required>

    </div>

    <div class="form-group remember">

        <label>
            <input
                type="checkbox"
                name="remember">

            Remember Me
        </label>

    </div>

    <button
        class="btn btn-primary"
        type="submit">

        Login

    </button>

</form>

</div>
</div>