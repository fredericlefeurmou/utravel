<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use UTravel\OpinionBundle\Entity\JourneyType;

class LoadJourneyType extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $jt1 = new JourneyType();
        $jt1->setId(JourneyType::$Studying);
        $jt1->setName('Semestre d\'étude');
        $manager->persist($jt1);
        
        $jt2 = new JourneyType();
        $jt2->setId(JourneyType::$Diploma);
        $jt2->setName('Double diplôme');
        $manager->persist($jt2);
        
        $jt3 = new JourneyType(JourneyType::$Internship);
        $jt3->setId(3);
        $jt3->setName('Stage');
        $manager->persist($jt3);
        
        $jt4 = new JourneyType(JourneyType::$Placement);
        $jt4->setId(4);
        $jt4->setName('Césure');
        $manager->persist($jt4);
        
        $manager->flush();
    }
}
