<?php

namespace AppBundle\Registration\Step;

use AppBundle\Entity\Athlete;
use AppBundle\Service\AthleteFinder;
use Symfony\Component\HttpFoundation\Request;

class ReturningAthleteStep implements StepInterface
{
    const BTN_NAME = 'accept';

    /** @var AthleteFinder */
    private $athleteFinder;

    /** @var Athlete */
    private $athlete;

    /** @var Athlete */
    private $candidate;

    /**
     * ReturningAthleteStep constructor.
     *
     * @param AthleteFinder $athleteFinder
     */
    public function __construct(AthleteFinder $athleteFinder)
    {
        $this->athleteFinder = $athleteFinder;
    }

    public function getName()
    {
        return 'returning-athlete';
    }

    public function setAthlete(Athlete $athlete)
    {
        $this->athlete = $athlete;
        $this->candidate = $this->athleteFinder->getBestMatchingAthlete($athlete);
    }

    public function handle(Request $request)
    {
        if ((bool) $request->request->get(self::BTN_NAME)) {
            foreach (get_class_methods($this->candidate) as $method) {
                if (substr($method, 0, 3) === 'get') {
                    $setter = $method;
                    $setter[0] = 's';
                    $this->athlete->$setter($this->candidate->$method());
                }
            }
        }
    }

    public function isValid(Athlete $athlete = null)
    {
        return true;
    }

    public function isSkippable(Athlete $athlete)
    {
        return !$this->athleteFinder->mightAlreadyExist($athlete);
    }

    public function getViewData()
    {
        return [
            'athlete' => $this->athlete,
            'candidate' => $this->candidate,
            'btn_name' => self::BTN_NAME,
        ];
    }
}
