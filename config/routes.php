<?php

/**
 * Déclaration des routes de l'application.
 *
 * Conventions appliquées :
 * - les routes littérales sont déclarées avant les routes à paramètres,
 *   afin qu'un segment fixe ne soit jamais interprété comme un paramètre,
 * - les opérations d'écriture en base sont exposées en POST uniquement,
 *   une requête GET ne devant jamais modifier de données,
 * - le motif :id n'accepte que des chiffres : une URL contenant un
 *   identifiant non numérique retombe en 404 sans atteindre le contrôleur.
 *
 * Le contrôle des droits n'est pas assuré ici mais dans les contrôleurs,
 * au début de chaque méthode.
 *
 * @var \Buki\Router\Router $router
 */

// ---------------------------------------------------------------------
// Accès public
// ---------------------------------------------------------------------

$router->get('/', 'HomeController@index');

$router->get('/connexion', 'AuthController@formulaire');
$router->post('/connexion', 'AuthController@connexion');
$router->get('/deconnexion', 'AuthController@deconnexion');

// ---------------------------------------------------------------------
// Trajets — utilisateur authentifié
//
// La modification et la suppression sont en outre réservées à l'auteur
// du trajet, l'administrateur disposant de tous les droits.
// ---------------------------------------------------------------------

$router->get('/trajets/creer', 'TrajetController@formulaireCreation');
$router->post('/trajets/creer', 'TrajetController@creer');

$router->get('/trajets/:id/modifier', 'TrajetController@formulaireModification');
$router->post('/trajets/:id/modifier', 'TrajetController@modifier');

$router->post('/trajets/:id/supprimer', 'TrajetController@supprimer');

// ---------------------------------------------------------------------
// Administration — rôle admin exclusivement
// ---------------------------------------------------------------------

$router->get('/admin', 'AdminController@index');

// Utilisateurs : consultation seule, les employés provenant du système RH
$router->get('/admin/utilisateurs', 'AdminController@utilisateurs');

// Agences : seul l'administrateur peut modifier la liste des villes
$router->get('/admin/agences', 'AdminController@agences');
$router->get('/admin/agences/creer', 'AdminController@formulaireCreationAgence');
$router->post('/admin/agences/creer', 'AdminController@creerAgence');
$router->get('/admin/agences/:id/modifier', 'AdminController@formulaireModificationAgence');
$router->post('/admin/agences/:id/modifier', 'AdminController@modifierAgence');
$router->post('/admin/agences/:id/supprimer', 'AdminController@supprimerAgence');

// Trajets : consultation de tous les trajets et suppression
$router->get('/admin/trajets', 'AdminController@trajets');