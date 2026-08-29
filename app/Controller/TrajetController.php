<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\AgenceModel;
use App\Model\TrajetModel;
use Core\Auth;
use Core\Controller;
use Core\Flash;
use Core\Validator;

/**
 * Gestion des trajets proposés par les utilisateurs.
 */
final class TrajetController extends Controller
{
    /**
     * Affiche le formulaire de création d'un trajet.
     */
    public function formulaireCreation(): string
    {
        $this->requireAuth();

        return $this->render('trajet/formulaire', [
            'agences' => (new AgenceModel())->findAllTriees(),
            'trajet'  => $this->trajetVide(),
            'erreurs' => [],
            'action'  => BASE_URL . '/trajets/creer',
            'titre'   => 'Proposer un trajet',
        ]);
    }

    /**
     * Enregistre un nouveau trajet.
     */
    public function creer(): string
    {
        $this->requireAuth();

        $donnees   = $this->extraireDonnees();
        $validator = $this->valider($donnees);

        if (!$validator->estValide()) {
            return $this->render('trajet/formulaire', [
                'agences' => (new AgenceModel())->findAllTriees(),
                'trajet'  => $donnees,
                'erreurs' => $validator->erreurs(),
                'action'  => BASE_URL . '/trajets/creer',
                'titre'   => 'Proposer un trajet',
            ]);
        }

        $donnees['utilisateur_id'] = Auth::id();

        (new TrajetModel())->create($donnees);

        Flash::success('Le trajet a été créé.');
        $this->redirect('/');
    }

    /**
     * Retourne un trajet vierge servant à initialiser le formulaire.
     *
     * @return array<string, mixed>
     */
    private function trajetVide(): array
    {
        return [
            'agence_depart_id'   => '',
            'agence_arrivee_id'  => '',
            'gdh_depart'         => '',
            'gdh_arrivee'        => '',
            'places_total'       => '',
            'places_disponibles' => '',
        ];
    }

    /**
     * Extrait et normalise les données du formulaire.
     *
     * @return array<string, mixed>
     */
    private function extraireDonnees(): array
    {
        return [
            'agence_depart_id'   => (int) ($_POST['agence_depart_id'] ?? 0),
            'agence_arrivee_id'  => (int) ($_POST['agence_arrivee_id'] ?? 0),
            'gdh_depart'         => str_replace('T', ' ', (string) ($_POST['gdh_depart'] ?? '')) . ':00',
            'gdh_arrivee'        => str_replace('T', ' ', (string) ($_POST['gdh_arrivee'] ?? '')) . ':00',
            'places_total'       => (int) ($_POST['places_total'] ?? 0),
            'places_disponibles' => (int) ($_POST['places_disponibles'] ?? 0),
        ];
    }

    /**
     * Applique les contrôles de cohérence exigés par le cahier des charges.
     *
     * @param array<string, mixed> $donnees
     */
    private function valider(array $donnees): Validator
    {
        $validator = new Validator();

        $depart  = strtotime((string) $donnees['gdh_depart']);
        $arrivee = strtotime((string) $donnees['gdh_arrivee']);

        $validator->verifier(
            $donnees['agence_depart_id'] > 0,
            'agence_depart_id',
            'Veuillez choisir une agence de départ.'
        );

        $validator->verifier(
            $donnees['agence_arrivee_id'] > 0,
            'agence_arrivee_id',
            'Veuillez choisir une agence d\'arrivée.'
        );

        $validator->verifier(
            $donnees['agence_depart_id'] !== $donnees['agence_arrivee_id'],
            'agence_arrivee_id',
            'L\'agence d\'arrivée doit être différente de l\'agence de départ.'
        );

        $validator->verifier(
            $depart !== false && $depart > time(),
            'gdh_depart',
            'La date de départ doit être postérieure à maintenant.'
        );

        $validator->verifier(
            $arrivee !== false && $depart !== false && $arrivee > $depart,
            'gdh_arrivee',
            'La date d\'arrivée doit être postérieure à la date de départ.'
        );

        $validator->verifier(
            $donnees['places_total'] > 0,
            'places_total',
            'Le nombre total de places doit être supérieur à zéro.'
        );

        $validator->verifier(
            $donnees['places_disponibles'] >= 0
                && $donnees['places_disponibles'] <= $donnees['places_total'],
            'places_disponibles',
            'Le nombre de places disponibles ne peut pas dépasser le nombre total.'
        );

        return $validator;
    }
}