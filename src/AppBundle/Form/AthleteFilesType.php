<?php

namespace AppBundle\Form;

use AppBundle\Entity\Athlete;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AthleteFilesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pictureFile', FileType::class, ['label' => 'Ta photo pour la licence'])
            ->add('passportFile', FileType::class, ['label' => 'Photo ou scan de ton passport / carte d\'identité'])
            ->add('paperFormFile', FileType::class, ['label' => 'Ton dossier rempli et signé'])
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
            'validation_groups' => array('file'),
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
