<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest
 *
 * @author pilou
 */
class FormationRepositoryTest extends KernelTestCase{
    //put your code here
    
    /**
     * Récupère le repository de Formation
     * @return FormationRepository
     */
    public function recupRepository(): FormationRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }
    
    /**
     * Test sur le nombre de formations
     */
    public function testNbFormations(){
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(237, $nbFormations);
    }
    
    /**
     * Création d'une instance de Formation avec title et publishedAt
     * @return Formation
     */
    public function newFormation(): Formation{
        $formation = (new Formation())
                ->setTitle("Formation test")
                ->setPublishedAt(new DateTime("2023-01-28 11:01:57"));
        return $formation;
    }
    
    /**
     * Test sur l'ajout d'une formation
     */
    public function testAddFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    /**
     * Test sur la suppression d'une formation
     */
    public function testRemoveFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
    /**
     * Test sur le rangement (par ordre alphabétique)
     */
    public function testFindAllOrderBy(){
        // on vérifie d'abord que le nombre de formation est exact
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals($nbFormations, $repository->count([]));
        // on teste ensuite en rangeant par ordre alphabétique du titre
        $formations = $repository->findAllOrderBy('title', 'ASC');
        $this->assertEquals("Android Studio (complément n°1) : Navigation Drawer et Fragment", $formations[0]->getTitle());
    }
    
    /**
     * Test sur le rangement en précisant la table (par ordre alphabétique)
     */
    public function testFindAllOrderByTable(){
        // on vérifie d'abord que le nombre de formations est exact
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals($nbFormations, $repository->count([]));
        // on teste ensuite en rangeant par ordre alphabétique du titre
        $formations = $repository->findAllOrderBy('title', 'ASC', 'formation');
        $this->assertEquals("Android Studio (complément n°1) : Navigation Drawer et Fragment", $formations[0]->getTitle());
    }
    
    /**
     * Test sur la recherche d'une formation (après son ajout)
     */
    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue('title', 'Formation test');
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
        $this->assertEquals("Formation test", $formations[0]->getTitle());
    }
    
    /**
     * Test sur la recherche d'une formation en précisant la table (après son ajout)
     */
    public function testFindByContainValueTable(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValueTable('title', 'Formation test', 'formations');
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
        $this->assertEquals("Formation test", $formations[0]->getTitle());
    }
    
    /**
     * Test sur les X formations les plus récentes (ici 3)
     */
    public function testFindAllLasted(){
        $repository = $this->recupRepository();
        // ici on ajoute 3 formations numérotées d'une date récente, puis on vérifie que les plus récentes sont belles et bien celles qui ont été ajoutées
        $formation = (new Formation())
                ->setTitle("Formation test 1")
                ->setPublishedAt(new DateTime("2023-01-28 11:01:57"));
        $repository->add($formation, true);
        $formation = (new Formation())
                ->setTitle("Formation test 2")
                ->setPublishedAt(new DateTime("2023-01-28 11:01:57"));
        $repository->add($formation, true);
        $formation = (new Formation())
                ->setTitle("Formation test 3")
                ->setPublishedAt(new DateTime("2023-01-28 11:01:57"));
        $repository->add($formation, true);
        $formations = $repository->findAllLasted(3);
        $nbFormations = count($formations);
        $this->assertEquals(3, $nbFormations);
        $this->assertEquals("Formation test 1", $formations[0]->getTitle());
        $this->assertEquals("Formation test 2", $formations[1]->getTitle());
        $this->assertEquals("Formation test 3", $formations[2]->getTitle());
    }
    
    /**
     * Test sur la playlist liée à cette formation et à ses formations
     */
    public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $formations = $repository->findAllForOnePlaylist(24);
        $nbFormations = count($formations);
        $this->assertEquals(10, $nbFormations);
        $this->assertEquals("Cours UML (1 à 7 / 33) : introduction et cas d'utilisation", $formations[0]->getTitle());
    }
}
