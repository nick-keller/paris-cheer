<?php

namespace AppBundle\Program\Program;

use AppBundle\Entity\Athlete;

class CompetitionProgram implements ProgramInterface
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'Compétition';
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return 'competition';
    }

    public function getDescription()
    {
        return 'Je souhaite pratiquer le sport en compétition.';
    }

    public function getPriority()
    {
        return 3;
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
            return 'competition_senior';
        } else if ($athlete->getBirthday() < new \DateTime('2005-01-01')) {
            return 'junior_senior';
        }
    }
}
