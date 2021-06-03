<?php

namespace App\Controller;

use App\Entity\Channels;
use App\Entity\Tutos;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $orderby = $request->query->has('orderby') ? $request->query->get('orderby') : 'tutos';
        $direction = $request->query->has('direction') ? $request->query->get('direction') : 'desc';
        $channels = $paginator->paginate(
            $this->em->getRepository(Channels::class)->findAllChannels($orderby, $direction),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('channels/index.html.twig', [
            'channels' => $channels,
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
        $tutos = $this->em->getRepository(Tutos::class)->findBy([
            'channel'   => $channel,
            'available' => true
        ]);

        return $this->render('channels/show.html.twig', [
            'channel'   => $channel,
            'tutos'     => $tutos,
            'from'      => $request->server->get('HTTP_REFERER')
        ]);
    }
}
