<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOStatement;

/**
 * Modèle générique fournissant les opérations CRUD communes.
 *
 * Les modèles concrets héritent de cette classe et se contentent de
 * déclarer la table qu'ils exploitent. Toute requête particulière est
 * ajoutée dans le modèle enfant.
 */
abstract class DefaultModel
{
    /**
     * Connexion PDO partagée.
     */
    protected PDO $db;

    /**
     * Nom de la table exploitée par le modèle. À déclarer dans l'enfant.
     */
    protected string $table = '';

    /**
     * Nom de la clé primaire de la table.
     */
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retourne toutes les lignes de la table.
     *
     * @param string|null $orderBy Colonne de tri, sans direction.
     * @param string      $direction ASC ou DESC.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(?string $orderBy = null, string $direction = 'ASC'): array
    {
        $sql = 'SELECT * FROM ' . $this->table;

        if ($orderBy !== null) {
            $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        /** @var array<int, array<string, mixed>> $rows */
        $rows = $this->db->query($sql)->fetchAll();

        return $rows;
    }

    /**
     * Retourne une ligne à partir de sa clé primaire.
     *
     * @return array<string, mixed>|null Null si aucune ligne ne correspond.
     */
    public function findById(int $id): ?array
    {
        $sql = 'SELECT * FROM ' . $this->table
             . ' WHERE ' . $this->primaryKey . ' = :id';

        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);

        /** @var array<string, mixed>|false $row */
        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    /**
     * Insère une ligne et retourne son identifiant.
     *
     * @param array<string, mixed> $data Couples colonne => valeur.
     */
    public function create(array $data): int
    {
        $columns      = array_keys($data);
        $placeholders = array_map(static fn (string $c): string => ':' . $c, $columns);

        $sql = 'INSERT INTO ' . $this->table
             . ' (' . implode(', ', $columns) . ')'
             . ' VALUES (' . implode(', ', $placeholders) . ')';

        $this->db->prepare($sql)->execute($data);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Met à jour une ligne identifiée par sa clé primaire.
     *
     * @param array<string, mixed> $data Couples colonne => valeur.
     *
     * @return bool True si au moins une ligne a été modifiée.
     */
    public function update(int $id, array $data): bool
    {
        $assignments = array_map(
            static fn (string $c): string => $c . ' = :' . $c,
            array_keys($data)
        );

        $sql = 'UPDATE ' . $this->table
             . ' SET ' . implode(', ', $assignments)
             . ' WHERE ' . $this->primaryKey . ' = :primary_key_value';

        $data['primary_key_value'] = $id;

        $statement = $this->db->prepare($sql);
        $statement->execute($data);

        return $statement->rowCount() > 0;
    }

    /**
     * Supprime une ligne identifiée par sa clé primaire.
     *
     * @return bool True si une ligne a été supprimée.
     */
    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM ' . $this->table
             . ' WHERE ' . $this->primaryKey . ' = :id';

        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }

    /**
     * Compte les lignes de la table.
     */
    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM ' . $this->table)
                              ->fetchColumn();
    }

    /**
     * Exécute une requête préparée et retourne le statement.
     *
     * Réservé aux modèles enfants pour leurs requêtes spécifiques.
     *
     * @param array<string, mixed> $params
     */
    protected function query(string $sql, array $params = []): PDOStatement
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement;
    }
}