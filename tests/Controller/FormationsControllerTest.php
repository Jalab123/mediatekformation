<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationsControllerTest
 *
 * @author pilou
 */
class FormationsControllerTest extends WebTestCase {
    //put your code here
    
    /**
     * Test sur l'accès de la page des formations
     */
    public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    /**
     * Test sur le premier élément de la liste des formations
     */
    public function testContenuPage(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }
    
    /**
     * Test de la fonctionnalité de tri des formations
     */
    public function testTriFormation(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }
    
    /**
     * Test de la fonctionnalité de filtre des formations
     */
    public function testFiltreFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        // simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Python n°18 : Décorateur singleton'
        ]);
        // vérifie le nombre de lignes obtenues
        $this->assertCount(1, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
        $this->assertSelectorTextContains('h5', 'Python n°18 : Décorateur singleton');
    }
    
    /**
     * Test d'un clic sur un lien (miniature) des formations
     */
    public function testLinkFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        // clic sur un lien (miniature de la formation)
        $client->clickLink('miniature formation');
        // récupération du résultat du clic
        $response = $client->getResponse();
        // contrôle si le lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        // récupération de la route et contrôle qu'elle est correcte
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/1', $uri);
        
    }

}
