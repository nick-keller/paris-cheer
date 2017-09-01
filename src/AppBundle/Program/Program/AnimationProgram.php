<?php

namespace AppBundle\Program\Program;

use AppBundle\Entity\Athlete;

class AnimationProgram implements ProgramInterface
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'Animation';
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return 'animation';
    }

    public function getDescription()
    {
        return 'Je souhaite pratiquer le sport et participer a des évènements.';
    }

    public function getPriority()
    {
        return 2;
    }

    /**
     * @param Athlete $athlete
     * @return bool True if $athlete is eligible to the program
     */
    public function isEligible(Athlete $athlete)
    {
        return $athlete->getBirthday() < new \DateTime('2005-01-01');
    }

    public function getCategory(Athlete $athlete)
    {
        if ($athlete->getBirthday() < new \DateTime('2002-01-01')) {
            return 'loisir_senior';
        } else if ($athlete->getBirthday() < new \DateTime('2005-01-01')) {
            return 'loisir_junior';
        }
    }
}
