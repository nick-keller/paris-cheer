<?php

namespace AppBundle\Registration\Step;

use AppBundle\Entity\Athlete;
use Symfony\Component\HttpFoundation\Request;

interface StepInterface
{
    /**
     * @return string A unique slug name.
     */
    public function getName();

    /**
     * Setup the step, called right at the beginning.
     *
     * @param Athlete $athlete
     */
    public function setAthlete(Athlete $athlete);

    /**
     * Updates the $athlete object based on the provided $request.
     * This is called when a POST is made by the client.
     *
     * @param Request $request
     */
    public function handle(Request $request);

    /**
     * Called right after the handle method to check if step is valid (no parameters).
     * Called on each previous step to check if user did not skip a step ($athlete parameter provided).
     *
     * @param Athlete $athlete When null the athlete of setAthlete should be used.
     * @return bool True if $athlete is valid.
     */
    public function isValid(Athlete $athlete = null);

    /**
     * Called before showing next step to check if this step can be skipped.
     *
     * @param Athlete $athlete
     * @return bool True if step can be skipped.
     */
    public function isSkippable(Athlete $athlete);

    /**
     * The data passed to the view for rendering.
     *
     * @return array
     */
    public function getViewData();
}
