<?php
/**
 * Formulaire de connexion.
 *
 * @var \Core\Controller $this
 * @var string           $email  Email saisi, réaffiché en cas d'échec.
 * @var string|null      $erreur Message d'erreur éventuel.
 */

$erreur = $erreur ?? null;
?>
<h1 class="h3 mb-4">Connexion</h1>

<?php if ($erreur !== null) : ?>
    <div class="alert alert-danger"><?= $this->e($erreur) ?></div>
<?php endif; ?>

<form method="post" action="<?= BASE_URL ?>/connexion" class="col-12 col-md-6 col-lg-4">

    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <input type="email"
               class="form-control"
               id="email"
               name="email"
               value="<?= $this->e($email) ?>"
               required
               autofocus>
    </div>

    <div class="mb-4">
        <label for="mot_de_passe" class="form-label">Mot de passe</label>
        <input type="password"
               class="form-control"
               id="mot_de_passe"
               name="mot_de_passe"
               required>
    </div>

    <button type="submit" class="btn btn-primary">Se connecter</button>

</form>