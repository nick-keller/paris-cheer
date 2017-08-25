<?php

namespace AppBundle\Registration\Step;

use AppBundle\Entity\Athlete;
use AppBundle\Program\Program\ProgramInterface;
use AppBundle\Program\ProgramEligibilityVoter;
use Symfony\Component\HttpFoundation\Request;

class ProgramSelectionStep implements StepInterface
{
    const BTN_NAME = 'program';

    /** @var ProgramEligibilityVoter */
    private $voter;

    /** @var Athlete */
    private $athlete;

    /** @var ProgramInterface[] */
    private $programs;

    /**
     * ProgramSelectionStep constructor.
     *
     * @param ProgramEligibilityVoter $voter
     */
    public function __construct(ProgramEligibilityVoter $voter)
    {
        $this->voter = $voter;
    }

    public function getName()
    {
        return 'program-selection';
    }

    public function setAthlete(Athlete $athlete)
    {
        $this->athlete = $athlete;
        $this->programs = $this->voter->getEligiblePrograms($athlete);
    }

    public function handle(Request $request)
    {
        $this->athlete->setProgram($request->request->get(self::BTN_NAME));

        $program = $this->voter->findBySlug($this->athlete->getProgram());

        if (null != $program) {
            $this->athlete->setCategory($program->getCategory($this->athlete));
        }
    }

    public function isValid(Athlete $athlete = null)
    {
        if (null !== $athlete) {
            $this->setAthlete($athlete);
        }

        return $this->athlete->getProgram() && array_key_exists($this->athlete->getProgram(), $this->programs);
    }

    public function isSkippable(Athlete $athlete)
    {
        return false;
    }

    public function getViewData()
    {
        return [
            'athlete' => $this->athlete,
            'programs' => $this->programs,
            'btn_name' => self::BTN_NAME,
        ];
    }
}
