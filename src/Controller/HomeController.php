<?php

namespace App\Controller;

use App\Entity\Tutos;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * HomeController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        /*
         * pour plus tard => Voter edit_tutos
         */
//        if(!$this->isGranted('EDIT', $tutos)){
//            $this->addFlash('danger', 'pas autorisé');
//        $this->redirectToRoute('home');
//        }

        $tutos = $this->em->getRepository(Tutos::class)->findLatest(6);

        return $this->render('home/index.html.twig', [
            'tutos' => $tutos,
        ]);
    }
}
