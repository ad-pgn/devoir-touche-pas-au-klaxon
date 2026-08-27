<?php

declare(strict_types=1);

namespace Core;

/**
 * Messages flash affichés une seule fois après une redirection.
 *
 * Conformément au cahier des charges, chaque opération d'écriture en base
 * redirige l'utilisateur vers la liste correspondante en lui signalant
 * le résultat de l'opération.
 */
final class Flash
{
    /**
     * Clé sous laquelle les messages sont stockés en session.
     */
    private const KEY = 'flash_messages';

    /**
     * Type d'un message de réussite.
     */
    public const SUCCESS = 'success';

    /**
     * Type d'un message d'erreur.
     */
    public const ERROR = 'danger';

    /**
     * Enregistre un message destiné à la prochaine requête.
     *
     * @param string $type Type du message, de préférence l'une des
     *                     constantes de la classe.
     */
    public static function add(string $message, string $type = self::SUCCESS): void
    {
        /** @var array<int, array{type: string, message: string}> $messages */
        $messages = Session::get(self::KEY, []);

        $messages[] = ['type' => $type, 'message' => $message];

        Session::set(self::KEY, $messages);
    }

    /**
     * Raccourci pour un message de réussite.
     */
    public static function success(string $message): void
    {
        self::add($message, self::SUCCESS);
    }

    /**
     * Raccourci pour un message d'erreur.
     */
    public static function error(string $message): void
    {
        self::add($message, self::ERROR);
    }

    /**
     * Retourne les messages en attente et les efface.
     *
     * @return array<int, array{type: string, message: string}>
     */
    public static function pull(): array
    {
        /** @var array<int, array{type: string, message: string}> $messages */
        $messages = Session::pull(self::KEY, []);

        return $messages;
    }

    /**
     * Indique si des messages sont en attente d'affichage.
     */
    public static function has(): bool
    {
        /** @var array<int, mixed> $messages */
        $messages = Session::get(self::KEY, []);

        return $messages !== [];
    }
}