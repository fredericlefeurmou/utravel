<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUniversities extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $u1 = new UTravel\OpinionBundle\Entity\University();
        $u1->setName('Linköping University');
        $u1->setCountry('SE');
        $u1->setCity('Linköping');
        $u1->setLink('http://www.liu.se/?l=en');
        $u1->setPartnership(true);
        $manager->persist($u1);
        
        $u2 = new UTravel\OpinionBundle\Entity\University();
        $u2->setName('Technische Universität Braunschweig');
        $u2->setCountry('DE');
        $u2->setCity('Braunschweig');
        $u2->setLink('https://www.tu-braunschweig.de/');
        $u2->setPartnership(true);
        $manager->persist($u2);
        
        $u3 = new UTravel\OpinionBundle\Entity\University();
        $u3->setName('Universidad de Zaragoza');
        $u3->setCountry('ES');
        $u3->setCity('Zaragoza');
        $u3->setLink('http://www.unizar.es/');
        $u3->setPartnership(true);
        $manager->persist($u3);
        
        $u4 = new UTravel\OpinionBundle\Entity\University();
        $u4->setName('Politecnico di Torino');
        $u4->setCountry('Torino');
        $u4->setCity('IT');
        $u4->setLink('http://www.polito.it/?lang=en');
        $u4->setPartnership(true);
        $manager->persist($u4);
        
        $manager->flush();
    }
}
