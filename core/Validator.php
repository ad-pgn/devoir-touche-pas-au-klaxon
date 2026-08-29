<?php

declare(strict_types=1);

namespace Core;

/**
 * Collecte les erreurs de validation d'un formulaire.
 *
 * Chaque règle est évaluée indépendamment afin que l'utilisateur voie
 * l'ensemble de ses erreurs en une seule fois plutôt qu'une par une.
 */
final class Validator
{
    /**
     * Messages d'erreur indexés par nom de champ.
     *
     * @var array<string, string>
     */
    private array $erreurs = [];

    /**
     * Enregistre une erreur si la condition n'est pas remplie.
     *
     * @param bool $condition Condition qui doit être vraie pour valider.
     */
    public function verifier(bool $condition, string $champ, string $message): void
    {
        if (!$condition && !isset($this->erreurs[$champ])) {
            $this->erreurs[$champ] = $message;
        }
    }

    /**
     * Indique si aucune erreur n'a été relevée.
     */
    public function estValide(): bool
    {
        return $this->erreurs === [];
    }

    /**
     * Retourne les erreurs relevées.
     *
     * @return array<string, string>
     */
    public function erreurs(): array
    {
        return $this->erreurs;
    }
}