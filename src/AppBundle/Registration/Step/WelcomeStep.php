<?php

namespace AppBundle\Registration\Step;

use AppBundle\Form\AthleteBasicInfoType;

class WelcomeStep extends AbstractStep
{

    public function getName()
    {
        return 'welcome';
    }

    protected function getFormType()
    {
        return AthleteBasicInfoType::class;
    }

    protected function getValidationGroup()
    {
        return 'basic_info';
    }
}
