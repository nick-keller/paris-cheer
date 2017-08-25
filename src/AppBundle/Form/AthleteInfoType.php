<?php

namespace AppBundle\Form;

use AppBundle\Entity\Athlete;
use AppBundle\Form\Type\GenderType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AthleteInfoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', GenderType::class, ['label' => 'Tu es'])
            ->add('birthPlace', TextType::class, ['label' => 'Ville de naissance'])
            ->add('nationality', TextType::class, ['label' => 'Nationalité'])
            ->add('fullAddress', TextType::class, ['label' => 'Adresse'])
            ->add('address', HiddenType::class)
            ->add('zipCode', HiddenType::class)
            ->add('city', HiddenType::class)
            ->add('submit', SubmitType::class, ['label' => 'Suivant'])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Athlete::class,
            'validation_groups' => array('info'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'athlete';
    }


}
