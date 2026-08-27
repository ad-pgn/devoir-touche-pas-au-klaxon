<?php
/**
 * Page d'accueil.
 *
 * @var \Core\Controller $this
 * @var string $titre
 * @var array<int, array<string, mixed>> $trajets
 */
?>
<h1 class="h2 mb-4"><?= $this->e($titre) ?></h1>

<?php $connecte = false; require dirname(__DIR__) . '/partials/trajets-table.php'; ?>