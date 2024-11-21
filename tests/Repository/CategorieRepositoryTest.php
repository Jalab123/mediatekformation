<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 *
 * @author pilou
 */
class CategorieRepositoryTest extends KernelTestCase {
    
    public function recupRepository(): CategorieRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }
    
    public function testNbCategories(){
        $repository = $this->recupRepository();
        $nbCategories = $repository->count([]);
        $this->assertEquals(9, $nbCategories);
    }
    
    public function newCategorie(): Categorie {
        $categorie = (new Categorie())
                ->setName("Java ");
        return $categorie;
    }
    
    public function testAddCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]));
    }
    
    public function testRemoveCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]));
    }
    
    public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $categories = $repository->findAllForOnePlaylist(1);
        $nbCategoriesTrouvees = count($categories);
        $this->assertEquals(2, $nbCategoriesTrouvees);
        $this->assertEquals("Java", $categories[0]->getName());
        $this->assertEquals("UML", $categories[1]->getName());
    }
}
