<?php

namespace AppBundle\Registration\Step;

use AppBundle\Form\AthleteContactType;

class ContactStep extends AbstractStep
{

    public function getName()
    {
        return 'contact';
    }

    protected function getFormType()
    {
        return AthleteContactType::class;
    }

    protected function getValidationGroup()
    {
        return 'contact';
    }
}
