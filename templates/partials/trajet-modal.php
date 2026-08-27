<?php
/**
 * Fenêtre modale affichant le détail d'un trajet.
 *
 * Réservée aux utilisateurs connectés : elle expose les coordonnées du
 * conducteur, que le cahier des charges ne rend accessibles qu'après
 * authentification.
 *
 * @var \Core\Controller   $this
 * @var array<string, mixed> $trajet
 */
?>
<div class="modal fade" id="trajet-<?= (int) $trajet['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <p><strong>Auteur :</strong>
                   <?= $this->e($trajet['auteur_prenom']) ?> <?= $this->e($trajet['auteur_nom']) ?></p>
                <p><strong>Téléphone :</strong> <?= $this->e($trajet['auteur_telephone']) ?></p>
                <p><strong>Email :</strong> <?= $this->e($trajet['auteur_email']) ?></p>
                <p class="mb-0"><strong>Nombre total de places :</strong>
                   <?= $this->e($trajet['places_total']) ?></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>

        </div>
    </div>
</div>