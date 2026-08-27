<?php

declare(strict_types=1);

use Buki\Router\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Préfixe d'URL de l'application, calculé dynamiquement.
 * Vaut "/devoir-touche-pas-au-klaxon/public" en développement local
 * et "" si l'application est servie depuis la racine du domaine.
 */
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));

$router = new Router([
    'base_folder' => str_replace('\\', '/', __DIR__),
    'paths'       => ['controllers' => dirname(__DIR__) . '/app/Controller'],
    'namespaces'  => ['controllers' => 'App\\Controller'],
    'debug'       => true,
]);

require dirname(__DIR__) . '/config/routes.php';

$router->run();