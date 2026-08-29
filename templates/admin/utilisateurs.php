<?php
/**
 * Liste des utilisateurs, réservée à l'administrateur.
 *
 * @var \Core\Controller $this
 * @var array<int, array<string, mixed>> $utilisateurs
 */
?>
<h1 class="h3 mb-4">Utilisateurs</h1>

<table class="table table-striped table-app align-middle">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Rôle</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($utilisateurs as $utilisateur) : ?>
            <tr>
                <td><?= $this->e($utilisateur['nom']) ?></td>
                <td><?= $this->e($utilisateur['prenom']) ?></td>
                <td><?= $this->e($utilisateur['email']) ?></td>
                <td><?= $this->e($utilisateur['telephone']) ?></td>
                <td>
                    <?php if ($utilisateur['role'] === 'admin') : ?>
                        <span class="badge text-bg-primary">Administrateur</span>
                    <?php else : ?>
                        <span class="badge text-bg-secondary">Utilisateur</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>