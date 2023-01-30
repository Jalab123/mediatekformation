<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author pilou
 */
class FormationValidationsTest extends KernelTestCase{
    //put your code here
    
    /**
     * Créé une nouvelle formation récente afin d'effectuer des tests
     * @return Formation
     */
    public function getFormation(): Formation{
        return (new Formation())
                ->setTitle("Formation test")
                ->setPublishedAt(new \DateTime("2023-01-28 11:01:57"));
    }
    
    /**
     * Test d'une date correcte
     */
    public function testValidPublishedAtFormation(){
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2021-01-04"));
        $this->assertErrors($formation, 0);
    }
    
    /**
     * Test d'une date incorrecte
     */
    public function testNonValidDatePublishedAtFormation(){
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2036-01-04"));
        $this->assertErrors($formation, 1);
    }
    
    /**
     * Permet de gérer les erreurs
     * @param Formation $formation
     * @param int $nbErreursAttendues
     */
    public function assertErrors(Formation $formation, int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
}
