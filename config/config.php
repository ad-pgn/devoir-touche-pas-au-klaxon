<?php

declare(strict_types=1);

/**
 * Configuration de l'application.
 *
 * Les valeurs ci-dessous sont celles d'un environnement XAMPP standard.
 * Pour les adapter sans modifier ce fichier versionné, créez un fichier
 * config/config.local.php retournant un tableau de même structure :
 * ses valeurs écraseront celles définies ici.
 */

$config = [
    'db' => [
        'host'    => 'localhost',
        'port'    => 3306,
        'name'    => 'touche_pas_au_klaxon',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],
];

$localConfigFile = __DIR__ . '/config.local.php';

if (is_file($localConfigFile)) {
    $config = array_replace_recursive($config, require $localConfigFile);
}

return $config;