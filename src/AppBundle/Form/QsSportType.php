<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\YesNoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class QsSportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questions = [
            'last_12_months' => [
                'Un membre de votre famille est-il décédé subitement d’une cause cardiaque ou inexpliquée ?',
                'Avez-vous ressenti une douleur dans la poitrine, des palpitations, un essoufflement inhabituel ou un malaise ?',
                'Avez-vous eu un épisode de respiration sifflante (asthme) ?',
                'Avez-vous eu une perte de connaissance ?',
                'Si vous avez arrêté le sport pendant 30 jours ou plus pour des raisons de santé, avez-vous repris sans l’accord d’un médecin ?',
                'Avez-vous débuté un traitement médical de longue durée (hors contraception et désensibilisation aux allergies) ?',
            ],
            'general' => [
                'Ressentez-vous une douleur, un manque de force ou une raideur suite à un problème osseux, articulaire ou musculaire (fracture, entorse, luxation, déchirure, tendinite, etc…) survenu durant les 12 derniers mois ?',
                'Votre pratique sportive est-elle interrompue pour des raisons de santé ?',
                'Pensez-vous avoir besoin d’un avis médical pour poursuivre votre pratique sportive ?',
            ]
        ];

        $builder
            ->add('last_12_months', FormType::class)
            ->add('general', FormType::class)
            ->add('submit', SubmitType::class, ['label' => 'Suivant'])
        ;

        foreach ($questions as $category => $qs) {
            foreach ($qs as $i => $question) {
                $builder->get($category)->add($i, YesNoType::class, [
                    'label' => $question,
                    'constraints' => new NotNull(),
                ]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'qs_sport';
    }


}
