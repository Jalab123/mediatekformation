<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author pilou
 */
class FormationTest extends TestCase {
    //put your code here
    
    /**
     * Test de la méthode qui retourne la date de parution au format string
     */
    public function testGetPublishedAtString(){
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2021-01-04 17:00:12"));
        $this->assertEquals("04/01/2021", $formation->getPublishedAtString());
    }
}
