<?php

declare(strict_types=1);

namespace App\Model;

use Core\DefaultModel;

/**
 * Accès aux données de la table agence.
 */
final class AgenceModel extends DefaultModel
{
    /**
     * Table exploitée par le modèle.
     */
    protected string $table = 'agence';

    /**
     * Retourne toutes les agences, triées par nom.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAllTriees(): array
    {
        return $this->findAll('nom');
    }
}