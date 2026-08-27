<?php

declare(strict_types=1);

namespace Core;

/**
 * Gestion de l'utilisateur authentifié et des contrôles d'accès.
 *
 * L'utilisateur connecté est conservé en session sous forme de tableau.
 * Aucune donnée sensible n'y est stockée : le mot de passe haché est
 * volontairement écarté au moment de la connexion.
 */
final class Auth
{
    /**
     * Clé de session contenant l'utilisateur authentifié.
     */
    private const KEY = 'utilisateur';

    /**
     * Valeur du rôle administrateur, telle que stockée en base.
     */
    public const ROLE_ADMIN = 'admin';

    /**
     * Ouvre la session applicative pour un utilisateur.
     *
     * @param array<string, mixed> $utilisateur Ligne de la table utilisateur.
     */
    public static function login(array $utilisateur): void
    {
        unset($utilisateur['mot_de_passe']);

        Session::regenerate();
        Session::set(self::KEY, $utilisateur);
    }

    /**
     * Ferme la session applicative.
     */
    public static function logout(): void
    {
        Session::destroy();
    }

    /**
     * Retourne l'utilisateur connecté, ou null s'il n'y en a pas.
     *
     * @return array<string, mixed>|null
     */
    public static function user(): ?array
    {
        /** @var array<string, mixed>|null $utilisateur */
        $utilisateur = Session::get(self::KEY);

        return $utilisateur;
    }

    /**
     * Indique si un utilisateur est connecté.
     */
    public static function check(): bool
    {
        return self::user() !== null;
    }

    /**
     * Retourne l'identifiant de l'utilisateur connecté, ou null.
     */
    public static function id(): ?int
    {
        $utilisateur = self::user();

        return $utilisateur === null ? null : (int) $utilisateur['id'];
    }

    /**
     * Indique si l'utilisateur connecté est administrateur.
     */
    public static function isAdmin(): bool
    {
        $utilisateur = self::user();

        return $utilisateur !== null
            && $utilisateur['role'] === self::ROLE_ADMIN;
    }

    /**
     * Indique si l'utilisateur connecté est l'auteur d'un trajet.
     *
     * L'administrateur est considéré comme habilité sur tous les trajets,
     * conformément au cahier des charges.
     */
    public static function owns(int $utilisateurId): bool
    {
        return self::isAdmin() || self::id() === $utilisateurId;
    }
}