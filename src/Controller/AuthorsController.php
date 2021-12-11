<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\SimpleLinks;
use App\Entity\YoutubeLinks;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/authors")]
class AuthorsController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route("/", name: "authors.index")]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $orderby = $request->query->has('orderby') ? $request->query->get('orderby') : 'nb';
        $direction = $request->query->has('direction') ? $request->query->get('direction') : 'desc';
        $authors = $paginator->paginate(
            $this->em->getRepository(Authors::class)->findAllAuthors($orderby, $direction),
            $request->query->getInt('page', 1),
            24
        );

        return $this->render('authors/index.html.twig', [
            'authors'    => $authors
        ]);
    }

    #[Route("/{slug}-{id}", name: "authors.show", requirements: ["slug" => "[a-z0-9\-]*"])]
    public function show(Request $request, string $slug, Authors $author): Response
    {
        $yt = $this
            ->em
            ->getRepository(YoutubeLinks::class)
            ->findBy([
                'author'        => $author,
                'is_publish'    => true
            ], [
                'published_at'  => 'DESC'
            ]);

        $links = $this
            ->em
            ->getRepository(SimpleLinks::class)
            ->findBy([
                'author'        => $author,
                'is_publish'    => true
            ], [
                'published_at'  => 'DESC'
            ]);

        return $this->render('authors/show.html.twig', [
            'slug'      => $slug,
            'author'    => $author,
            'videos'    => $yt,
            'links'     => $links
        ]);
    }
}
