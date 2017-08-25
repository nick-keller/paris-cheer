<?php

namespace AppBundle\Form\Type;

use AppBundle\Enum\Gender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function($phone) {
                return $phone;
            },
            function($phone) {
                return preg_replace('/[^0-9]/','',$phone);
            }
        ));
    }

    public function getParent()
    {
        return TextType::class;
    }
}
