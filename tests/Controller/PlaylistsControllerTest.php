<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de tests du contrôleur des playlists.
 *
 * @author pilou
 */
class PlaylistsControllerTest extends WebTestCase{
    
    /**
     * Test vérifiant l'accès à la page des playlists.
     */
    public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * Test vérifiant le contenu de la page.
     */
    public function testContenuPage(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertSelectorTextContains("h5", "Bases de la programmation (C#)");
    }
    
    /**
     * Test vérifiant le tri sur les playlists.
     */
    public function testTriPlaylist(){
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains("h5", "Bases de la programmation (C#)");
    }
    
    /**
     * Test vérifiant le tri sur le nombre de formations.
     */
    public function testTriNbFormations(){
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/nbformations/ASC');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains("h5", "playlist test");
    }
    
    /**
     * Test vérifiant le filtre sur les playlists.
     */
    public function testFiltrePlaylist(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Cours'
        ]);
        //puisque le nombre de formations est aussi un h5
        //il y a deux h5 par ligne
        //ici, 22 h5 = 11 lignes
        $this->assertCount(22, $crawler->filter("h5"));
        $this->assertSelectorTextContains("h5", "Cours Composant logiciel");
    }
    
    /**
     * Test vérifiant le clic sur le bouton permettant de consulter une playlist.
     */
    public function testLinkPlaylist(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink('Voir détail');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/13', $uri);
        $this->assertSelectorTextContains("h4", "Bases de la programmation (C#)");
    }

}
