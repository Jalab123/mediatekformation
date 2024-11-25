<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de tests du repository des playlists.
 *
 * @author pilou
 */
class PlaylistRepositoryTest extends KernelTestCase {
    
    /**
     * Test vérifiant l'accès au repository.
     * @return PlaylistRepository
     */
    public function recupRepository(): PlaylistRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }
    
    /**
     * Test vérifiant le nombre de playlists.
     */
    public function testNbPlaylists(){
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(28, $nbPlaylists);
    }
    
    /**
     * Fonction permettant d'ajouter une nouvelle playlist.
     * @return Playlist
     */
    public function newPlaylist(): Playlist {
        $playlist = (new Playlist())
                ->setName("Eclipse Test");
        return $playlist;
    }
    
    /**
     * Test add playlist.
     */
    public function testAddPlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]));
    }
    
    /**
     * Test remove playlist.
     */
    public function testRemovePlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]));
    }
    
    /**
     * Test findAllOrderByName.
     */
    public function testFindAllOrderByName(){
        $repository = $this->recupRepository();
        $nbPlaylistsTotal = $repository->count([]);
        $playlists = $repository->findAllOrderByName("ASC");
        $nbPlaylistsTrouvees = count($playlists);
        $this->assertEquals($nbPlaylistsTotal, $nbPlaylistsTrouvees);
        $this->assertEquals("Bases de la programmation (C#)", $playlists[0]->getName());
    }
    
    /**
     * Test findAllOrderByNbFormations.
     */
    public function testFindAllOrderByNbFormations(){
        $repository = $this->recupRepository();
        $nbPlaylistsTotal = $repository->count([]);
        $playlists = $repository->findAllOrderByNbFormations("ASC");
        $nbPlaylistsTrouvees = count($playlists);
        $this->assertEquals($nbPlaylistsTotal, $nbPlaylistsTrouvees);
        $this->assertEquals("playlist test", $playlists[0]->getName());
    }
    
    /**
     * Test findByContainValue.
     */
    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findByContainValue("name", "Eclipse Test");
        $nbPlaylists = count($playlists);
        $this->assertEquals(1, $nbPlaylists);
        $this->assertEquals("Eclipse Test", $playlists[0]->getName());
    }
}
