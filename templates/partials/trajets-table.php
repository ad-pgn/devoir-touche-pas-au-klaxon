<?php
/**
 * Tableau des trajets, réutilisé par toutes les vues qui en affichent.
 *
 * Les actions disponibles dépendent du profil : le visiteur n'en a
 * aucune, l'utilisateur connecté peut consulter le détail de tous les
 * trajets et modifier ou supprimer les siens, l'administrateur a tous
 * les droits sur tous les trajets.
 *
 * @var \Core\Controller           $this
 * @var array<int, array<string, mixed>> $trajets  Trajets à afficher.
 * @var bool                       $connecte Utilisateur authentifié ou non.
 */

$connecte = $connecte ?? false;
?>
<table class="table table-striped table-trajets align-middle">
    <thead>
        <tr>
            <th>Départ</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Destination</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Places</th>
            <?php if ($connecte) : ?>
                <th></th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($trajets as $trajet) : ?>
            <tr>
                <td><?= $this->e($trajet['agence_depart']) ?></td>
                <td><?= $this->e($this->date($trajet['gdh_depart'])) ?></td>
                <td><?= $this->e($this->heure($trajet['gdh_depart'])) ?></td>
                <td><?= $this->e($trajet['agence_arrivee']) ?></td>
                <td><?= $this->e($this->date($trajet['gdh_arrivee'])) ?></td>
                <td><?= $this->e($this->heure($trajet['gdh_arrivee'])) ?></td>
                <td><?= $this->e($trajet['places_disponibles']) ?></td>

                <?php if ($connecte) : ?>
                    <td class="text-nowrap">
                        <button type="button"
                                class="btn btn-link p-0 me-2 text-body"
                                data-bs-toggle="modal"
                                data-bs-target="#trajet-<?= (int) $trajet['id'] ?>"
                                title="Voir le détail">
                            <?php $icone = 'eye'; require __DIR__ . '/icones.php'; ?>
                        </button>

                        <?php if (\Core\Auth::owns((int) $trajet['utilisateur_id'])) : ?>
                            <a href="<?= BASE_URL ?>/trajets/<?= (int) $trajet['id'] ?>/modifier"
                               class="btn btn-link p-0 me-2 text-primary"
                               title="Modifier">
                                <?php $icone = 'pencil'; require __DIR__ . '/icones.php'; ?>
                            </a>

                            <a href="<?= BASE_URL ?>/trajets/<?= (int) $trajet['id'] ?>/supprimer"
                               class="btn btn-link p-0 text-danger"
                               title="Supprimer"
                               onclick="return confirm('Supprimer définitivement ce trajet ?');">
                                <?php $icone = 'trash'; require __DIR__ . '/icones.php'; ?>
                            </a>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($connecte) : ?>
    <?php foreach ($trajets as $trajet) : ?>
        <?php require __DIR__ . '/trajet-modal.php'; ?>
    <?php endforeach; ?>
<?php endif; ?>