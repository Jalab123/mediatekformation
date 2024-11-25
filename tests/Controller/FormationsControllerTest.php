<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de tests du contrôleur des formations.
 *
 * @author pilou
 */
class FormationsControllerTest extends WebTestCase{
    
    /**
     * Test vérifiant l'accès à la page des formations.
     */
    public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * Test vérifiant le contenu de la page.
     */
    public function testContenuPage(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertSelectorTextContains("h5", "Eclipse n°8 : Déploiement");
    }
    
    /**
     * Test vérifiant le tri sur les formations.
     */
    public function testTriFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/ASC');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains("h5", "Android Studio (complément n°1) : Navigation Drawer et Fragment");
    }
    
    /**
     * Test vérifiant le tri sur les playlists.
     */
    public function testTriPlaylist(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/name/ASC/playlist');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains("h5", "Bases de la programmation n°74 - POO : collections");
    }
    
    /**
     * Test vérifiant le tri sur la date.
     */
    public function testTriDate(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/publishedAt/ASC');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains("h5", "Cours UML (1 à 7 / 33) : introduction et cas d'utilisation");
    }
    
    /**
     * Test vérifiant le filtre sur les formations.
     */
    public function testFiltreFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse'
        ]);
        $this->assertCount(9, $crawler->filter("h5"));
        $this->assertSelectorTextContains("h5", "Eclipse n°8 : Déploiement");
    }
    
    /**
     * Test vérifiant le filtre sur les playlists.
     */
    public function testFiltrePlaylist(){
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/name/playlist');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse'
        ]);
        $this->assertCount(9, $crawler->filter("h5"));
        $this->assertSelectorTextContains("h5", "Eclipse n°8 : Déploiement");
    }
    
    /**
     * Test vérifiant le clic sur une miniature.
     */
    public function testLinkFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('Miniature de la playlist');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/1', $uri);
        $this->assertSelectorTextContains("h4", "Eclipse n°8 : Déploiement");
    }

}
