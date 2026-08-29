<?php

declare(strict_types=1);

namespace Tests\Model;

use App\Model\AgenceModel;
use PDOException;
use Tests\DatabaseTestCase;

/**
 * Tests des opérations d'écriture sur les agences.
 */
final class AgenceModelTest extends DatabaseTestCase
{
    private AgenceModel $modele;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modele = new AgenceModel();
    }

    public function testCreationRetourneIdentifiantEtEnregistreAgence(): void
    {
        $id = $this->modele->create(['nom' => 'Bordeaux']);

        self::assertGreaterThan(0, $id);

        $agence = $this->modele->findById($id);

        self::assertNotNull($agence);
        self::assertSame('Bordeaux', $agence['nom']);
    }

    public function testCreationRefuseUnNomDejaUtilise(): void
    {
        $this->expectException(PDOException::class);

        $this->modele->create(['nom' => 'Paris']);
    }

    public function testModificationChangeLeNom(): void
    {
        $modifiee = $this->modele->update($this->agenceParisId, ['nom' => 'Paris-Nord']);

        self::assertTrue($modifiee);

        $agence = $this->modele->findById($this->agenceParisId);

        self::assertNotNull($agence);
        self::assertSame('Paris-Nord', $agence['nom']);
    }

    public function testSuppressionRetireLAgence(): void
    {
        $supprimee = $this->modele->delete($this->agenceLyonId);

        self::assertTrue($supprimee);
        self::assertNull($this->modele->findById($this->agenceLyonId));
    }

    public function testSuppressionRefuseeSiDesTrajetsYFontReference(): void
    {
        $this->pdo->exec(sprintf(
            "INSERT INTO trajet (gdh_depart, gdh_arrivee, places_total,
                                 places_disponibles, utilisateur_id,
                                 agence_depart_id, agence_arrivee_id)
             VALUES ('%s', '%s', 4, 3, %d, %d, %d)",
            date('Y-m-d H:i:s', strtotime('+2 days')),
            date('Y-m-d H:i:s', strtotime('+2 days 5 hours')),
            $this->utilisateurId,
            $this->agenceParisId,
            $this->agenceLyonId
        ));

        self::assertSame(1, $this->modele->compterTrajets($this->agenceParisId));

        $this->expectException(PDOException::class);

        $this->modele->delete($this->agenceParisId);
    }

    public function testNomExisteIgnoreLAgenceEnCoursDeModification(): void
    {
        self::assertTrue($this->modele->nomExiste('Paris'));
        self::assertFalse($this->modele->nomExiste('Paris', $this->agenceParisId));
    }
}