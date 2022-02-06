<?php

namespace App\Controller;

use App\Repository\AuthorsRepository;
use App\Repository\YoutubeLinksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap', format: 'xml')]
    public function index(
        Request $request,
        YoutubeLinksRepository $linksRepository,
        AuthorsRepository $authorsRepository
    ): Response {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = ['loc' => $this->generateUrl('home'), 'priority' => '1.00'];
        $urls[] = ['loc' => $this->generateUrl('app_register')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('about')];
        $urls[] = ['loc' => $this->generateUrl('confidentiality')];
        $urls[] = ['loc' => $this->generateUrl('search')];
        $urls[] = ['loc' => $this->generateUrl('authors.index')];
        $urls[] = ['loc' => $this->generateUrl('events.index')];

        foreach ($linksRepository->findAll() as $link) {
            $urls[] = [
                'loc' => $this->generateUrl('links.show', [
                    'slug' => $link->getSlug(),
                    'id' => $link->getId(),
                ]),
                'lastmod' => $link->getPublishedAt()->format('Y-m-d'),
            ];
        }
        foreach ($authorsRepository->findAll() as $authors) {
            $urls[] = [
                'loc' => $this->generateUrl('authors.show', [
                    'slug' => $authors->getSlug(),
                    'id' => $authors->getId(),
                ]),
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname,
            ]),
            200
        );
        $response->headers->set('Content-type', 'text/xml');

        return $response;
    }
}
