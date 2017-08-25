<?php

namespace AppBundle\Form;

use AppBundle\Entity\Athlete;
use AppBundle\Form\Type\PhoneType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AthleteContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', PhoneType::class, ['label' => 'Numéro de portable'])
            ->add('email', EmailType::class, ['label' => 'Adresse email'])
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
            'validation_groups' => array('contact'),
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
