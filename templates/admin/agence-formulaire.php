<?php
/**
 * Formulaire de création et de modification d'une agence.
 *
 * @var \Core\Controller $this
 * @var array<string, mixed> $agence
 * @var string|null $erreur
 * @var string $action
 * @var string $titre
 */
?>
<h1 class="h3 mb-4"><?= $this->e($titre) ?></h1>

<form method="post" action="<?= $this->e($action) ?>" class="col-12 col-md-6">

    <div class="mb-4">
        <label for="nom" class="form-label">Nom de l'agence</label>
        <input type="text"
               class="form-control <?= $erreur !== null ? 'is-invalid' : '' ?>"
               id="nom"
               name="nom"
               value="<?= $this->e($agence['nom']) ?>"
               maxlength="100"
               required
               autofocus>
        <?php if ($erreur !== null) : ?>
            <div class="invalid-feedback"><?= $this->e($erreur) ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="<?= BASE_URL ?>/admin/agences" class="btn btn-secondary">Annuler</a>

</form>