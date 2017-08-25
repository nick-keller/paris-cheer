<?php

namespace AppBundle\Registration\Step;

use AppBundle\Form\AthleteEmergencyContactType;

class EmergencyContactStep extends AbstractStep
{

    /**
     * @return string A unique slug name.
     */
    public function getName()
    {
        return 'emergency-contact';
    }

    /**
     * @return string
     */
    protected function getFormType()
    {
        return AthleteEmergencyContactType::class;
    }

    /**
     * @return string[]
     */
    protected function getValidationGroup()
    {
        return 'emergency';
    }
}
