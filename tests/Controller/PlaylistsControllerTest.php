<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistsControllerTest
 *
 * @author pilou
 */
class PlaylistsControllerTest extends WebTestCase{
    
    public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testContenuPage(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertSelectorTextContains("h5", "Bases de la programmation (C#)");
    }
    
    public function testTriPlaylist(){
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains("h5", "Bases de la programmation (C#)");
    }
    
    public function testTriNbFormations(){
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/nbformations/ASC');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains("h5", "playlist test");
    }
    
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
