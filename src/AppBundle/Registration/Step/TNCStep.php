<?php

namespace AppBundle\Registration\Step;

use AppBundle\Entity\Athlete;
use Symfony\Component\HttpFoundation\Request;

class TNCStep implements StepInterface
{

    public function getName()
    {
        return 'tnc';
    }

    public function setAthlete(Athlete $athlete)
    {
    }

    public function handle(Request $request)
    {
    }

    public function isValid(Athlete $athlete = null)
    {
        return true;
    }

    public function isSkippable(Athlete $athlete)
    {
        return false;
    }


    public function getViewData()
    {
        return [];
    }
}
