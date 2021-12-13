<?php

namespace App\Controller;

use App\Entity\Links;
use App\Entity\LinkSearch;
use App\Form\LinkSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/search')]
class SearchController extends AbstractController
{
    private EntityManagerInterface $em;
    private PaginatorInterface $paginator;

    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'search')]
    public function index(Request $request): Response
    {
        $search = new LinkSearch();
        $form = $this->createForm(LinkSearchType::class, $search);
        $form->handleRequest($request);

        $orderby = $request->query->has('sort') ? $request->query->get('sort') : 'l.published_at';
        $direction = $request->query->has('direction') ? $request->query->get('direction') : 'desc';
        $page = 0 === $request->query->getInt('page', 1) ? 1 : $request->query->getInt('page', 1);
        $perPage = 12;

        if ($form->isSubmitted()) {
            $result = $this->paginator->paginate(
                $this->em->getRepository(Links::class)->findAllPublished($search, $orderby, $direction),
                $page,
                $perPage
            );

            return $this->render('search/index.html.twig', [
                'form' => $form->createView(),
                'result' => $result,
            ]);
        }

        $datas = $request->query;
        if ($datas->has('element') && $datas->has('value')) {
            $go = false;
            switch ($datas->get('element')) {
                case 'word':
                    $search->setSearch($datas->get('value'));
                    $go = true;
                    break;
            }

            if ($go) {
                $result = $this->paginator->paginate(
                    $this->em->getRepository(Links::class)->findAllPublished($search, $orderby, $direction),
                    $page,
                    $perPage
                );

                return $this->render('search/index.html.twig', [
                    'form' => $form->createView(),
                    'result' => $result,
                ]);
            }
        }

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
