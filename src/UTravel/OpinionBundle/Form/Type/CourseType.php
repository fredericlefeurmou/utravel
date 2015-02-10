<?php

namespace UTravel\OpinionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of CourseType
 *
 * @author jonathan
 */
class CourseType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'Intitulé',
            'max_length' => 50
        ));

        $builder->add('description', 'textarea', array('label' => 'Description'));
        $builder->add('evaluationFile', 'file', array('label' => 'Fiche d\'évaluation', 'required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UTravel\OpinionBundle\Entity\StudyCourse',
        ));
    }
    
    public function getName() {
        return "course";
    }

}
