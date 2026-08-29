<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\AgenceModel;
use App\Model\TrajetModel;
use App\Model\UserModel;
use Core\Controller;
use Core\Flash;

/**
 * Tableau de bord de l'administrateur.
 *
 * Toutes les actions de ce contrôleur sont réservées au rôle admin :
 * la vérification est faite au début de chaque méthode, aucune requête
 * ne pouvant se fier au contrôle effectué sur l'écran précédent.
 */
final class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord et ses indicateurs.
     */
    public function index(): string
    {
        $this->requireAdmin();

        return $this->render('admin/index', [
            'nbUtilisateurs' => (new UserModel())->count(),
            'nbAgences'      => (new AgenceModel())->count(),
            'nbTrajets'      => (new TrajetModel())->count(),
        ]);
    }

    /**
     * Liste les utilisateurs de l'application.
     *
     * Consultation uniquement : les employés proviennent du système RH
     * et ne sont ni créés, ni modifiés, ni supprimés par l'application.
     */
    public function utilisateurs(): string
    {
        $this->requireAdmin();

        return $this->render('admin/utilisateurs', [
            'utilisateurs' => (new UserModel())->findAllTries(),
        ]);
    }

    /**
     * Liste les agences et donne accès à leur gestion.
     */
    public function agences(): string
    {
        $this->requireAdmin();

        return $this->render('admin/agences', [
            'agences' => (new AgenceModel())->findAllTriees(),
        ]);
    }

    /**
     * Affiche le formulaire de création d'une agence.
     */
    public function formulaireCreationAgence(): string
    {
        $this->requireAdmin();

        return $this->render('admin/agence-formulaire', [
            'agence'  => ['id' => null, 'nom' => ''],
            'erreur'  => null,
            'action'  => BASE_URL . '/admin/agences/creer',
            'titre'   => 'Ajouter une agence',
        ]);
    }

    /**
     * Enregistre une nouvelle agence.
     */
    public function creerAgence(): string
    {
        $this->requireAdmin();

        $nom    = trim((string) ($_POST['nom'] ?? ''));
        $erreur = $this->validerAgence($nom);

        if ($erreur !== null) {
            return $this->render('admin/agence-formulaire', [
                'agence' => ['id' => null, 'nom' => $nom],
                'erreur' => $erreur,
                'action' => BASE_URL . '/admin/agences/creer',
                'titre'  => 'Ajouter une agence',
            ]);
        }

        (new AgenceModel())->create(['nom' => $nom]);

        Flash::success('L\'agence a été créée.');
        $this->redirect('/admin/agences');
    }

    /**
     * Affiche le formulaire de modification d'une agence.
     */
    public function formulaireModificationAgence(int $id): string
    {
        $this->requireAdmin();

        $agence = $this->agenceExistante($id);

        return $this->render('admin/agence-formulaire', [
            'agence' => $agence,
            'erreur' => null,
            'action' => BASE_URL . '/admin/agences/' . $id . '/modifier',
            'titre'  => 'Modifier l\'agence',
        ]);
    }

    /**
     * Enregistre les modifications apportées à une agence.
     */
    public function modifierAgence(int $id): string
    {
        $this->requireAdmin();
        $this->agenceExistante($id);

        $nom    = trim((string) ($_POST['nom'] ?? ''));
        $erreur = $this->validerAgence($nom, $id);

        if ($erreur !== null) {
            return $this->render('admin/agence-formulaire', [
                'agence' => ['id' => $id, 'nom' => $nom],
                'erreur' => $erreur,
                'action' => BASE_URL . '/admin/agences/' . $id . '/modifier',
                'titre'  => 'Modifier l\'agence',
            ]);
        }

        (new AgenceModel())->update($id, ['nom' => $nom]);

        Flash::success('L\'agence a été modifiée.');
        $this->redirect('/admin/agences');
    }

    /**
     * Supprime une agence.
     *
     * La suppression est refusée si des trajets y font référence : la
     * contrainte ON DELETE RESTRICT de la base l'interdirait de toute
     * façon, mais le contrôle préalable permet un message explicite.
     */
    public function supprimerAgence(int $id): never
    {
        $this->requireAdmin();

        $agence = $this->agenceExistante($id);
        $modele = new AgenceModel();

        $nbTrajets = $modele->compterTrajets($id);

        if ($nbTrajets > 0) {
            Flash::error(sprintf(
                'Impossible de supprimer l\'agence %s : %d trajet(s) y font référence.',
                $agence['nom'],
                $nbTrajets
            ));

            $this->redirect('/admin/agences');
        }

        $modele->delete($id);

        Flash::success('L\'agence a été supprimée.');
        $this->redirect('/admin/agences');
    }

    /**
     * Charge une agence ou interrompt la requête si elle n'existe pas.
     *
     * @return array<string, mixed>
     */
    private function agenceExistante(int $id): array
    {
        $agence = (new AgenceModel())->findById($id);

        if ($agence === null) {
            Flash::error('Cette agence n\'existe pas.');
            $this->redirect('/admin/agences');
        }

        return $agence;
    }

    /**
     * Valide le nom d'une agence.
     *
     * @param int|null $id Agence en cours de modification, le cas échéant.
     *
     * @return string|null Message d'erreur, ou null si le nom est valide.
     */
    private function validerAgence(string $nom, ?int $id = null): ?string
    {
        if ($nom === '') {
            return 'Le nom de l\'agence est obligatoire.';
        }

        if (mb_strlen($nom) > 100) {
            return 'Le nom de l\'agence ne peut pas dépasser 100 caractères.';
        }

        if ((new AgenceModel())->nomExiste($nom, $id)) {
            return 'Une agence porte déjà ce nom.';
        }

        return null;
    }

    /**
     * Liste l'intégralité des trajets pour l'administrateur.
     *
     * Contrairement à la page d'accueil, cette liste inclut les trajets
     * passés et les trajets complets : l'administrateur a accès à toutes
     * les informations.
     */
    public function trajets(): string
    {
        $this->requireAdmin();

        return $this->render('admin/trajets', [
            'trajets' => (new TrajetModel())->findTous(),
        ]);
    }
}