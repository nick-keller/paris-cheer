<?php

namespace AppBundle\Program\Program;

use AppBundle\Entity\Athlete;

interface ProgramInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * Returns an int, the bigger the first it should appear in the list.
     * @return int
     */
    public function getPriority();

    /**
     * @param Athlete $athlete
     * @return bool True if $athlete is eligible to the program
     */
    public function isEligible(Athlete $athlete);

    /**
     * @param Athlete $athlete
     * @return string The category of the athlete based on his/her age.
     */
    public function getCategory(Athlete $athlete);
}
