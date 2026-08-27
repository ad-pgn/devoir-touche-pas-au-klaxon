<?php
/**
 * En-tête de l'application, décliné en trois états selon le profil.
 *
 * Visiteur     : nom de l'application + bouton de connexion.
 * Utilisateur  : nom + création de trajet + identité + déconnexion.
 * Administrateur : nom cliquable + menu de gestion + déconnexion.
 *
 * @var \Core\Controller $this
 */

$utilisateur = $this->utilisateur();
$estAdmin    = $this->estAdmin();
?>
<div class="container">
    <header class="app-header d-flex align-items-center justify-content-between">

        <?php if ($estAdmin) : ?>
            <a href="<?= BASE_URL ?>/admin" class="app-name text-decoration-none text-body">
                Touche pas au klaxon
            </a>
        <?php else : ?>
            <span class="app-name">Touche pas au klaxon</span>
        <?php endif; ?>

        <nav class="d-flex align-items-center gap-3">

            <?php if ($utilisateur === null) : ?>

                <a href="<?= BASE_URL ?>/connexion" class="btn btn-dark">Connexion</a>

            <?php elseif ($estAdmin) : ?>

                <a href="<?= BASE_URL ?>/admin/utilisateurs" class="btn btn-secondary">Utilisateurs</a>
                <a href="<?= BASE_URL ?>/admin/agences" class="btn btn-secondary">Agences</a>
                <a href="<?= BASE_URL ?>/admin/trajets" class="btn btn-secondary">Trajets</a>
                <span>Bonjour <?= $this->e($utilisateur['prenom']) ?> <?= $this->e($utilisateur['nom']) ?></span>
                <a href="<?= BASE_URL ?>/deconnexion" class="btn btn-dark">Déconnexion</a>

            <?php else : ?>

                <a href="<?= BASE_URL ?>/trajets/creer" class="btn btn-dark">Créer un trajet</a>
                <span>Bonjour <?= $this->e($utilisateur['prenom']) ?> <?= $this->e($utilisateur['nom']) ?></span>
                <a href="<?= BASE_URL ?>/deconnexion" class="btn btn-dark">Déconnexion</a>

            <?php endif; ?>

        </nav>
    </header>
</div>