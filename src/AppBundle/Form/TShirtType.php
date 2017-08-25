<?php

namespace AppBundle\Form;

use AppBundle\Entity\Athlete;
use AppBundle\Enum\Gender;
use AppBundle\Form\Type\YesNoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class TShirtType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('freeTShirt', CheckboxType::class, ['required' => false])
            ->add('tShirtSize', ChoiceType::class)
            ->add('tShirtColor', TextType::class, ['required' => false])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Athlete $athlete */
            $athlete = $event->getData();
            $form = $event->getForm();

            if ($athlete->getGender() === Gender::Male) {
                $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
            } else {
                $sizes = ['XS', 'S', 'M', 'L'];
            }
                $form->add('tShirtSize', ChoiceType::class, ['choices' => array_combine($sizes, $sizes)]);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Athlete::class,
            'validation_groups' => array('tshirt'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tshirt';
    }


}
