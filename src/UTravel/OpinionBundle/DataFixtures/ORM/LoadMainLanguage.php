<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use UTravel\OpinionBundle\Entity\MainLanguage;

class LoadMainLanguage extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $ml1 = new MainLanguage();
        $ml1->setName('Anglais');
        $manager->persist($ml1);
        
        $ml2 = new MainLanguage();
        $ml2->setName('Allemand');
        $manager->persist($ml2);
        
        $ml3 = new MainLanguage();
        $ml3->setName('Arabe');
        $manager->persist($ml3);
        
        $ml4 = new MainLanguage();
        $ml4->setName('Espagnol');
        $manager->persist($ml4);
        
        $ml5 = new MainLanguage();
        $ml5->setName('Français');
        $manager->persist($ml5);
        
        $ml5 = new MainLanguage();
        $ml5->setName('Mandarin');
        $manager->persist($ml5);
        
        $ml6 = new MainLanguage();
        $ml6->setName('Portugais');
        $manager->persist($ml6);
        
        $ml7 = new MainLanguage();
        $ml7->setName('Russe');
        $manager->persist($ml7);
        
        $manager->flush();
    }
}
