<?php

namespace AppBundle\Registration\Step;

use AppBundle\Form\AthleteInfoType;

class InfoStep extends AbstractStep
{

    public function getName()
    {
        return 'info';
    }

    /**
     * @return string
     */
    protected function getFormType()
    {
        return AthleteInfoType::class;
    }

    /**
     * @return string[]
     */
    protected function getValidationGroup()
    {
        return 'info';
    }
}
