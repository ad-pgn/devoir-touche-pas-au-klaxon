<?php

declare(strict_types=1);

namespace Tests\Model;

use App\Model\TrajetModel;
use PDOException;
use Tests\DatabaseTestCase;

/**
 * Tests des opérations d'écriture sur les trajets.
 *
 * Les contraintes de cohérence vérifiées ici sont celles portées par la
 * base : elles constituent le dernier rempart, indépendamment des
 * contrôles réalisés côté contrôleur.
 */
final class TrajetModelTest extends DatabaseTestCase
{
    private TrajetModel $modele;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modele = new TrajetModel();
    }

    public function testCreationEnregistreLeTrajetAvecSesJointures(): void
    {
        $id = $this->modele->create($this->donneesTrajet());

        self::assertGreaterThan(0, $id);

        $trajet = $this->modele->findDetail($id);

        self::assertNotNull($trajet);
        self::assertSame('Paris', $trajet['agence_depart']);
        self::assertSame('Lyon', $trajet['agence_arrivee']);
        self::assertSame('Utilisateur', $trajet['auteur_prenom']);
    }

    public function testCreationRefuseeSiAgencesIdentiques(): void
    {
        $donnees = $this->donneesTrajet();
        $donnees['agence_arrivee_id'] = $donnees['agence_depart_id'];

        $this->expectException(PDOException::class);

        $this->modele->create($donnees);
    }

    public function testCreationRefuseeSiArriveeAvantDepart(): void
    {
        $donnees = $this->donneesTrajet();
        $donnees['gdh_arrivee'] = date('Y-m-d H:i:s', strtotime('+1 day'));

        $this->expectException(PDOException::class);

        $this->modele->create($donnees);
    }

    public function testCreationRefuseeSiPlacesDisponiblesSuperieuresAuTotal(): void
    {
        $donnees = $this->donneesTrajet();
        $donnees['places_disponibles'] = 10;

        $this->expectException(PDOException::class);

        $this->modele->create($donnees);
    }

    public function testModificationMetAJourLesPlaces(): void
    {
        $id = $this->modele->create($this->donneesTrajet());

        $modifie = $this->modele->update($id, ['places_disponibles' => 1]);

        self::assertTrue($modifie);

        $trajet = $this->modele->findDetail($id);

        self::assertNotNull($trajet);
        self::assertSame(1, (int) $trajet['places_disponibles']);
    }

    public function testSuppressionRetireLeTrajet(): void
    {
        $id = $this->modele->create($this->donneesTrajet());

        self::assertTrue($this->modele->delete($id));
        self::assertNull($this->modele->findDetail($id));
    }

    public function testTrajetsPassesSontExclusDesTrajetsDisponibles(): void
    {
        $donnees = $this->donneesTrajet();
        $donnees['gdh_depart']  = date('Y-m-d H:i:s', strtotime('-2 days'));
        $donnees['gdh_arrivee'] = date('Y-m-d H:i:s', strtotime('-2 days +5 hours'));

        $this->modele->create($donnees);
        $this->modele->create($this->donneesTrajet());

        self::assertCount(1, $this->modele->findDisponibles());
        self::assertCount(2, $this->modele->findTous());
    }

    public function testTrajetsCompletsSontExclusDesTrajetsDisponibles(): void
    {
        $donnees = $this->donneesTrajet();
        $donnees['places_disponibles'] = 0;

        $this->modele->create($donnees);

        self::assertCount(0, $this->modele->findDisponibles());
    }
}