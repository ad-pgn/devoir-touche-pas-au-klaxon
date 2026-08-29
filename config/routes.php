<?php

/** @var \Buki\Router\Router $router */

$router->get('/', 'HomeController@index');

$router->get('/connexion', 'AuthController@formulaire');
$router->post('/connexion', 'AuthController@connexion');

$router->get('/deconnexion', 'AuthController@deconnexion');

$router->get('/trajets/creer', 'TrajetController@formulaireCreation');
$router->post('/trajets/creer', 'TrajetController@creer');

$router->get('/trajets/:id/modifier', 'TrajetController@formulaireModification');
$router->post('/trajets/:id/modifier', 'TrajetController@modifier');

$router->post('/trajets/:id/supprimer', 'TrajetController@supprimer');

$router->get('/admin', 'AdminController@index');

$router->get('/admin/utilisateurs', 'AdminController@utilisateurs');

$router->get('/admin/agences', 'AdminController@agences');
$router->get('/admin/agences/creer', 'AdminController@formulaireCreationAgence');
$router->post('/admin/agences/creer', 'AdminController@creerAgence');
$router->get('/admin/agences/:id/modifier', 'AdminController@formulaireModificationAgence');
$router->post('/admin/agences/:id/modifier', 'AdminController@modifierAgence');
$router->post('/admin/agences/:id/supprimer', 'AdminController@supprimerAgence');