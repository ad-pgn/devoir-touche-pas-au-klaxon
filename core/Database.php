<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Point d'accès unique à la base de données.
 *
 * Cette classe applique le patron Singleton : une seule connexion PDO est
 * ouverte par requête HTTP et partagée par l'ensemble des modèles.
 */
final class Database
{
    /**
     * Instance PDO partagée, créée à la première demande.
     */
    private static ?PDO $instance = null;

    /**
     * Empêche l'instanciation : la classe ne s'utilise que statiquement.
     */
    private function __construct()
    {
    }

    /**
     * Retourne la connexion PDO, en l'ouvrant si nécessaire.
     *
     * @throws RuntimeException Si la connexion échoue.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            /** @var array{db: array<string, string|int>} $config */
            $config = require dirname(__DIR__) . '/config/config.php';
            self::$instance = self::connect($config['db']);
        }

        return self::$instance;
    }

    /**
     * Ouvre la connexion PDO à partir des paramètres de configuration.
     *
     * @param array<string, string|int> $db Paramètres de connexion.
     *
     * @throws RuntimeException Si la connexion échoue.
     */
    private static function connect(array $db): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $db['host'],
            $db['port'],
            $db['name'],
            $db['charset']
        );

        try {
            return new PDO($dsn, (string) $db['user'], (string) $db['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException(
                'Connexion à la base de données impossible.',
                0,
                $e
            );
        }
    }
}