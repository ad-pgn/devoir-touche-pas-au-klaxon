<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Controller;

/**
 * Contrôleur de la page d'accueil.
 */
final class HomeController extends Controller
{
    /**
     * Affiche la liste des trajets disponibles.
     *
     * Version provisoire : la récupération des trajets sera implémentée
     * à l'issue #19.
     */
    public function index(): string
    {
        return $this->render('home/index', [
            'titre' => 'Pour obtenir plus d\'informations sur un trajet, veuillez vous connecter',
        ]);
    }
}