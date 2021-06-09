<?php

namespace App\Controller;

use App\Entity\Addurl;
use App\Entity\Categories;
use App\Entity\Channels;
use App\Entity\Comments;
use App\Entity\Evaluations;
use App\Entity\Tutos;
use App\Entity\TutoSearch;
use App\Entity\User;
use App\Entity\UserTutosInformations;
use App\Form\AddurlType;
use App\Form\TutoSearchType;
use App\Form\TutosType;
use App\Managers\BadgesManager;
use App\Service\CallApiService;
use Doctrine\DBAL\ConnectionException;
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
    private $em;
    private $translator;
    private $apiService;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, CallApiService $apiService)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->apiService = $apiService;
    }

    /**
     * @Route("/addUrl", name="tutos.addurl")
     * @param Request $request
     * @return Response
     */
    public function addUrl(Request $request): Response
    {
        $addurl = new Addurl();
        $form = $this->createForm(AddurlType::class, $addurl);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $url = $request->request->get('url');

            $youtubeId = $this->apiService->getYoutubeId($url);
            if(!is_null($youtubeId)){
                $uniq = $this->em
                    ->getRepository(Tutos::class)
                    ->findOneBy([
                        'youtube_id' => $youtubeId
                    ])
                ;
                if($uniq){
                    $this->addFlash('danger', ucfirst($this->translator->trans('tutos.add.not_uniq')));

                    return $this->render('tutos/addurl.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $video = $this->apiService->getVideoInformations($youtubeId);
                $youtubechannel = $this->apiService->getChannelInformations($video->getChannelId());
                if(!is_null($youtubechannel)){
                    $channel = $this
                        ->em
                        ->getRepository(Channels::class)
                        ->findOneBy([
                            'title' => $youtubechannel['title']
                        ]);

                    if(!$channel) {
                        $channel = (new Channels())
                            ->setTitle($youtubechannel['title'])
                            ->setDescription($youtubechannel['description'])
                            ->setThumbnailsUrl($youtubechannel['thumbnails']['default']['url'])
                            ->setYoutubeCustomUrl($youtubechannel['customUrl'])
                            ->setYoutubeId($video['channelId'])
                        ;
                        $this->em->persist($channel);
                        $this->em->flush();
                    }
                } else {
                    $channel = null;
                }

                return $this->redirectToRoute('tutos.add', [
                    'url'       => $url,
                    'video'     => $video,
                    'channel'   => !is_null($channel) ? $channel->getId() : null,
                    'category'  => 'videos'
                ]);
            }

            $uniq = $this->em
                ->getRepository(Tutos::class)
                ->findOneBy([
                    'url' => $url
                ])
            ;
            if($uniq){
                $this->addFlash('danger', ucfirst($this->translator->trans('tutos.add.not_uniq')));

                return $this->render('tutos/addurl.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            return $this->redirectToRoute('tutos.add', [
                'url'       => $url,
            ]);
        }

        return $this->render('tutos/addurl.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="tutos.add")
     * @param Request $request
     * @param BadgesManager $badgesManager
     * @param null $category
     * @param null $url
     * @param null $video
     * @param null $channel
     * @return RedirectResponse|Response
     * @throws ConnectionException
     */
    public function add(Request $request, BadgesManager $badgesManager, $category = null, $url = null, $video = null, $channel = null)
    {
        $tutos = new Tutos();

        if(!is_null($request->query->get('url'))) {
            $tutos->setUrl($request->query->get('url'));
        }

        if(!is_null($request->query->get('video')) && !is_null($request->query->get('channel'))){
            $tutos->setTitle($request->query->get('video')['title']);
            $tutos->setDescription($request->query->get('video')['description']);

            $channel = $this->em->find(Channels::class, $request->query->get('channel'));
            if($channel) {
                $tutos->setCreator($channel->getTitle());
                $tutos->setChannel($channel);
            }
        }

        if(!is_null($request->query->get('category'))) {
            $categories  = $this->em->getRepository(Categories::class)->findOneBy(['homekey' => $request->query->get('category')]);
            if($categories) {
               $tutos->setCategory($categories);
            }
        }

        $form = $this->createForm(TutosType::class, $tutos);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $tutos->setPublishedBy($this->getUser());

            if($request->request->get('tutos')['channel'] === ''){
                $newchannel = $request->request->get('newchannel');
                if($newchannel['title'] === ''){
                    $this->addFlash('danger', ucfirst($this->translator->trans('channel.add.title.error')));

                    return $this->render('tutos/new.html.twig', [
                        'form'          => $form->createView(),
                        'new_channel'   => 'tutos_channel_title'
                    ]);
                }
                $channelTitle = $newchannel['title'];
                $channelSiteUrl = $newchannel['site_url'];
                $channelDescription = '';
                $channelThumbnails = '';
                $channelYoutubeId = '';
                $channelCustomUrl = '';

                if($newchannel['youtube_id'] !== ''){
                    $youtubechannel = $this->apiService->getChannelInformations($newchannel['youtube_id']);
                    if(!is_null($youtubechannel)) {
                        $channel = $this
                            ->em
                            ->getRepository(Channels::class)
                            ->findOneBy([
                                'youtube_custom_url' => $youtubechannel['customUrl']
                            ]);

                        if(!$channel) {
                            $channelTitle = $youtubechannel['title'];
                            $channelDescription = $youtubechannel['description'];
                            $channelThumbnails = $youtubechannel['thumbnails']['default']['url'];
                            $channelCustomUrl = $youtubechannel['customUrl'];
                            $channelYoutubeId = $newchannel['youtube_id'];
                        }
                    }
                }

                $channel = (new Channels())
                    ->setTitle($channelTitle)
                    ->setDescription($channelDescription)
                    ->setThumbnailsUrl($channelThumbnails)
                    ->setYoutubeCustomUrl($channelCustomUrl)
                    ->setYoutubeId($channelYoutubeId)
                    ->setSiteUrl($channelSiteUrl)
                ;
                $this->em->persist($channel);
                $this->em->flush();

                $tutos->setChannel($channel);
            }

            $youtubeId = $this->apiService->getYoutubeId($tutos->getUrl());
            if(!is_null($youtubeId)){
                $video = $this->apiService->getVideoInformations($youtubeId);

                if($video) {
                    if($video->getThumbnails()->getMedium()) {
                        $tutos->setThumbnailsSmall($video->getThumbnails()->getMedium()->getUrl());
                    }
                    if($video->getThumbnails()->getHigh()) {
                        $tutos->setThumbnailsLarge($video->getThumbnails()->getHigh()->getUrl());
                    }
                }
            }

            if($request->request->has('userEval') && $request->request->get('userEval') !== null  && $request->request->get('userEval') !== ''){
                $tutos->setMoy($request->request->get('userEval'));
            }

            $this->em->persist($tutos);
            $this->em->beginTransaction();
            $this->em->flush();

            $tutos_count = $this->em->getRepository(Tutos::class)->countForUser($this->getUser()->getId());
            $badgesManager->checkAndUnlock($this->getUser(), 'publish', $tutos_count);

            $this->em->getConnection()->commit();

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

        $update = false;
        $youtubeId = $tuto->getYoutubeId();
        if(is_null($youtubeId)) {
            $youtubeId = $this->apiService->getYoutubeId($tuto->getUrl());
            if(!is_null($youtubeId)){
                $tuto->setYoutubeId($youtubeId);
                $update = true;
            }
        }

        if(!is_null($youtubeId)) {
            $video = $this->apiService->getVideoInformations($youtubeId);

            if($video) {
                if($video->getThumbnails()->getMedium()) {
                    $tuto->setThumbnailsSmall($video->getThumbnails()->getMedium()->getUrl());
                    $update = true;
                }
                if($video->getThumbnails()->getHigh()) {
                    $tuto->setThumbnailsLarge($video->getThumbnails()->getHigh()->getUrl());
                    $update = true;
                }
            }
        }

        if($update) {
            $this->em->persist($tuto);
            $this->em->flush();
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
            if($request->request->get('tutos')['channel'] === ''){
                $newchannel = $request->request->get('newchannel');
                if($newchannel['title'] === ''){
                    $this->addFlash('danger', ucfirst($this->translator->trans('channel.add.title.error')));

                    return $this->render('tutos/edit.html.twig', [
                        'tuto'          => $tuto,
                        'form'          => $form->createView(),
                        'new_channel'   => 'tutos_channel_title'
                    ]);
                }
                $channelTitle = $newchannel['title'];
                $channelSiteUrl = $newchannel['site_url'];
                $channelDescription = '';
                $channelThumbnails = '';
                $channelYoutubeId = '';
                $channelCustomUrl = '';

                if($newchannel['youtube_id'] !== ''){
                    $youtubechannel = $this->apiService->getChannelInformations($newchannel['youtube_id']);
                    if(!is_null($youtubechannel)) {
                        $channel = $this
                            ->em
                            ->getRepository(Channels::class)
                            ->findOneBy([
                                'youtube_custom_url' => $youtubechannel['customUrl']
                            ]);

                        if(!$channel) {
                            $channelTitle = $youtubechannel['title'];
                            $channelDescription = $youtubechannel['description'];
                            $channelThumbnails = $youtubechannel['thumbnails']['default']['url'];
                            $channelCustomUrl = $youtubechannel['customUrl'];
                            $channelYoutubeId = $newchannel['youtube_id'];
                        }
                    }
                }

                $channel = (new Channels())
                    ->setTitle($channelTitle)
                    ->setDescription($channelDescription)
                    ->setThumbnailsUrl($channelThumbnails)
                    ->setYoutubeCustomUrl($channelCustomUrl)
                    ->setYoutubeId($channelYoutubeId)
                    ->setSiteUrl($channelSiteUrl)
                ;
                $this->em->persist($channel);
                $this->em->flush();

                $tuto->setChannel($channel);
            }

            $this->em->persist($tuto);
            $this->em->flush();

            $this->addFlash('success', ucfirst($this->translator->trans('tutos.edit.validate')));

            return $this->redirectToRoute('tutos.show', [
                'id'    => $tuto->getId(),
                'slug'  => $tuto->getSlug()
            ]);
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

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/api/userTutosInformations", name="api.user.tutos.informations")
     */
    public function setUserTutoInformations(Request $request) :JsonResponse
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

        if(!$datas->has('action') || !$datas->has('value')){
            return $this->json(['message' => 'Information manquante', 'code' => 401], 401);
        }

        $infos = $this->em
            ->getRepository(UserTutosInformations::class)
            ->findOneBy([
                'user' => $user,
                'tutos' => $tutos
            ]);

        if(!$infos){
            $infos = new UserTutosInformations();
            $infos->setUser($user);
            $infos->setTutos($tutos);
        }

        switch($datas->get('action')){
            case 'shown':
                $infos->setShown(!$datas->get('value'));
                break;
            case 'pined':
                $infos->setPined(!$datas->get('value'));
                break;

            case 'postit':
                $infos->setPostit($datas->get('value'));
                break;
        }
        $this->em->persist($infos);
        $this->em->flush();

        return $this->json(['message' => 'Information bien prise en compte', 'code' => 200], 200);
    }
}
