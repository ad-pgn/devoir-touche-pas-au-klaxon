<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\TrajetModel;
use Core\Auth;
use Core\Controller;

/**
 * Contrôleur de la page d'accueil.
 */
final class HomeController extends Controller
{
    /**
     * Affiche les trajets à venir disposant de places libres.
     *
     * La page est accessible à tous. Le titre invite les visiteurs non
     * authentifiés à se connecter pour accéder au détail des trajets.
     */
    public function index(): string
    {
        $trajets = (new TrajetModel())->findDisponibles();

        $titre = Auth::check()
            ? 'Trajets proposés'
            : 'Pour obtenir plus d\'informations sur un trajet, veuillez vous connecter';

        return $this->render('home/index', [
            'titre'   => $titre,
            'trajets' => $trajets,
        ]);
    }
}