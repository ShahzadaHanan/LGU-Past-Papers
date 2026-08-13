<h1>Admin Login</h1>

<?php if (!empty($error)): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/admin/login">

    <input
        type="email"
        name="email"
        placeholder="Email"
        required
    >

    <input
        type="password"
        name="password"
        placeholder="Password"
        required
    >

    <button type="submit">
        Login
    </button>

</form>