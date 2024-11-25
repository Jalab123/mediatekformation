<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de tests du repository des formations.
 *
 * @author pilou
 */
class FormationRepositoryTest extends KernelTestCase {
    
    /**
     * Test vérifiant l'accès au repository.
     * @return FormationRepository
     */
    public function recupRepository(): FormationRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }
    
    /**
     * Test vérifiant le nombre de formations.
     */
    public function testNbFormations(){
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(237, $nbFormations);
    }
    
    /**
     * Fonction permettant d'ajouter une nouvelle formation.
     * @return Formation
     */
    public function newFormation(): Formation {
        $formation = (new Formation())
                ->setTitle("Eclipse n°9 : Test")
                ->setPublishedAt(new DateTime("2024-11-15"));
        return $formation;
    }
    
    /**
     * Test add formation.
     */
    public function testAddFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]));
    }
    
    /**
     * Test remove formation.
     */
    public function testRemoveFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]));
    }
    
    /**
     * Test findAllBorderBy.
     */
    public function testFindAllOrderBy(){
        $repository = $this->recupRepository();
        $nbFormationsTotal = $repository->count([]);
        $formations = $repository->findAllOrderBy("title", "ASC");
        $nbFormationsTrouvees = count($formations);
        $this->assertEquals($nbFormationsTotal, $nbFormationsTrouvees);
        $this->assertEquals("Android Studio (complément n°1) : Navigation Drawer et Fragment", $formations[0]->getTitle());
    }
    
    /**
     * Test findByContainValue.
     */
    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue("title", "Eclipse n°9 : Test");
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
        $this->assertEquals("Eclipse n°9 : Test", $formations[0]->getTitle());
    }
    
    /**
     * Test findAllLasted.
     */
    public function testFindAllLasted(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllLasted(3);
        $nbFormationsTrouvees = count($formations);
        $this->assertEquals(3, $nbFormationsTrouvees);
        $this->assertEquals("Eclipse n°9 : Test", $formations[0]->getTitle());
    }
    
    /**
     * Test findAllForOnePlaylist.
     */
    public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $formations = $repository->findAllForOnePlaylist(1);
        $nbFormationsTrouvees = count($formations);
        $this->assertEquals(8, $nbFormationsTrouvees);
    }
}
