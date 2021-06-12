<?php

namespace App\Controller;

use App\Repository\ChannelsRepository;
use App\Repository\TutosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(
        Request $request,
        TutosRepository $tutosRepository,
        ChannelsRepository $channelsRepository
    ): Response {
        $hotsname = $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = ['loc' => $this->generateUrl('home'), 'priority' => '1.00'];
        $urls[] = ['loc' => $this->generateUrl('app_register')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('about')];
        $urls[] = ['loc' => $this->generateUrl('confidentiality')];
        $urls[] = ['loc' => $this->generateUrl('search')];
        $urls[] = ['loc' => $this->generateUrl('channels')];

        foreach ($tutosRepository->findAll() as $tuto) {
            $urls[] = [
                'loc' => $this->generateUrl('tutos.show', [
                    'slug'  => $tuto->getSlug(),
                    'id'    => $tuto->getId()
                ]),
                'lastmod' => $tuto->getPublishedAt()->format('Y-m-d')
            ];
        }
        foreach ($channelsRepository->findAll() as $channel) {
            $urls[] = [
                'loc' => $this->generateUrl('channels.show', [
                    'slug'  => $channel->getSlug(),
                    'id'    => $channel->getId()
                ]),
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls'      => $urls,
                'hostname'  => $hotsname
            ]),
            200
        );
        $response->headers->set('Content-type', 'text/xml');

        return $response;
    }
}
