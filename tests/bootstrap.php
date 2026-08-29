<?php

/**
 * Amorçage de la suite de tests.
 *
 * Crée et alimente une base dédiée aux tests, distincte de celle de
 * l'application, puis branche les modèles dessus. Le schéma est repris
 * de sql/01_create.sql afin qu'il n'existe jamais deux définitions de
 * structure susceptibles de diverger : les instructions CREATE DATABASE
 * et USE y sont simplement retirées, la base de test étant sélectionnée
 * par la connexion elle-même.
 */

declare(strict_types=1);

use Core\Database;

require dirname(__DIR__) . '/vendor/autoload.php';

/** @var array{db: array<string, string|int>} $config */
$config = require dirname(__DIR__) . '/config/config.php';

$db         = $config['db'];
$nomBase    = $db['name'] . '_test';
$dsnServeur = sprintf('mysql:host=%s;port=%d;charset=%s', $db['host'], $db['port'], $db['charset']);

$pdo = new PDO($dsnServeur, (string) $db['user'], (string) $db['pass'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
]);

$pdo->exec(sprintf(
    'CREATE DATABASE IF NOT EXISTS %s CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
    $nomBase
));

$pdo->exec('USE ' . $nomBase);

$schema = (string) file_get_contents(dirname(__DIR__) . '/sql/01_create.sql');
$schema = (string) preg_replace('/CREATE\s+DATABASE.*?;/is', '', $schema);
$schema = (string) preg_replace('/USE\s+[^;]+;/i', '', $schema);

$pdo->exec($schema);

Database::useConnection($pdo);