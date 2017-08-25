<?php

namespace AppBundle\Program;

use AppBundle\Entity\Athlete;
use AppBundle\Program\Program\ProgramInterface;

class ProgramEligibilityVoter
{
    /** @var ProgramInterface[] */
    private $programs = [];

    public function addProgram(ProgramInterface $program)
    {
        $this->programs[$program->getSlug()] = $program;

        // Sort by priority
        uasort($this->programs, function($a, $b) { return $b->getPriority() - $a->getPriority(); });
    }

    /**
     * @param Athlete $athlete
     * @return array List of eligible programs
     */
    public function getEligiblePrograms(Athlete $athlete) {
        return array_filter($this->programs, function($program) use ($athlete) {
            /** @var $program ProgramInterface */
            return $program->isEligible($athlete);
        });
    }

    /**
     * @param string $slug
     * @return ProgramInterface|null
     */
    public function findBySlug($slug) {
        if (array_key_exists($slug, $this->programs)) {
            return $this->programs[$slug];
        }

        return null;
    }
}
