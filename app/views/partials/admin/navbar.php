<header class="navbar">

<div>

<?= $title ?? '' ?>

</div>

<form
method="POST"
action="/logout">

<?= \App\Helpers\Form::csrf($csrf); ?>

<button
type="submit">

Logout

</button>

</form>

</header>