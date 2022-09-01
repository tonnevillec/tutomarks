<?php

namespace App\Controller;

use App\Entity\Hebdoo;
use App\Entity\HebdooSemaine;
use App\Form\HebdooType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/hebdoo')]
class HebdooController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
//        private readonly HttpClientInterface $client
    ) {
    }

    #[Route('/', name: 'hebdoo.semaine')]
    public function index(): Response
    {
        $hebdoo = $this
            ->em
            ->getRepository(HebdooSemaine::class)
            ->findOneBy([
                'is_publish' => true,
            ]);

        return $this->render('hebdoo/index.html.twig', [
            'hebdoo' => $hebdoo,
        ]);
    }

    #[Route('/add', name: 'hebdoo.add')]
    public function add(Request $request): Response
    {
        $hebdoo = new Hebdoo();
        $form = $this->createForm(HebdooType::class, $hebdoo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($hebdoo);
            $this->em->flush();

            $this->addFlash('success', 'Votre lien a été ajouté');

            return $this->redirectToRoute('hebdoo.archives');
        }

        return $this->render('hebdoo/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/archives', name: 'hebdoo.archives')]
    public function archives(): Response
    {
        $hebdoo = $this->em->getRepository(Hebdoo::class)->findAll();

        return $this->render('hebdoo/archives.html.twig', [
            'hebdoo' => $hebdoo,
        ]);
    }
}
