<?php
/**
 * Formulaire de création et de modification d'un trajet.
 *
 * Les coordonnées de l'auteur sont affichées à titre informatif et ne
 * sont pas modifiables : elles proviennent du système RH.
 *
 * @var \Core\Controller $this
 * @var array<int, array<string, mixed>> $agences
 * @var array<string, mixed> $trajet  Valeurs à pré-remplir.
 * @var array<string, string> $erreurs Messages indexés par champ.
 * @var string $action URL de soumission.
 * @var string $titre
 */

$utilisateur = $this->utilisateur();

/**
 * Convertit un GDH de la base au format attendu par datetime-local.
 */
$pourInput = static function (mixed $gdh): string {
    $gdh = (string) $gdh;

    return $gdh === '' ? '' : date('Y-m-d\TH:i', (int) strtotime($gdh));
};
?>
<h1 class="h3 mb-4"><?= $this->e($titre) ?></h1>

<form method="post" action="<?= $this->e($action) ?>" class="col-12 col-lg-8">

    <fieldset class="mb-4">
        <legend class="h6 text-muted">Personne à contacter</legend>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control"
                       value="<?= $this->e($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?>" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" class="form-control"
                       value="<?= $this->e($utilisateur['telephone']) ?>" disabled>
            </div>
            <div class="col-md-12">
                <label class="form-label">Email</label>
                <input type="text" class="form-control"
                       value="<?= $this->e($utilisateur['email']) ?>" disabled>
            </div>
        </div>
    </fieldset>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="agence_depart_id" class="form-label">Agence de départ</label>
            <select class="form-select <?= isset($erreurs['agence_depart_id']) ? 'is-invalid' : '' ?>"
                    id="agence_depart_id" name="agence_depart_id" required>
                <option value="">Choisir une agence</option>
                <?php foreach ($agences as $agence) : ?>
                    <option value="<?= (int) $agence['id'] ?>"
                        <?= (int) $trajet['agence_depart_id'] === (int) $agence['id'] ? 'selected' : '' ?>>
                        <?= $this->e($agence['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($erreurs['agence_depart_id'])) : ?>
                <div class="invalid-feedback"><?= $this->e($erreurs['agence_depart_id']) ?></div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <label for="agence_arrivee_id" class="form-label">Agence d'arrivée</label>
            <select class="form-select <?= isset($erreurs['agence_arrivee_id']) ? 'is-invalid' : '' ?>"
                    id="agence_arrivee_id" name="agence_arrivee_id" required>
                <option value="">Choisir une agence</option>
                <?php foreach ($agences as $agence) : ?>
                    <option value="<?= (int) $agence['id'] ?>"
                        <?= (int) $trajet['agence_arrivee_id'] === (int) $agence['id'] ? 'selected' : '' ?>>
                        <?= $this->e($agence['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($erreurs['agence_arrivee_id'])) : ?>
                <div class="invalid-feedback"><?= $this->e($erreurs['agence_arrivee_id']) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="gdh_depart" class="form-label">Date et heure de départ</label>
            <input type="datetime-local"
                   class="form-control <?= isset($erreurs['gdh_depart']) ? 'is-invalid' : '' ?>"
                   id="gdh_depart" name="gdh_depart"
                   value="<?= $this->e($pourInput($trajet['gdh_depart'])) ?>" required>
            <?php if (isset($erreurs['gdh_depart'])) : ?>
                <div class="invalid-feedback"><?= $this->e($erreurs['gdh_depart']) ?></div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <label for="gdh_arrivee" class="form-label">Date et heure d'arrivée</label>
            <input type="datetime-local"
                   class="form-control <?= isset($erreurs['gdh_arrivee']) ? 'is-invalid' : '' ?>"
                   id="gdh_arrivee" name="gdh_arrivee"
                   value="<?= $this->e($pourInput($trajet['gdh_arrivee'])) ?>" required>
            <?php if (isset($erreurs['gdh_arrivee'])) : ?>
                <div class="invalid-feedback"><?= $this->e($erreurs['gdh_arrivee']) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="places_total" class="form-label">Nombre total de places</label>
            <input type="number" min="1"
                   class="form-control <?= isset($erreurs['places_total']) ? 'is-invalid' : '' ?>"
                   id="places_total" name="places_total"
                   value="<?= $this->e($trajet['places_total']) ?>" required>
            <?php if (isset($erreurs['places_total'])) : ?>
                <div class="invalid-feedback"><?= $this->e($erreurs['places_total']) ?></div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <label for="places_disponibles" class="form-label">Places disponibles</label>
            <input type="number" min="0"
                   class="form-control <?= isset($erreurs['places_disponibles']) ? 'is-invalid' : '' ?>"
                   id="places_disponibles" name="places_disponibles"
                   value="<?= $this->e($trajet['places_disponibles']) ?>" required>
            <?php if (isset($erreurs['places_disponibles'])) : ?>
                <div class="invalid-feedback"><?= $this->e($erreurs['places_disponibles']) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="<?= BASE_URL ?>/" class="btn btn-secondary">Annuler</a>

</form>