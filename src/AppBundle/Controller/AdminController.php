<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Athlete;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction(EntityManagerInterface $em)
    {
        $athletes = $em->getRepository(Athlete::class)->findAll();

        return $this->render(':admin:athletes.html.twig', [
            'athletes' => $athletes,
        ]);
    }
}
