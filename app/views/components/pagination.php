<?php if($paginator->hasPrevious()): ?>

<a href="?page=<?= $paginator->page()-1; ?>">

Previous

</a>

<?php endif; ?>

<span>

<?= $paginator->page(); ?>

/

<?= $paginator->totalPages(); ?>

</span>

<?php if($paginator->hasNext()): ?>

<a href="?page=<?= $paginator->page()+1; ?>">

Next

</a>

<?php endif; ?>