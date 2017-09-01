<?php

namespace AppBundle\Controller;

use AppBundle\Document\DocumentGenerator;
use AppBundle\Entity\Athlete;
use AppBundle\Registration\RegistrationFlow;
use Doctrine\ORM\EntityManagerInterface;
use Hashids\Hashids;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/registration")
 */
class RegistrationController extends Controller
{
    const REGISTERING_ATHLETE = 'registering_athlete';

    /**
     * The user is redirected here to start the registration flow.
     * This will simply redirect hi to the first step.
     *
     * @Route("/", name="registration_start")
     *
     * @param RegistrationFlow $flow
     * @return Response
     */
    public function startAction(RegistrationFlow $flow)
    {
        return $this->redirectToStep($flow->firstStep());
    }


    /**
     * The user land here at each step. A GET will simply display the step and a POST will process it.
     * It also handles registration after the last step is completed.
     *
     * @Route("/{name}", name="registration_step")
     *
     * @param Request                $request
     * @param RegistrationFlow       $flow
     * @param DocumentGenerator      $generator
     * @param EntityManagerInterface $em
     * @param Hashids                $hashids
     * @param string                 $name
     * @return Response
     * @throws \Exception
     */
    public function stepAction(Request $request, RegistrationFlow $flow, DocumentGenerator $generator, EntityManagerInterface $em, Hashids $hashids, string $name)
    {
        $athlete = $this->getAthleteFromSession();

        // First check if any previous step is not valid, and redirect to the first eventual invalid step
        if (($invalidStep = $flow->hasInvalidStepBefore($athlete, $name)) !== false) {
            return $this->redirectToStep($invalidStep);
        }

        // Get step and initialize it
        $step = $flow->getStep($name);
        $step->setAthlete($athlete);

        // If this is a post we handle step submission, otherwise we directly render the step
        if ($request->isMethod('POST')) {
            $step->handle($request);

            if ($step->isValid()) {
                $this->saveAthleteToSession($athlete);

                $next = $flow->next($name, $athlete);

                // If the flow is finished (no next step) we handle registration
                if ($next === false) {
                    // Remove already existing athlete if any
                    if ($athlete->getId()) {
                        $em->getRepository(Athlete::class)->deleteById($athlete->getId());
                    }

                    // Save athlete to DB
                    $em->persist($athlete);
                    $em->flush();

                    $generator->getDocuments($athlete);

                    return $this->redirectToRoute('registration_finish', ['id' => $hashids->encode($athlete->getId())]);
                }

                return $this->redirectToStep($next);
            }
        }

        return $this->render(':registration:step_' . $name . '.html.twig', $step->getViewData());
    }

    /**
     * This is shown after the registration flow is completed.
     *
     * @Route("/finish/{id}", name="registration_finish")
     *
     * @param Hashids                $hashids
     * @param EntityManagerInterface $em
     * @param DocumentGenerator      $documentGenerator
     * @param string                 $id
     * @return Response
     */
    public function finishAction(Hashids $hashids, EntityManagerInterface $em, DocumentGenerator $documentGenerator, string $id)
    {
        $id = $hashids->decode($id)[0];

        $athlete = $em->getRepository(Athlete::class)->find($id);

        return $this->render(':registration:finish.html.twig', [
            'athlete' => $athlete,
            'documents' => $documentGenerator->getDocumentsList($athlete),
        ]);
    }

    /**
     * @param string $step
     * @return RedirectResponse
     */
    private function redirectToStep(string $step)
    {
        return $this->redirectToRoute('registration_step', ['name' =>$step]);
    }

    /**
     * @return Athlete
     */
    private function getAthleteFromSession() : Athlete
    {
        return $this->get('session')->get(self::REGISTERING_ATHLETE, new Athlete());
    }

    /**
     * @param Athlete $athlete
     */
    private function saveAthleteToSession(Athlete $athlete)
    {
        $this->get('session')->set(self::REGISTERING_ATHLETE, $athlete);
    }
}
