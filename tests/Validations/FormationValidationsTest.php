<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Classe de tests permettant de tester les dates.
 *
 * @author pilou
 */
class FormationValidationsTest extends KernelTestCase {
    
    /**
     * Fonction permettant d'ajouter et de récupérer une nouvelle formation.
     * @return Formation
     */
    public function getFormation(): Formation{
        return (new Formation())
                ->setTitle("Eclipse n°9 : Test");
    }
    
    /**
     * Test vérifiant une date valide.
     */
    public function testValidDateFormation(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2020-11-15"));
        $this->assertErrors($formation, 0);
    }
    
    /**
     * Test vérifiant une date non-valide.
     */
    public function testNonValidDateFormation(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2029-11-15"));
        $this->assertErrors($formation, 1);
    }
    
    /**
     * Fonction permettant de gérer les erreurs.
     * @param Formation $formation
     * @param int $nbErreursAttendues*
     */
    public function assertErrors(Formation $formation, int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
}
