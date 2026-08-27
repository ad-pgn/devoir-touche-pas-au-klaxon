<?php

declare(strict_types=1);

namespace Core;

/**
 * Encapsule l'accès à la session PHP.
 *
 * Toutes les interactions avec $_SESSION passent par cette classe, ce qui
 * évite de manipuler la superglobale un peu partout dans l'application.
 */
final class Session
{
    /**
     * Démarre la session si elle ne l'est pas déjà.
     *
     * Appelée automatiquement par les autres méthodes : il n'est pas
     * nécessaire de l'invoquer explicitement.
     */
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Enregistre une valeur en session.
     */
    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Lit une valeur en session.
     *
     * @param mixed $default Valeur retournée si la clé n'existe pas.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();

        return $_SESSION[$key] ?? $default;
    }

    /**
     * Indique si une clé existe en session.
     */
    public static function has(string $key): bool
    {
        self::start();

        return isset($_SESSION[$key]);
    }

    /**
     * Supprime une valeur de la session.
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Lit une valeur puis la supprime immédiatement.
     *
     * C'est le mécanisme sur lequel reposent les messages flash : la
     * donnée n'est disponible que pour une seule lecture.
     */
    public static function pull(string $key, mixed $default = null): mixed
    {
        $value = self::get($key, $default);
        self::remove($key);

        return $value;
    }

    /**
     * Détruit intégralement la session et son cookie.
     */
    public static function destroy(): void
    {
        self::start();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    /**
     * Régénère l'identifiant de session en conservant les données.
     *
     * À appeler juste après une connexion réussie pour se prémunir
     * contre la fixation de session.
     */
    public static function regenerate(): void
    {
        self::start();
        session_regenerate_id(true);
    }
}