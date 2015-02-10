<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use UTravel\OpinionBundle\Entity\BranchSpecialization;
use UTravel\OpinionBundle\Entity\Branch;

class LoadBranchBranch extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /// Génie Biologique
        $gb = new Branch();
        $gb->setName('Biologique');
        $gb->setAbbreviation('GB');
        $manager->persist($gb);
        
        $iaa = new BranchSpecialization();
        $iaa->setName('Innovation Aliments et Agroressources');
        $iaa->setAbbreviation('IAA');
        $iaa->setBranch($gb);
        $manager->persist($iaa);
        
        $bb = new BranchSpecialization();
        $bb->setName('Biomatériaux et Biomécanique');
        $bb->setAbbreviation('BB');
        $bb->setBranch($gb);
        $manager->persist($bb);
        
        $bm = new BranchSpecialization();
        $bm->setName('Biomédicale');
        $bm->setAbbreviation('BM');
        $bm->setBranch($gb);
        $manager->persist($bm);
        
        $cib = new BranchSpecialization();
        $cib->setName('Conception et Innovation de Bioproduits');
        $cib->setAbbreviation('CIB');
        $cib->setBranch($gb);
        $manager->persist($cib);
        
        
        // Génie Informatique
        $gi = new Branch();
        $gi->setName('Informatique');
        $gi->setAbbreviation('GI');
        $manager->persist($gi);
        
        $adel = new BranchSpecialization();
        $adel->setName('Aide à la Décision En Logistique');
        $adel->setAbbreviation('ADEL');
        $adel->setBranch($gi);
        $manager->persist($adel);
        
        $fdd = new BranchSpecialization();
        $fdd->setName('Fouille de Données et Décisionnel');
        $fdd->setAbbreviation('FDD');
        $fdd->setBranch($gi);
        $manager->persist($fdd);
        
        $icsi = new BranchSpecialization();
        $icsi->setName('Ingénierie des Connaissances et des Supports d’Information');
        $icsi->setAbbreviation('ICSI');
        $icsi->setBranch($gi);
        $manager->persist($icsi);
        
        $sri = new BranchSpecialization();
        $sri->setName('Systèmes et Réseaux Informatiques');
        $sri->setAbbreviation('SRI');
        $sri->setBranch($gi);
        $manager->persist($sri);
        
        $strie = new BranchSpecialization();
        $strie->setName('Systèmes Temps-Réel et Informatique Embarquée');
        $strie->setAbbreviation('STRIE');
        $strie->setBranch($gi);
        $manager->persist($strie);
        
        
        // Génie Mécanique
        $gm = new Branch();
        $gm->setName('Mécanique');
        $gm->setAbbreviation('GM');
        $manager->persist($gm);
        
        $avi = new BranchSpecialization();
        $avi->setName('Acoustique et Vibrations Industrielles');
        $avi->setAbbreviation('AVI');
        $avi->setBranch($gm);
        $manager->persist($avi);
        
        $fqi = new BranchSpecialization();
        $fqi->setName('Fiabilité et Qualité Industrielle');
        $fqi->setAbbreviation('FQI');
        $fqi->setBranch($gm);
        $manager->persist($fqi);
        
        $idi = new BranchSpecialization();
        $idi->setName('Ingénierie du Design Industriel');
        $idi->setAbbreviation('AVI');
        $idi->setBranch($gm);
        $manager->persist($idi);
        
        $mars = new BranchSpecialization();
        $mars->setName('Mécatronique, Actionneurs, Robotisation et Systèmes');
        $mars->setAbbreviation('MARS');
        $mars->setBranch($gm);
        $manager->persist($mars);
        
        $mit = new BranchSpecialization();
        $mit->setName('Matériaux et Innovation Technologique');
        $mit->setAbbreviation('MIT');
        $mit->setBranch($gm);
        $manager->persist($mit);
        
        
        // Génie Procédés
        $gp = new Branch();
        $gp->setName('Procédés');
        $gp->setAbbreviation('GP');
        $manager->persist($gp);
        
        $ai = new BranchSpecialization();
        $ai->setName('Agro-Industrie');
        $ai->setAbbreviation('AI');
        $ai->setBranch($gp);
        $manager->persist($ai);
        
        $cpi = new BranchSpecialization();
        $cpi->setName('Conduite des Procédés Industriels');
        $cpi->setAbbreviation('CPI');
        $cpi->setBranch($gp);
        $manager->persist($cpi);
        
        $qse = new BranchSpecialization();
        $qse->setName('Qualité, Sécurité, Environnement');
        $qse->setAbbreviation('QSE');
        $qse->setBranch($gp);
        $manager->persist($qse);
        
        $te = new BranchSpecialization();
        $te->setName('Thermique Énergétique');
        $te->setAbbreviation('TE');
        $te->setBranch($gp);
        $manager->persist($te); 
        
        
        // Génie systèmes mécaniques
        $gsm = new Branch();
        $gsm->setName('Systèmes Mécaniques');
        $gsm->setAbbreviation('GSM');
        $manager->persist($gsm);
        
        $cmi = new BranchSpecialization();
        $cmi->setName('Conception Mécanique Intégrée');
        $cmi->setAbbreviation('CMI');
        $cmi->setBranch($gsm);
        $manager->persist($cmi);
        
        $mops = new BranchSpecialization();
        $mops->setName('Modélisation et Optimisation des Produits et Structures');
        $mops->setAbbreviation('MOPS');
        $mops->setBranch($gsm);
        $manager->persist($mops);
        
        $pil = new BranchSpecialization();
        $pil->setName('Production Intégrée et Logistique');
        $pil->setAbbreviation('PIL');
        $pil->setBranch($gsm);
        $manager->persist($pil);
        
        
        // Génie systèmes urbains
        $gsu = new Branch();
        $gsu->setName('Systèmes Urbains');
        $gsu->setAbbreviation('GSU');
        $manager->persist($gsu);
        
        $aie = new BranchSpecialization();
        $aie->setName('Aménagement et Ingénierie Environnementale');
        $aie->setAbbreviation('AIE');
        $aie->setBranch($gsu);
        $manager->persist($aie);
        
        $sr = new BranchSpecialization();
        $sr->setName('Systèmes et Réseaux pour l’environnement construit');
        $sr->setAbbreviation('SR');
        $sr->setBranch($gsu);
        $manager->persist($sr);
        
        $sti = new BranchSpecialization();
        $sti->setName('Systèmes Techniques Intégrés');
        $sti->setAbbreviation('STI');
        $sti->setBranch($gsu);
        $manager->persist($sti);
        
        
        $manager->flush();
    }
}
