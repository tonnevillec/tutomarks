<?php

namespace App\Controller;

use App\Entity\Channels;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChannelsController extends AbstractController
{
    private $em;
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @Route("/channels", name="channels")
     */
    public function index(): Response
    {
        return $this->render('channels/index.html.twig', [
            'controller_name' => 'ChannelsController',
        ]);
    }


    /**
     * @Route("/{slug}-{id}", name="channels.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Request $request
     * @param $slug
     * @param $id
     * @return RedirectResponse|Response
     */
    public function show(Request $request, $slug, $id)
    {
        $channel = $this->em->find(Channels::class, $id);
        if(!$channel) {
            $this->addFlash('danger', $this->translator->trans('error.unauthorized'));
            return $this->redirectToRoute('home');
        }

        return $this->render('channels/show.html.twig', [
            'channel'   => $channel,
            'from'      => $request->server->get('HTTP_REFERER')
        ]);
    }
}
