<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use UTravel\OpinionBundle\Entity\TransportType;

class LoadTransportType extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tt1 = new TransportType();
        $tt1->setName('Avion');
        $manager->persist($tt1);
        
        $tt2 = new TransportType();
        $tt2->setName('Train');
        $manager->persist($tt2);
        
        $tt3 = new TransportType();
        $tt3->setName('Voiture');
        $manager->persist($tt3);
        
        $tt4 = new TransportType();
        $tt4->setName('Autre');
        $manager->persist($tt4);
        
        $manager->flush();
    }
}
