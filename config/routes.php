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