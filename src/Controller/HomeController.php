<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Categories;
use App\Entity\Events;
use App\Entity\Links;
use App\Entity\Tags;
use App\Repository\YoutubeLinksRepository;
use App\Service\EmailService;
use App\Service\MyLittleTeamService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    public function __construct(private TranslatorInterface $translator,
                                private EmailService $mailer,
                                private ParameterBagInterface $param,
                                private EntityManagerInterface $em,
                                private YoutubeLinksRepository $ytRepository,
                                private HttpClientInterface $client,
                                private MyLittleTeamService $mlt,
                                private SerializerInterface $serializer
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $youtubelinks = $this->ytRepository->findLatestPublished();
        $articles = $this->em->getRepository(Links::class)->findLatestSimpleLinks('articles', 4);
        $podcasts = $this->em->getRepository(Links::class)->findLatestSimpleLinks('podcasts', 4);
        $formations = $this->em->getRepository(Links::class)->findLatestSimpleLinks('formations', 6);
        $ressources = $this->em->getRepository(Links::class)->findLatestSimpleLinks('ressources', 3);
        $authors = $this->em->getRepository(Authors::class)->findTop(6);

        $events = $this->em->getRepository(Events::class)->findEventsByDate(6);

        $hebdoo = $this->client->request(
            'GET',
            'https://hebdoo.fr/api/last'
        )->toArray();

//        $mlt = $this->getParameter('mlt_enable');

        return $this->render('home/index.html.twig', [
            'youtubelinks' => $youtubelinks,
            'articles' => $articles,
            'podcasts' => $podcasts,
            'formations' => $formations,
            'ressources' => $ressources,
            'authors' => $authors,
            'hebdoo' => $hebdoo,
            'events' => $events,
            'mlt' => false,
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        return $this->render('home/contact.html.twig');
    }

    #[Route('/sendContact', name: 'contact.send', methods: ['POST'])]
    public function sendContactForm(Request $request): RedirectResponse|Response
    {
        $datas = $request->request;
        $return = true;

        if ($datas->has('contact_robot') && !empty($datas->get('contact_robot'))) {
            $this->addFlash('danger', ucfirst($this->translator->trans('contact.error.no_robot')));
            $return = false;
        }

        if (!$datas->has('contact_email') || '' === $datas->get('contact_email')) {
            $this->addFlash('danger', ucfirst($this->translator->trans('contact.error.email_required')));
            $return = false;
        }

        if (!$datas->has('contact_subject') || '' === $datas->get('contact_subject')) {
            $this->addFlash('danger', ucfirst($this->translator->trans('contact.error.subject_required')));
            $return = false;
        }

        if (!$datas->has('contact_message') || '' === $datas->get('contact_message') || strlen($datas->get('contact_message')) < 10) {
            $this->addFlash('danger', ucfirst($this->translator->trans('contact.error.message_required')));
            $return = false;
        }

        if (!$return) {
            return $this->render('home/contact.html.twig', [
                'mail_from' => $datas->get('contact_email'),
                'mail_subject' => $datas->get('contact_subject'),
                'mail_message' => $datas->get('contact_message'),
            ]);
        }

        $this->mailer->send(
            $this->param->get('mailer_from'),
            ucfirst($this->translator->trans('mail.contact.subject')),
            'contact',
            [
                'mail_from' => $datas->get('contact_email'),
                'mail_subject' => $datas->get('contact_subject'),
                'mail_message' => $datas->get('contact_message'),
            ]
        );

        $this->addFlash('success', ucfirst($this->translator->trans('contact.send_ok')));

        return $this->redirectToRoute('home');
    }

    #[Route('/about', name: 'about')]
    public function about(Request $request): Response
    {
        return $this->render('home/about.html.twig');
    }

    #[Route('/confidentiality', name: 'confidentiality')]
    public function confidentiality(Request $request): Response
    {
        return $this->render('home/confidentiality.html.twig');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/weekly/tweet', name: 'weekly.tweet')]
    public function weeklyTweet()
    {
        $events = $this->em->getRepository(Events::class)->findWeeklyPublished();

        $categories = $this->em
            ->getRepository(Categories::class)
            ->findBy(['is_actif' => true])
        ;

        return $this->render('home/weekly_tweet.html.twig', [
            'events' => $events,
            'categories' => $categories,
        ]);
    }

    #[Route('/api/mlt', name: 'api.mlt', methods: ['GET'])]
    public function apiMlt(): JsonResponse
    {
        $mlt = ($this->getParameter('mlt_enable')) ? $this->mlt->findLatest(
            $this->getParameter('mlt_table'),
            $this->getParameter('mlt_view'),
            6
        ) : [];

        return new JsonResponse($this->serializer->serialize($mlt, 'json'), 201, [], true);
    }

    #[Route('/api/tags', name: 'api.tags', methods: ['GET'])]
    public function apiTags(): JsonResponse
    {
        $tags = $this->em->getRepository(Tags::class)->findBy([], ['title' => 'ASC']);

        return $this->json($tags, 200, [], ['groups' => 'show_tags']);
    }
}
