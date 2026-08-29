<?php
/**
 * Liste des agences, réservée à l'administrateur.
 *
 * Seul l'administrateur peut modifier la liste des villes, conformément
 * au cahier des charges.
 *
 * @var \Core\Controller $this
 * @var array<int, array<string, mixed>> $agences
 */
?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0">Agences</h1>
    <a href="<?= BASE_URL ?>/admin/agences/creer" class="btn btn-primary">
        Ajouter une agence
    </a>
</div>

<?php if ($agences === []) : ?>
    <p class="text-muted">Aucune agence n'est enregistrée.</p>
<?php else : ?>
    <table class="table table-striped table-app align-middle">
        <thead>
            <tr>
                <th>Nom</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agences as $agence) : ?>
                <tr>
                    <td><?= $this->e($agence['nom']) ?></td>
                    <td class="text-nowrap">
                        <a href="<?= BASE_URL ?>/admin/agences/<?= (int) $agence['id'] ?>/modifier"
                           class="btn btn-link p-0 me-3 text-primary"
                           title="Modifier">
                            <?php $icone = 'pencil'; require dirname(__DIR__) . '/partials/icones.php'; ?>
                        </a>

                        <form method="post"
                              action="<?= BASE_URL ?>/admin/agences/<?= (int) $agence['id'] ?>/supprimer"
                              class="d-inline"
                              onsubmit="return confirm('Supprimer définitivement cette agence ?');">
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