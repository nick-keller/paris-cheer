<?php

namespace AppBundle\Registration\Step;

use AppBundle\Entity\Athlete;
use AppBundle\Enum\Gender;
use AppBundle\Form\BraType;

class BraStep extends AbstractStep
{

    public function getName()
    {
        return 'bra';
    }

    protected function getFormType()
    {
        return BraType::class;
    }

    protected function getValidationGroup()
    {
        return 'bra';
    }

    public function isSkippable(Athlete $athlete)
    {
        return $athlete->getGender() !== Gender::Female;
    }
}
