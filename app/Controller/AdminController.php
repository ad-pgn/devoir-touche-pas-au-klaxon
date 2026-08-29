<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\AgenceModel;
use App\Model\TrajetModel;
use App\Model\UserModel;
use Core\Controller;

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
}