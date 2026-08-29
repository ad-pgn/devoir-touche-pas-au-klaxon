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

    /**
     * Indique si une agence porte déjà ce nom.
     *
     * @param int|null $exclureId Agence à ignorer lors d'une modification,
     *                            pour qu'elle ne se détecte pas elle-même.
     */
    public function nomExiste(string $nom, ?int $exclureId = null): bool
    {
        $sql    = 'SELECT COUNT(*) FROM agence WHERE nom = :nom';
        $params = ['nom' => $nom];

        if ($exclureId !== null) {
            $sql .= ' AND id <> :id';
            $params['id'] = $exclureId;
        }

        return (int) $this->query($sql, $params)->fetchColumn() > 0;
    }

     /**
     * Compte les trajets rattachés à une agence, au départ ou à l'arrivée.
     *
     * Les deux marqueurs sont distincts : les requêtes n'étant pas
     * émulées par PDO, MySQL exige une valeur par emplacement.
     */
    public function compterTrajets(int $id): int
    {
        $sql = 'SELECT COUNT(*) FROM trajet
                WHERE agence_depart_id = :id_depart
                   OR agence_arrivee_id = :id_arrivee';

        return (int) $this->query($sql, [
            'id_depart'  => $id,
            'id_arrivee' => $id,
        ])->fetchColumn();
    }
}