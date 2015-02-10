<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use UTravel\OpinionBundle\Entity\HousingType;

class LoadHousingType extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $ht1 = new HousingType();
        $ht1->setName('Colocation');
        $manager->persist($ht1);
        
        $ht2 = new HousingType();
        $ht2->setName('Studio');
        $manager->persist($ht2);
        
        $ht3 = new HousingType();
        $ht3->setName('Chambre');
        $manager->persist($ht3);
        
        $manager->flush();
    }
}
