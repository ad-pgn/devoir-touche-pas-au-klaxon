<?php

declare(strict_types=1);

namespace App\Model;

use Core\DefaultModel;

/**
 * Accès aux données de la table trajet.
 *
 * Les requêtes de ce modèle joignent systématiquement les agences et
 * l'auteur du trajet : les vues ont besoin des noms de villes et des
 * coordonnées du conducteur, pas de leurs identifiants.
 */
final class TrajetModel extends DefaultModel
{
    /**
     * Table exploitée par le modèle.
     */
    protected string $table = 'trajet';

    /**
     * Colonnes communes à toutes les requêtes de lecture.
     */
    private const SELECT = '
        SELECT  t.id,
                t.gdh_depart,
                t.gdh_arrivee,
                t.places_total,
                t.places_disponibles,
                t.utilisateur_id,
                t.agence_depart_id,
                t.agence_arrivee_id,
                d.nom  AS agence_depart,
                a.nom  AS agence_arrivee,
                u.nom       AS auteur_nom,
                u.prenom    AS auteur_prenom,
                u.telephone AS auteur_telephone,
                u.email     AS auteur_email
        FROM trajet t
        INNER JOIN agence      d ON d.id = t.agence_depart_id
        INNER JOIN agence      a ON a.id = t.agence_arrivee_id
        INNER JOIN utilisateur u ON u.id = t.utilisateur_id
    ';

    /**
     * Retourne les trajets proposés sur la page d'accueil.
     *
     * Conformément au cahier des charges, seuls apparaissent les trajets
     * à venir pour lesquels il reste au moins une place, triés par date
     * de départ croissante.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findDisponibles(): array
    {
        $sql = self::SELECT . '
            WHERE t.places_disponibles > 0
              AND t.gdh_depart > NOW()
            ORDER BY t.gdh_depart ASC
        ';

        /** @var array<int, array<string, mixed>> $rows */
        $rows = $this->query($sql)->fetchAll();

        return $rows;
    }

    /**
     * Retourne tous les trajets, y compris passés et complets.
     *
     * Destiné au tableau de bord de l'administrateur.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findTous(): array
    {
        $sql = self::SELECT . ' ORDER BY t.gdh_depart DESC';

        /** @var array<int, array<string, mixed>> $rows */
        $rows = $this->query($sql)->fetchAll();

        return $rows;
    }

    /**
     * Retourne un trajet enrichi des noms d'agences et de son auteur.
     *
     * @return array<string, mixed>|null Null si le trajet n'existe pas.
     */
    public function findDetail(int $id): ?array
    {
        $sql = self::SELECT . ' WHERE t.id = :id';

        /** @var array<string, mixed>|false $row */
        $row = $this->query($sql, ['id' => $id])->fetch();

        return $row === false ? null : $row;
    }
}