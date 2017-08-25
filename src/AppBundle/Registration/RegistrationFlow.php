<?php

namespace AppBundle\Registration;

use AppBundle\Entity\Athlete;
use AppBundle\Registration\Step\BraStep;
use AppBundle\Registration\Step\ContactStep;
use AppBundle\Registration\Step\EmergencyContactStep;
use AppBundle\Registration\Step\InfoStep;
use AppBundle\Registration\Step\ProgramSelectionStep;
use AppBundle\Registration\Step\QSSportStep;
use AppBundle\Registration\Step\ReturningAthleteStep;
use AppBundle\Registration\Step\StepInterface;
use AppBundle\Registration\Step\TShirtStep;
use AppBundle\Registration\Step\WelcomeStep;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistrationFlow
{
    /** @var StepInterface[] */
    private $steps = [];

    /**
     * RegistrationFlow constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->addStep($container->get(WelcomeStep::class));
        $this->addStep($container->get(ReturningAthleteStep::class));
        $this->addStep($container->get(ProgramSelectionStep::class));
        $this->addStep($container->get(InfoStep::class));
        $this->addStep($container->get(ContactStep::class));
        $this->addStep($container->get(EmergencyContactStep::class));
        $this->addStep($container->get(QSSportStep::class));
        $this->addStep($container->get(TShirtStep::class));
        $this->addStep($container->get(BraStep::class));
    }

    private function addStep(StepInterface $step)
    {
        $this->steps[$step->getName()] = $step;
    }

    /**
     * @return string The name of the first step.
     */
    public function firstStep()
    {
        return reset($this->steps)->getName();
    }

    /**
     * @param Athlete $athlete
     * @param string  $step
     * @return bool|string False if no invalid previous steps found, step's name otherwise.
     * @throws \Exception
     */
    public function hasInvalidStepBefore(Athlete $athlete, $step)
    {
        foreach ($this->steps as $previousStep) {
            if ($previousStep->getName() === $step) {
                return false;
            }

            if (!$previousStep->isValid($athlete)) {
                return $previousStep->getName();
            }
        }

        throw new \Exception('Unkown step ' . $step);
    }

    /**
     * @param string $name
     * @return StepInterface
     * @throws \Exception
     */
    public function getStep($name)
    {
        $this->StepExists($name);

        return $this->steps[$name];
    }

    /**
     * @param string  $name
     * @param Athlete $athlete
     * @return bool|string Name of next non-skippable step, false if flow is finished.
     * @throws \Exception
     */
    public function next($name, Athlete $athlete)
    {
        $this->StepExists($name);

        $stepPassed = false;

        foreach ($this->steps as $step) {
            if (!$stepPassed) {
                $stepPassed = $step->getName() === $name;
            } else if (!$step->isSkippable($athlete)) {
                return $step->getName();
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @throws \Exception
     */
    private function StepExists($name)
    {
        if (!array_key_exists($name, $this->steps)) {
            throw new \Exception('Unkown step ' . $name);
        }
    }
}
