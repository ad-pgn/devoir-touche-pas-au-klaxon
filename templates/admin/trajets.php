<?php
/**
 * Liste de tous les trajets, réservée à l'administrateur.
 *
 * L'administrateur peut consulter le détail de chaque trajet et le
 * supprimer, quel qu'en soit l'auteur. La modification reste réservée
 * à l'auteur, conformément au cahier des charges.
 *
 * @var \Core\Controller $this
 * @var array<int, array<string, mixed>> $trajets
 */
?>
<h1 class="h3 mb-4">Trajets</h1>

<?php if ($trajets === []) : ?>
    <p class="text-muted">Aucun trajet n'est enregistré.</p>
<?php else : ?>
    <table class="table table-striped table-app align-middle">
        <thead>
            <tr>
                <th>Départ</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Places</th>
                <th>Auteur</th>
                <th></th>
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
                    <td>
                        <?= $this->e($trajet['places_disponibles']) ?>
                        / <?= $this->e($trajet['places_total']) ?>
                    </td>
                    <td>
                        <?= $this->e($trajet['auteur_prenom']) ?>
                        <?= $this->e($trajet['auteur_nom']) ?>
                    </td>
                    <td class="text-nowrap">
                        <form method="post"
                              action="<?= BASE_URL ?>/trajets/<?= (int) $trajet['id'] ?>/supprimer"
                              class="d-inline"
                              onsubmit="return confirm('Supprimer définitivement ce trajet ?');">
                            <button type="submit"
                                    class="btn btn-link p-0 text-danger align-baseline"
                                    title="Supprimer">
                                <?php $icone = 'trash'; require dirname(__DIR__) . '/partials/icones.php'; ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>