<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author pilou
 */
class PlaylistRepositoryTest extends KernelTestCase{
    //put your code here
    
    /**
     * Récupère le repository de Playlist
     * @return PlaylistRepository
     */
    public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }
    
    /**
     * Test sur le nombre de playlists
     */
    public function testNbPlaylists(){
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(27, $nbFormations);
    }
    
    /**
     * Création d'une instance de Playlist avec title
     * @return Playlist
     */
    public function newPlaylist(): Playlist{
        $playlist = (new Playlist())
                ->setName("Playlist test");
        return $playlist;
    }
    
    /**
     * Test sur l'ajout d'une playliste
     */
    public function testAddPlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    /**
     * Test sur la suppression d'une playlist
     */
    public function testRemovePlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
    /**
     * Test sur le rangement par nom de playlist (par ordre alphabétique)
     */
    public function testFindAllOrderByName(){
        // on vérifie d'abord que le nombre de playlists est exact
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals($nbPlaylists, $repository->count([]));
        // on teste ensuite en rangeant par ordre alphabétique du titre
        $playlists = $repository->findAllOrderByName('ASC');
        $this->assertEquals("Bases de la programmation (C#)", $playlists[0]->getName());
    }
    
    /**
     * Test sur le rangement des playlists en fonction du nombre de formations (ici, dans l'ordre décroissant)
     */
    public function testFindAllOrderByNbFormations(){
        // on vérifie d'abord que le nombre de playlists est exact
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals($nbPlaylists, $repository->count([]));
        // on teste ensuite en rangeant par ordre décroissant du nombre de formations
        // remarque: ici j'ai préféré procéder de façon inverse, en affichant la playlist contenant le plus de formations
        $playlists = $repository->findAllOrderByNbFormations('DESC');
        $this->assertEquals("Bases de la programmation (C#)", $playlists[0]->getName());
    }
    
    /**
     * Test sur la recherche d'une playlist (après son ajout)
     */
     public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findByContainValue('name', 'Playlist test');
        $nbPlaylists = count($playlists);
        $this->assertEquals(1, $nbPlaylists);
        $this->assertEquals("Playlist test", $playlists[0]->getName());
    }
    
    /**
     * Test sur la recherche d'une playlist en précisant la table (après son ajout)
     */
    public function testFindByContainValueTable(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findByContainValueTable('name', 'Playlist test', 'playlist');
        $nbPlaylists = count($playlists);
        $this->assertEquals(1, $nbPlaylists);
        $this->assertEquals("Playlist test", $playlists[0]->getName());
    }
}
