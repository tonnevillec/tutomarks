<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Tags;
use App\Entity\Tutos;
use App\Entity\TutoSearch;
use App\Form\TutoSearchType;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @param string|null $element
     * @param int|null $value
     * @return Response
     */
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        string $element = null,
        int $value = null
    ): Response {

        $orderby = $request->query->has('sort') ? $request->query->get('sort') : 't.published_at';
        $direction = $request->query->has('direction') ? $request->query->get('direction') : 'desc';

        $search = new TutoSearch();
        $form = $this->createForm(TutoSearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
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

        $datas = $request->query;
        if ($datas->has('element') && $datas->has('value')) {
            $go = false;
            switch ($datas->get('element')) {
                case 'word':
                    $search->setSearch($datas->get('value'));
                    $go = true;
                    break;

                case 'category':
                    $search->setCategory($this->em->find(Categories::class, $datas->get('value')));
                    $go = true;
                    break;

                case 'tag':
                    $t = new ArrayCollection();
                    $t->add($this->em->find(Tags::class, $datas->get('value')));
                    $search->setTags($t);
                    $go = true;
                    break;
            }

            if ($go) {
                $result = $paginator->paginate(
                    $this->em->getRepository(Tutos::class)->findAllVisible($search, $orderby, $direction),
                    $request->query->getInt('page', 1),
                    12
                );

                return $this->render('search/search.html.twig', [
                    'form' => $form->createView(),
                    'result' => $result
                ]);
            }
        }

        return $this->render('search/search.html.twig', [
            'form'  => $form->createView()
        ]);
    }
}
