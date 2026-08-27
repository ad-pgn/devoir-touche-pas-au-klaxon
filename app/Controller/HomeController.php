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
        // Données provisoires, remplacées par la requête réelle à l'issue #19.
        $trajets = [
            [
                'id' => 1, 'agence_depart' => 'Paris', 'agence_arrivee' => 'Lyon',
                'gdh_depart' => '2026-09-02 08:00:00', 'gdh_arrivee' => '2026-09-02 13:00:00',
                'places_total' => 4, 'places_disponibles' => 3, 'utilisateur_id' => 1,
                'auteur_nom' => 'Martin', 'auteur_prenom' => 'Alexandre',
                'auteur_telephone' => '0612345678', 'auteur_email' => 'alexandre.martin@email.fr',
            ],
            [
                'id' => 2, 'agence_depart' => 'Lyon', 'agence_arrivee' => 'Marseille',
                'gdh_depart' => '2026-09-03 09:30:00', 'gdh_arrivee' => '2026-09-03 12:30:00',
                'places_total' => 3, 'places_disponibles' => 1, 'utilisateur_id' => 2,
                'auteur_nom' => 'Dubois', 'auteur_prenom' => 'Sophie',
                'auteur_telephone' => '0698765432', 'auteur_email' => 'sophie.dubois@email.fr',
            ],
        ];

        return $this->render('home/index', [
            'titre'   => 'Pour obtenir plus d\'informations sur un trajet, veuillez vous connecter',
            'trajets' => $trajets,
        ]);
    }
}