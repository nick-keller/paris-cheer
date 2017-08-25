<?php

namespace AppBundle\Program\Program;

use AppBundle\Entity\Athlete;

class CheerAcademyProgram implements ProgramInterface
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'Cheer Academy';
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return 'cheer_academy';
    }

    public function getPriority()
    {
        return 1;
    }

    /**
     * @param Athlete $athlete
     * @return bool True if $athlete is eligible to the program
     */
    public function isEligible(Athlete $athlete)
    {
        return $athlete->getBirthday() >= new \DateTime('2002-01-01') && $athlete->getBirthday() < new \DateTime('2013-01-01');
    }

    public function getCategory(Athlete $athlete)
    {
        if ($athlete->getBirthday() < new \DateTime('2002-01-01')) {
            return null;
        } else if ($athlete->getBirthday() < new \DateTime('2007-01-01')) {
            return 'loisir_junior';
        } else if ($athlete->getBirthday() < new \DateTime('2010-01-01')) {
            return 'loisir_u11';
        } else if ($athlete->getBirthday() < new \DateTime('2013-01-01')) {
            return 'loisir_u8';
        }
    }
}
