<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author pilou
 */
class FormationValidationsTest extends KernelTestCase {
    
    public function getFormation(): Formation{
        return (new Formation())
                ->setTitle("Eclipse n°9 : Test");
    }
    
    public function testValidDateFormation(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2020-11-15"));
        $this->assertErrors($formation, 0);
    }
    
    public function testNonValidDateFormation(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2029-11-15"));
        $this->assertErrors($formation, 1);
    }
    
    public function assertErrors(Formation $formation, int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
}
