<?php

declare(strict_types=1);

namespace App\Model;

use Core\DefaultModel;

/**
 * Accès aux données de la table utilisateur.
 *
 * Les employés étant importés du système RH, ce modèle ne propose ni
 * création, ni modification, ni suppression : seules les lectures
 * nécessaires à l'authentification et à l'affichage sont exposées.
 */
final class UserModel extends DefaultModel
{
    /**
     * Table exploitée par le modèle.
     */
    protected string $table = 'utilisateur';

    /**
     * Recherche un utilisateur par son adresse email.
     *
     * @return array<string, mixed>|null Null si aucun compte ne correspond.
     */
    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT * FROM utilisateur WHERE email = :email';

        /** @var array<string, mixed>|false $row */
        $row = $this->query($sql, ['email' => $email])->fetch();

        return $row === false ? null : $row;
    }

    /**
     * Retourne tous les utilisateurs, triés par nom puis prénom.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAllTries(): array
    {
        $sql = 'SELECT id, nom, prenom, email, telephone, role
                FROM utilisateur
                ORDER BY nom, prenom';

        /** @var array<int, array<string, mixed>> $rows */
        $rows = $this->query($sql)->fetchAll();

        return $rows;
    }
}