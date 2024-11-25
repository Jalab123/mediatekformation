<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de tests du contrôleur de la page d'accueil.
 *
 * @author pilou
 */
class AccueilControllerTest extends WebTestCase{
    
    /**
     * Test vérifiant l'accès à la page d'accueil.
     */
    public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
