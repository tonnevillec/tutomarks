<?php

namespace App\Controller;

use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'blog.index')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig');
    }

    #[Route('/posts/{slug}', name: 'blog.post', methods: ['GET'])]
    public function show(Posts $posts): Response
    {
        return $this->render('blog/show.html.twig', [
            'post' => $posts,
        ]);
    }
}
