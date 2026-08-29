<?php
/**
 * Tableau de bord de l'administrateur.
 *
 * Présente les indicateurs de l'application et les accès aux
 * fonctionnalités de gestion.
 *
 * @var \Core\Controller $this
 * @var int $nbUtilisateurs
 * @var int $nbAgences
 * @var int $nbTrajets
 */
?>
<h1 class="h3 mb-4">Tableau de bord</h1>

<div class="row g-3">

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 text-muted">Utilisateurs</h2>
                <p class="display-6 mb-3"><?= $this->e($nbUtilisateurs) ?></p>
                <a href="<?= BASE_URL ?>/admin/utilisateurs" class="btn btn-primary btn-sm">
                    Consulter la liste
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 text-muted">Agences</h2>
                <p class="display-6 mb-3"><?= $this->e($nbAgences) ?></p>
                <a href="<?= BASE_URL ?>/admin/agences" class="btn btn-primary btn-sm">
                    Gérer les agences
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h6 text-muted">Trajets</h2>
                <p class="display-6 mb-3"><?= $this->e($nbTrajets) ?></p>
                <a href="<?= BASE_URL ?>/admin/trajets" class="btn btn-primary btn-sm">
                    Gérer les trajets
                </a>
            </div>
        </div>
    </div>

</div>