<?php

declare(strict_types=1);

namespace Tests;

use Core\Database;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Classe de base des tests touchant à la base de données.
 *
 * Chaque test part d'un état connu : les tables sont vidées puis
 * réalimentées avec un jeu minimal avant chaque méthode de test, ce qui
 * garantit qu'aucun test ne dépend de l'ordre d'exécution ni des
 * effets de bord d'un autre.
 */
abstract class DatabaseTestCase extends TestCase
{
    /**
     * Connexion à la base de test.
     */
    protected PDO $pdo;

    /**
     * Identifiant de l'utilisateur créé pour les tests.
     */
    protected int $utilisateurId;

    /**
     * Identifiant de la première agence créée pour les tests.
     */
    protected int $agenceParisId;

    /**
     * Identifiant de la seconde agence créée pour les tests.
     */
    protected int $agenceLyonId;

    protected function setUp(): void
    {
        $this->pdo = Database::getInstance();

        $this->viderTables();
        $this->creerJeuMinimal();
    }

    /**
     * Vide les trois tables et réinitialise les compteurs.
     */
    private function viderTables(): void
    {
        $this->pdo->exec('DELETE FROM trajet');
        $this->pdo->exec('DELETE FROM utilisateur');
        $this->pdo->exec('DELETE FROM agence');

        $this->pdo->exec('ALTER TABLE trajet AUTO_INCREMENT = 1');
        $this->pdo->exec('ALTER TABLE utilisateur AUTO_INCREMENT = 1');
        $this->pdo->exec('ALTER TABLE agence AUTO_INCREMENT = 1');
    }

    /**
     * Insère un utilisateur et deux agences, socle commun aux tests.
     */
    private function creerJeuMinimal(): void
    {
        $this->pdo->exec("
            INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role)
            VALUES ('Test', 'Utilisateur', 'test@email.fr', '0600000000', 'hash', 'utilisateur')
        ");
        $this->utilisateurId = (int) $this->pdo->lastInsertId();

        $this->pdo->exec("INSERT INTO agence (nom) VALUES ('Paris')");
        $this->agenceParisId = (int) $this->pdo->lastInsertId();

        $this->pdo->exec("INSERT INTO agence (nom) VALUES ('Lyon')");
        $this->agenceLyonId = (int) $this->pdo->lastInsertId();
    }

    /**
     * Construit un jeu de données valide pour créer un trajet.
     *
     * @return array<string, mixed>
     */
    protected function donneesTrajet(): array
    {
        return [
            'gdh_depart'         => date('Y-m-d H:i:s', strtotime('+2 days')),
            'gdh_arrivee'        => date('Y-m-d H:i:s', strtotime('+2 days 5 hours')),
            'places_total'       => 4,
            'places_disponibles' => 3,
            'utilisateur_id'     => $this->utilisateurId,
            'agence_depart_id'   => $this->agenceParisId,
            'agence_arrivee_id'  => $this->agenceLyonId,
        ];
    }
}