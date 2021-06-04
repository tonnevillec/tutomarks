<?php

namespace App\Controller;

use App\Entity\Tutos;
use App\Entity\TutoSearch;
use App\Form\TutoSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="search")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $orderby = $request->query->has('sort') ? $request->query->get('sort') : 't.published_at';
        $direction = $request->query->has('direction') ? $request->query->get('direction') : 'desc';

        $search = new TutoSearch();
        $form = $this->createForm(TutoSearchType::class, $search);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $result = $paginator->paginate(
                $this->em->getRepository(Tutos::class)->findAllVisible($search, $orderby, $direction),
                $request->query->getInt('page', 1),
                12
            );

            return $this->render('search/search.html.twig', [
                'form'      => $form->createView(),
                'result'    => $result
            ]);
        }

        return $this->render('search/search.html.twig', [
            'form'  => $form->createView()
        ]);
    }
}
