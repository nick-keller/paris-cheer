<?php

namespace AppBundle\Controller;

use AppBundle\Document\DocumentGenerator;
use AppBundle\Entity\Athlete;
use AppBundle\Registration\RegistrationFlow;
use Doctrine\ORM\EntityManagerInterface;
use Hashids\Hashids;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/registration")
 */
class RegistrationController extends Controller
{
    const REGISTERING_ATHLETE = 'registering_athlete';

    /**
     * @Route("/", name="registration_start")
     *
     * @param Request          $request
     * @param RegistrationFlow $flow
     * @return Response
     */
    public function startAction(Request $request, RegistrationFlow $flow)
    {
        return $this->redirectToStep($flow->firstStep());
    }


    /**
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
    public function stepAction(Request $request, RegistrationFlow $flow, DocumentGenerator $generator, EntityManagerInterface $em, Hashids $hashids, $name)
    {
        $athlete = $this->getAthleteFromSession();

        if (($invalidStep = $flow->hasInvalidStepBefore($athlete, $name)) !== false) {
            return $this->redirectToStep($invalidStep);
        }

        $step = $flow->getStep($name);
        $step->setAthlete($athlete);

        if ($request->isMethod('POST')) {
            $step->handle($request);

            if ($step->isValid()) {
                $this->saveAthleteToSession($athlete);

                $next = $flow->next($name, $athlete);

                if ($next === false) {
                    if ($athlete->getId()) {
                        $em->getRepository(Athlete::class)->deleteById($athlete->getId());
                    }

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
     * @Route("/finish/{id}", name="registration_finish")
     *
     * @param Hashids                $hashids
     * @param EntityManagerInterface $em
     * @param DocumentGenerator      $documentGenerator
     * @param                        $id
     * @return Response
     */
    public function finishAction(Hashids $hashids, EntityManagerInterface $em, DocumentGenerator $documentGenerator, $id)
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectToStep($step)
    {
        return $this->redirectToRoute('registration_step', ['name' =>$step]);
    }

    /**
     * @return Athlete
     */
    private function getAthleteFromSession()
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
