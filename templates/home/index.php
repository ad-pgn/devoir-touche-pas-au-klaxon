<?php
/**
 * Page d'accueil.
 *
 * Le tableau des trajets n'affiche la colonne d'actions que pour les
 * utilisateurs authentifiés : le détail d'un trajet, sa modification et
 * sa suppression ne sont accessibles qu'après connexion.
 *
 * @var \Core\Controller $this
 * @var string $titre
 * @var array<int, array<string, mixed>> $trajets
 */
?>
<h1 class="h2 mb-4"><?= $this->e($titre) ?></h1>

<?php if ($trajets === []) : ?>
    <p class="text-muted">Aucun trajet n'est proposé pour le moment.</p>
<?php else : ?>
    <?php
    $connecte = $this->utilisateur() !== null;
    require dirname(__DIR__) . '/partials/trajets-table.php';
    ?>
<?php endif; ?>