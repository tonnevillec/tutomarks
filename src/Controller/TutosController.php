<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Evaluations;
use App\Entity\Tutos;
use App\Entity\TutoSearch;
use App\Entity\User;
use App\Form\TutoSearchType;
use App\Form\TutosType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TutosController
 * @package App\Controller
 * @Route("/tutos")
 */
class TutosController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @Route("/add", name="tutos.add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $tutos = new Tutos();
        $form = $this->createForm(TutosType::class, $tutos);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $tutos->setPublishedBy($this->getUser());

            if($request->request->has('userEval') && $request->request->get('userEval') !== null  && $request->request->get('userEval') !== ''){
                $tutos->setMoy($request->request->get('userEval'));
            }

            $this->em->persist($tutos);
            $this->em->flush();

            if($request->request->has('userEval') && $request->request->get('userEval') !== null  && $request->request->get('userEval') !== ''){
                $eval = new Evaluations();
                $eval->setTutos($tutos);
                $eval->setUser($this->getUser());
                $eval->setNotation($request->request->get('userEval'));
                $this->em->persist($eval);
                $this->em->flush();
            }

            if($request->request->has('userComment') && $request->request->get('userComment') !== null  && $request->request->get('userComment') !== ''){
                $comment = new Comments();
                $comment->setTutos($tutos);
                $comment->setUser($this->getUser());
                $comment->setComment($request->request->get('userComment'));
                $this->em->persist($comment);
                $this->em->flush();
            }

            $this->addFlash('success', ucfirst($this->translator->trans('tutos.add.validate')));
            return $this->redirectToRoute('home');
        }

        return $this->render('tutos/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}-{id}", name="tutos.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Request $request
     * @param $slug
     * @param $id
     * @return RedirectResponse|Response
     */
    public function show(Request $request, $slug, $id)
    {
        $tuto = $this->em->getRepository(Tutos::class)->find($id);
        if(!$tuto) {
            $this->addFlash('danger', $this->translator->trans('error.unauthorized'));
            return $this->redirectToRoute('home');
        }

        return $this->render('tutos/show.html.twig', [
            'tuto'      => $tuto,
            'from'      => $request->server->get('HTTP_REFERER')
        ]);
    }

    /**
     * @Route("/user", name="tutos.user")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        if(!$this->getUser()) {
            $this->addFlash('danger', ucfirst($this->translator->trans('error.unauthorized')));
            return $this->redirectToRoute('home');
        }

        $search = new TutoSearch();
        $form = $this->createForm(TutoSearchType::class, $search);
        $form->handleRequest($request);

        $result = $paginator->paginate(
            $this->em->getRepository(Tutos::class)->findForMe($this->getUser(), $search),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('tutos/user.html.twig', [
            'form'      => $form->createView(),
            'result'    => $result
        ]);
    }

    /**
     * @Route("/{id}/deadlink", name="tutos.deadlink")
     * @param Tutos $tutos
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function deadLink(Request $request, Tutos $tutos, MailerInterface $mailer)
    {
        $email = (new TemplatedEmail())
            ->from($this->getUser()->getEmail())
            ->to('support@tutomarks.fr')
            ->subject(ucfirst($this->translator->trans('deadlink.mail.subject')))
            ->htmlTemplate('email/deadlink.html.twig')
            ->context([
                'mail_from' => $this->getUser()->getEmail(),
                'tuto' => $tutos,
            ])
        ;
        $mailer->send($email);

        $this->addFlash('success', ucfirst($this->translator->trans('deadlink.thanks.flash')));

        return $this->redirectToRoute('tutos.show', [
            'id'    => $tutos->getId(),
            'slug'  => $tutos->getSlug()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tutos.edit")
     * @param Request $request
     * @param Tutos $tuto
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Tutos $tuto)
    {
        if(!$tuto) {
            $this->addFlash('danger', ucfirst($this->translator->trans('error.unauthorized')));
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(TutosType::class, $tuto);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($tuto);
            $this->em->flush();

            $this->addFlash('success', ucfirst($this->translator->trans('tutos.edit.validate')));

            return $this->redirectToRoute('tutos.show', [ 'id' => $tuto->getId()]);
        }

        return $this->render('tutos/edit.html.twig', [
            'tuto'      => $tuto,
            'form'      => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/api/eval", name="api.eval.tutos")
     */
    public function setEval(Request $request) :JsonResponse
    {
        $datas = $request->request;

        if($datas->has('user')){
            $user = $this->em->getRepository(User::class)->find($datas->get('user'));
            if(!$user){
                return $this->json(['message' => 'Accès non autorisé', 'code' => 403], 403);
            }
        }

        if($datas->has('tutos')){
            $tutos = $this->em->getRepository(Tutos::class)->find($datas->get('tutos'));
            if(!$tutos){
                return $this->json(['message' => 'Information manquante', 'code' => 401], 401);
            }
        }

        if(!$datas->has('eval')){
            return $this->json(['message' => 'Information manquante', 'code' => 401], 401);
        }

        $eval = $this->em->getRepository(Evaluations::class)->findBy(['user' => $user, 'tutos' => $tutos]);
        if(count($eval) > 0){
            $eval = $eval[0];
        } else {
            $eval = new Evaluations();
            $eval->setUser($user);
            $eval->setTutos($tutos);
        }

        $eval->setNotation($datas->get('eval'));
        $this->em->persist($eval);
        $this->em->flush();

        // Maj moyenne
        $nb = 0;
        $somme = 0;
        foreach ($tutos->getEvaluations() as $eval) {
            $somme += $eval->getNotation();
            $nb++;
        }
        if($nb != 0) {
            $tutos->setMoy($somme / $nb);
        } else {
            $tutos->setMoy(null);
        }
        $this->em->flush();

        return $this->json(['message' => 'Evaluation bien prise en compte', 'code' => 200], 200);
    }

}
