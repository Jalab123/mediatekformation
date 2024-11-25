<?php
namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Classe de tests permettant de tester la méthode getPublishedAtString.
 *
 * @author pilou
 */
class FormationTest extends TestCase {
    
    /**
     * Test getPublishedAtString.
     */
    public function testGetPublishedAtString(){
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime("2024-11-15"));
        $this->assertEquals("15/11/2024", $formation->getPublishedAtString());
    }
}
