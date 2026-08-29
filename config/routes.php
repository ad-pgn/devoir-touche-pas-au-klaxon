<?php

/** @var \Buki\Router\Router $router */

$router->get('/', 'HomeController@index');

$router->get('/connexion', 'AuthController@formulaire');
$router->post('/connexion', 'AuthController@connexion');
$router->get('/deconnexion', 'AuthController@deconnexion');

$router->get('/trajets/creer', 'TrajetController@formulaireCreation');
$router->post('/trajets/creer', 'TrajetController@creer');