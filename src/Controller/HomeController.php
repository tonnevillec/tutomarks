<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Links;
use App\Entity\Tags;
use App\Repository\YoutubeLinksRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    private TranslatorInterface $translator;
    private EmailService $mailer;
    private ParameterBagInterface $param;
    private EntityManagerInterface $em;
    private HttpClientInterface $client;

    public function __construct(TranslatorInterface $translator,
                                EmailService $mailer,
                                ParameterBagInterface $param,
                                EntityManagerInterface $em,
                                HttpClientInterface $client
    ) {
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->param = $param;
        $this->em = $em;
        $this->client = $client;
    }

    #[Route('/', name: 'home')]
    public function index(YoutubeLinksRepository $ytRepository): Response
    {
        $youtubelinks = $ytRepository->findLatestPublished();
        $articles = $this->em->getRepository(Links::class)->findLatestSimpleLinks('articles', 4);
        $podcasts = $this->em->getRepository(Links::class)->findLatestSimpleLinks('podcasts', 4);
        $ressources = $this->em->getRepository(Links::class)->findLatestSimpleLinks('ressources', 3);
        $authors = $this->em->getRepository(Authors::class)->findTop(6);
        $tags = $this->em->getRepository(Tags::class)->findBy([], ['title' => 'ASC']);

        $hebdoo = $this->client->request(
            'GET',
            'https://hebdoo.fr/api/last'
        )->toArray();

        return $this->render('home/index.html.twig', [
            'youtubelinks' => $youtubelinks,
            'articles' => $articles,
            'podcasts' => $podcasts,
            'ressources' => $ressources,
            'authors' => $authors,
            'tags' => $tags,
            'hebdoo' => $hebdoo,
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

//    #[Route("/migration", name: "migration")]
//    public function migration()
//    {
//        $tab = [
//            ['link' => 1, 'tag' => 2],
//            ['link' => 1, 'tag' => 3],
//            ['link' => 2, 'tag' => 2],
//            ['link' => 2, 'tag' => 3],
//            ['link' => 3, 'tag' => 2],
//            ['link' => 3, 'tag' => 3],
//            ['link' => 4, 'tag' => 1],
//            ['link' => 5, 'tag' => 2],
//            ['link' => 5, 'tag' => 3],
//            ['link' => 6, 'tag' => 3],
//            ['link' => 7, 'tag' => 21],
//            ['link' => 8, 'tag' => 6],
//            ['link' => 8, 'tag' => 11],
//            ['link' => 9, 'tag' => 6],
//            ['link' => 10, 'tag' => 19],
//            ['link' => 11, 'tag' => 3],
//            ['link' => 12, 'tag' => 2],
//            ['link' => 12, 'tag' => 3],
//            ['link' => 13, 'tag' => 17],
//            ['link' => 14, 'tag' => 2],
//            ['link' => 15, 'tag' => 2],
//            ['link' => 15, 'tag' => 3],
//            ['link' => 16, 'tag' => 2],
//            ['link' => 16, 'tag' => 3],
//            ['link' => 17, 'tag' => 2],
//            ['link' => 17, 'tag' => 3],
//            ['link' => 18, 'tag' => 4],
//            ['link' => 19, 'tag' => 6],
//            ['link' => 19, 'tag' => 9],
//            ['link' => 19, 'tag' => 10],
//            ['link' => 19, 'tag' => 11],
//            ['link' => 19, 'tag' => 21],
//            ['link' => 20, 'tag' => 4],
//            ['link' => 20, 'tag' => 12],
//            ['link' => 21, 'tag' => 23],
//            ['link' => 21, 'tag' => 24],
//            ['link' => 22, 'tag' => 2],
//            ['link' => 22, 'tag' => 5],
//            ['link' => 23, 'tag' => 2],
//            ['link' => 23, 'tag' => 3],
//            ['link' => 24, 'tag' => 2],
//            ['link' => 25, 'tag' => 10],
//            ['link' => 26, 'tag' => 10],
//            ['link' => 27, 'tag' => 2],
//            ['link' => 27, 'tag' => 3],
//            ['link' => 28, 'tag' => 1],
//            ['link' => 29, 'tag' => 1],
//            ['link' => 30, 'tag' => 1],
//            ['link' => 30, 'tag' => 11],
//            ['link' => 31, 'tag' => 10],
//            ['link' => 32, 'tag' => 19],
//            ['link' => 33, 'tag' => 28],
//            ['link' => 34, 'tag' => 3],
//            ['link' => 34, 'tag' => 30],
//            ['link' => 35, 'tag' => 3],
//            ['link' => 35, 'tag' => 29],
//            ['link' => 36, 'tag' => 2],
//            ['link' => 37, 'tag' => 19],
//            ['link' => 38, 'tag' => 2],
//            ['link' => 38, 'tag' => 3],
//            ['link' => 38, 'tag' => 29],
//            ['link' => 39, 'tag' => 2],
//            ['link' => 39, 'tag' => 14],
//            ['link' => 39, 'tag' => 31],
//            ['link' => 40, 'tag' => 2],
//            ['link' => 40, 'tag' => 3],
//            ['link' => 41, 'tag' => 3],
//            ['link' => 42, 'tag' => 2],
//            ['link' => 42, 'tag' => 3],
//            ['link' => 42, 'tag' => 4],
//            ['link' => 42, 'tag' => 14],
//            ['link' => 42, 'tag' => 16],
//            ['link' => 43, 'tag' => 4],
//            ['link' => 43, 'tag' => 20],
//            ['link' => 44, 'tag' => 2],
//            ['link' => 44, 'tag' => 3],
//            ['link' => 45, 'tag' => 2],
//            ['link' => 45, 'tag' => 3],
//            ['link' => 46, 'tag' => 11],
//            ['link' => 47, 'tag' => 3],
//            ['link' => 48, 'tag' => 14],
//            ['link' => 49, 'tag' => 3],
//            ['link' => 49, 'tag' => 14],
//            ['link' => 50, 'tag' => 16],
//            ['link' => 51, 'tag' => 3],
//            ['link' => 52, 'tag' => 2],
//            ['link' => 52, 'tag' => 3],
//            ['link' => 53, 'tag' => 2],
//            ['link' => 53, 'tag' => 3],
//            ['link' => 54, 'tag' => 11],
//            ['link' => 55, 'tag' => 1],
//            ['link' => 56, 'tag' => 2],
//            ['link' => 57, 'tag' => 2],
//            ['link' => 57, 'tag' => 3],
//            ['link' => 58, 'tag' => 2],
//            ['link' => 58, 'tag' => 11],
//            ['link' => 59, 'tag' => 9],
//            ['link' => 59, 'tag' => 10],
//            ['link' => 59, 'tag' => 11],
//            ['link' => 60, 'tag' => 9],
//            ['link' => 60, 'tag' => 10],
//            ['link' => 60, 'tag' => 11],
//            ['link' => 61, 'tag' => 2],
//            ['link' => 61, 'tag' => 3],
//            ['link' => 63, 'tag' => 2],
//            ['link' => 63, 'tag' => 3],
//            ['link' => 64, 'tag' => 2],
//            ['link' => 64, 'tag' => 3],
//            ['link' => 64, 'tag' => 14],
//            ['link' => 64, 'tag' => 16],
//            ['link' => 64, 'tag' => 29],
//            ['link' => 65, 'tag' => 3],
//            ['link' => 66, 'tag' => 2],
//            ['link' => 66, 'tag' => 3],
//            ['link' => 67, 'tag' => 9],
//            ['link' => 67, 'tag' => 11],
//            ['link' => 68, 'tag' => 9],
//            ['link' => 68, 'tag' => 10],
//            ['link' => 68, 'tag' => 11],
//            ['link' => 69, 'tag' => 2],
//            ['link' => 69, 'tag' => 3],
//            ['link' => 70, 'tag' => 1],
//            ['link' => 70, 'tag' => 11],
//            ['link' => 71, 'tag' => 1],
//            ['link' => 71, 'tag' => 11],
//            ['link' => 72, 'tag' => 1],
//            ['link' => 72, 'tag' => 11],
//            ['link' => 73, 'tag' => 1],
//            ['link' => 73, 'tag' => 11],
//            ['link' => 74, 'tag' => 2],
//            ['link' => 74, 'tag' => 3],
//            ['link' => 75, 'tag' => 2],
//            ['link' => 75, 'tag' => 3],
//            ['link' => 76, 'tag' => 14],
//            ['link' => 77, 'tag' => 2],
//            ['link' => 77, 'tag' => 3],
//            ['link' => 78, 'tag' => 3],
//            ['link' => 78, 'tag' => 19],
//            ['link' => 79, 'tag' => 14],
//            ['link' => 80, 'tag' => 11],
//            ['link' => 81, 'tag' => 10],
//            ['link' => 81, 'tag' => 45],
//            ['link' => 81, 'tag' => 46],
//            ['link' => 82, 'tag' => 3],
//            ['link' => 83, 'tag' => 3],
//            ['link' => 83, 'tag' => 11],
//            ['link' => 84, 'tag' => 2],
//            ['link' => 84, 'tag' => 3],
//            ['link' => 85, 'tag' => 2],
//            ['link' => 85, 'tag' => 3],
//            ['link' => 86, 'tag' => 1],
//            ['link' => 87, 'tag' => 3],
//            ['link' => 87, 'tag' => 11],
//            ['link' => 88, 'tag' => 3],
//            ['link' => 89, 'tag' => 25],
//            ['link' => 90, 'tag' => 25],
//            ['link' => 91, 'tag' => 3],
//            ['link' => 91, 'tag' => 29],
//            ['link' => 92, 'tag' => 9],
//            ['link' => 92, 'tag' => 11],
//            ['link' => 93, 'tag' => 3],
//            ['link' => 94, 'tag' => 25],
//            ['link' => 95, 'tag' => 1],
//            ['link' => 95, 'tag' => 11],
//            ['link' => 96, 'tag' => 1],
//            ['link' => 96, 'tag' => 11],
//            ['link' => 97, 'tag' => 1],
//            ['link' => 97, 'tag' => 11],
//            ['link' => 98, 'tag' => 29],
//            ['link' => 100, 'tag' => 1],
//            ['link' => 100, 'tag' => 26],
//            ['link' => 101, 'tag' => 3],
//            ['link' => 102, 'tag' => 2],
//            ['link' => 102, 'tag' => 11],
//            ['link' => 103, 'tag' => 9],
//            ['link' => 103, 'tag' => 10],
//            ['link' => 103, 'tag' => 11],
//            ['link' => 104, 'tag' => 2],
//            ['link' => 104, 'tag' => 3],
//            ['link' => 104, 'tag' => 33],
//            ['link' => 105, 'tag' => 29],
//            ['link' => 105, 'tag' => 34],
//            ['link' => 106, 'tag' => 14],
//            ['link' => 106, 'tag' => 15],
//            ['link' => 106, 'tag' => 35],
//            ['link' => 106, 'tag' => 36],
//            ['link' => 106, 'tag' => 40],
//            ['link' => 106, 'tag' => 41],
//            ['link' => 107, 'tag' => 1],
//            ['link' => 107, 'tag' => 10],
//            ['link' => 108, 'tag' => 2],
//            ['link' => 109, 'tag' => 38],
//            ['link' => 110, 'tag' => 34],
//            ['link' => 111, 'tag' => 14],
//            ['link' => 112, 'tag' => 20],
//            ['link' => 113, 'tag' => 25],
//            ['link' => 114, 'tag' => 25],
//            ['link' => 115, 'tag' => 25],
//            ['link' => 116, 'tag' => 9],
//            ['link' => 116, 'tag' => 10],
//            ['link' => 117, 'tag' => 9],
//            ['link' => 117, 'tag' => 10],
//            ['link' => 118, 'tag' => 9],
//            ['link' => 118, 'tag' => 10],
//            ['link' => 118, 'tag' => 11],
//            ['link' => 119, 'tag' => 47],
//            ['link' => 120, 'tag' => 3],
//            ['link' => 121, 'tag' => 25],
//            ['link' => 122, 'tag' => 25],
//            ['link' => 123, 'tag' => 10],
//            ['link' => 124, 'tag' => 12],
//            ['link' => 125, 'tag' => 14],
//            ['link' => 126, 'tag' => 3],
//            ['link' => 127, 'tag' => 3],
//            ['link' => 128, 'tag' => 10],
//            ['link' => 129, 'tag' => 9],
//            ['link' => 129, 'tag' => 10],
//            ['link' => 129, 'tag' => 11],
//            ['link' => 130, 'tag' => 3],
//            ['link' => 131, 'tag' => 3],
//            ['link' => 132, 'tag' => 3],
//            ['link' => 133, 'tag' => 3],
//            ['link' => 134, 'tag' => 38],
//            ['link' => 134, 'tag' => 48],
//            ['link' => 135, 'tag' => 3],
//            ['link' => 135, 'tag' => 20],
//            ['link' => 136, 'tag' => 2],
//            ['link' => 136, 'tag' => 3],
//            ['link' => 137, 'tag' => 9],
//            ['link' => 138, 'tag' => 2],
//            ['link' => 138, 'tag' => 3],
//            ['link' => 139, 'tag' => 14],
//            ['link' => 140, 'tag' => 4],
//            ['link' => 141, 'tag' => 4],
//            ['link' => 142, 'tag' => 3],
//            ['link' => 143, 'tag' => 9],
//            ['link' => 143, 'tag' => 10],
//            ['link' => 143, 'tag' => 11],
//            ['link' => 144, 'tag' => 3],
//            ['link' => 144, 'tag' => 11],
//            ['link' => 145, 'tag' => 49],
//            ['link' => 146, 'tag' => 2],
//            ['link' => 147, 'tag' => 10],
//            ['link' => 148, 'tag' => 9],
//            ['link' => 150, 'tag' => 11],
//            ['link' => 151, 'tag' => 1],
//            ['link' => 151, 'tag' => 20],
//            ['link' => 151, 'tag' => 50],
//            ['link' => 152, 'tag' => 11],
//            ['link' => 153, 'tag' => 1],
//            ['link' => 154, 'tag' => 2],
//            ['link' => 154, 'tag' => 12],
//            ['link' => 155, 'tag' => 23],
//            ['link' => 158, 'tag' => 1],
//            ['link' => 159, 'tag' => 2],
//            ['link' => 160, 'tag' => 12],
//            ['link' => 161, 'tag' => 1],
//            ['link' => 163, 'tag' => 33],
//            ['link' => 164, 'tag' => 11],
//            ['link' => 165, 'tag' => 2],
//            ['link' => 165, 'tag' => 3],
//            ['link' => 166, 'tag' => 2],
//            ['link' => 166, 'tag' => 3],
//            ['link' => 167, 'tag' => 11],
//            ['link' => 168, 'tag' => 2],
//            ['link' => 168, 'tag' => 3],
//            ['link' => 168, 'tag' => 19],
//            ['link' => 169, 'tag' => 19],
//            ['link' => 170, 'tag' => 14],
//            ['link' => 170, 'tag' => 29],
//            ['link' => 170, 'tag' => 38],
//            ['link' => 171, 'tag' => 1],
//            ['link' => 171, 'tag' => 51],
//            ['link' => 172, 'tag' => 2],
//            ['link' => 173, 'tag' => 2],
//            ['link' => 173, 'tag' => 3],
//            ['link' => 174, 'tag' => 2],
//            ['link' => 175, 'tag' => 3],
//            ['link' => 176, 'tag' => 38],
//            ['link' => 176, 'tag' => 39],
//            ['link' => 177, 'tag' => 3],
//            ['link' => 177, 'tag' => 33],
//            ['link' => 178, 'tag' => 11],
//            ['link' => 179, 'tag' => 2],
//            ['link' => 180, 'tag' => 2],
//            ['link' => 181, 'tag' => 10],
//            ['link' => 182, 'tag' => 4],
//            ['link' => 182, 'tag' => 23],
//            ['link' => 182, 'tag' => 52],
//            ['link' => 183, 'tag' => 2],
//            ['link' => 184, 'tag' => 21],
//            ['link' => 185, 'tag' => 4],
//            ['link' => 186, 'tag' => 14],
//            ['link' => 187, 'tag' => 21],
//            ['link' => 188, 'tag' => 19],
//            ['link' => 188, 'tag' => 21],
//            ['link' => 189, 'tag' => 9],
//            ['link' => 189, 'tag' => 10],
//            ['link' => 189, 'tag' => 21],
//            ['link' => 190, 'tag' => 11],
//            ['link' => 191, 'tag' => 33],
//            ['link' => 192, 'tag' => 3],
//            ['link' => 192, 'tag' => 14],
//            ['link' => 193, 'tag' => 23],
//            ['link' => 193, 'tag' => 52],
//            ['link' => 194, 'tag' => 2],
//            ['link' => 195, 'tag' => 21],
//            ['link' => 196, 'tag' => 19],
//            ['link' => 197, 'tag' => 19],
//            ['link' => 198, 'tag' => 3],
//            ['link' => 199, 'tag' => 19],
//            ['link' => 200, 'tag' => 51],
//            ['link' => 201, 'tag' => 19],
//            ['link' => 202, 'tag' => 14],
//            ['link' => 202, 'tag' => 37],
//            ['link' => 202, 'tag' => 40],
//            ['link' => 203, 'tag' => 11],
//            ['link' => 204, 'tag' => 11],
//            ['link' => 205, 'tag' => 11],
//            ['link' => 206, 'tag' => 9],
//            ['link' => 206, 'tag' => 10],
//            ['link' => 206, 'tag' => 46],
//            ['link' => 207, 'tag' => 11],
//            ['link' => 208, 'tag' => 2],
//            ['link' => 208, 'tag' => 3],
//            ['link' => 209, 'tag' => 9],
//            ['link' => 209, 'tag' => 10],
//            ['link' => 209, 'tag' => 11],
//            ['link' => 210, 'tag' => 4],
//            ['link' => 211, 'tag' => 19],
//            ['link' => 212, 'tag' => 3],
//            ['link' => 213, 'tag' => 10],
//            ['link' => 214, 'tag' => 10],
//            ['link' => 215, 'tag' => 10],
//            ['link' => 216, 'tag' => 19],
//            ['link' => 217, 'tag' => 2],
//            ['link' => 218, 'tag' => 39],
//            ['link' => 219, 'tag' => 4],
//            ['link' => 220, 'tag' => 19],
//            ['link' => 221, 'tag' => 19],
//            ['link' => 222, 'tag' => 29],
//            ['link' => 223, 'tag' => 2],
//            ['link' => 223, 'tag' => 15],
//            ['link' => 223, 'tag' => 31],
//            ['link' => 223, 'tag' => 36],
//            ['link' => 223, 'tag' => 53],
//            ['link' => 224, 'tag' => 2],
//            ['link' => 224, 'tag' => 31],
//            ['link' => 224, 'tag' => 35],
//            ['link' => 224, 'tag' => 53],
//            ['link' => 225, 'tag' => 3],
//            ['link' => 225, 'tag' => 14],
//            ['link' => 226, 'tag' => 19],
//            ['link' => 227, 'tag' => 3],
//            ['link' => 227, 'tag' => 6],
//            ['link' => 227, 'tag' => 14],
//            ['link' => 227, 'tag' => 32],
//            ['link' => 227, 'tag' => 53],
//            ['link' => 228, 'tag' => 20],
//            ['link' => 228, 'tag' => 52],
//            ['link' => 229, 'tag' => 3],
//            ['link' => 230, 'tag' => 12],
//            ['link' => 231, 'tag' => 19],
//            ['link' => 232, 'tag' => 3],
//            ['link' => 232, 'tag' => 14],
//            ['link' => 233, 'tag' => 9],
//            ['link' => 233, 'tag' => 11],
//            ['link' => 234, 'tag' => 12],
//            ['link' => 235, 'tag' => 4],
//            ['link' => 236, 'tag' => 3],
//            ['link' => 236, 'tag' => 38],
//            ['link' => 237, 'tag' => 2],
//            ['link' => 237, 'tag' => 41],
//            ['link' => 238, 'tag' => 3],
//            ['link' => 239, 'tag' => 9],
//            ['link' => 239, 'tag' => 10],
//            ['link' => 239, 'tag' => 11],
//            ['link' => 240, 'tag' => 23],
//            ['link' => 241, 'tag' => 3],
//            ['link' => 241, 'tag' => 9],
//            ['link' => 242, 'tag' => 12],
//            ['link' => 243, 'tag' => 54],
//            ['link' => 244, 'tag' => 25],
//            ['link' => 244, 'tag' => 33],
//            ['link' => 245, 'tag' => 2],
//            ['link' => 246, 'tag' => 6],
//            ['link' => 246, 'tag' => 11],
//            ['link' => 246, 'tag' => 28],
//            ['link' => 246, 'tag' => 41],
//            ['link' => 247, 'tag' => 9],
//            ['link' => 248, 'tag' => 3],
//            ['link' => 249, 'tag' => 3],
//            ['link' => 249, 'tag' => 38],
//            ['link' => 250, 'tag' => 12],
//            ['link' => 251, 'tag' => 3],
//            ['link' => 252, 'tag' => 4],
//            ['link' => 253, 'tag' => 54],
//            ['link' => 254, 'tag' => 11],
//            ['link' => 254, 'tag' => 20],
//            ['link' => 255, 'tag' => 2],
//            ['link' => 256, 'tag' => 49],
//            ['link' => 257, 'tag' => 15],
//            ['link' => 257, 'tag' => 35],
//            ['link' => 258, 'tag' => 16],
//            ['link' => 259, 'tag' => 3],
//            ['link' => 260, 'tag' => 19],
//            ['link' => 261, 'tag' => 5],
//            ['link' => 261, 'tag' => 14],
//            ['link' => 262, 'tag' => 4],
//            ['link' => 263, 'tag' => 2],
//            ['link' => 263, 'tag' => 3],
//            ['link' => 263, 'tag' => 14],
//            ['link' => 264, 'tag' => 4],
//            ['link' => 265, 'tag' => 37],
//            ['link' => 266, 'tag' => 19],
//            ['link' => 267, 'tag' => 12],
//            ['link' => 268, 'tag' => 6],
//            ['link' => 269, 'tag' => 3],
//            ['link' => 270, 'tag' => 2],
//            ['link' => 271, 'tag' => 25],
//            ['link' => 272, 'tag' => 4],
//            ['link' => 272, 'tag' => 12],
//            ['link' => 273, 'tag' => 19],
//            ['link' => 274, 'tag' => 19],
//            ['link' => 275, 'tag' => 19],
//            ['link' => 276, 'tag' => 21],
//            ['link' => 277, 'tag' => 2],
//            ['link' => 278, 'tag' => 3],
//            ['link' => 279, 'tag' => 4],
//            ['link' => 279, 'tag' => 12],
//            ['link' => 280, 'tag' => 25],
//            ['link' => 281, 'tag' => 19],
//            ['link' => 282, 'tag' => 19],
//            ['link' => 283, 'tag' => 3],
//            ['link' => 284, 'tag' => 3],
//            ['link' => 284, 'tag' => 38],
//            ['link' => 284, 'tag' => 39],
//            ['link' => 285, 'tag' => 19],
//            ['link' => 286, 'tag' => 39],
//            ['link' => 287, 'tag' => 23],
//            ['link' => 288, 'tag' => 29],
//            ['link' => 289, 'tag' => 3],
//            ['link' => 289, 'tag' => 12],
//            ['link' => 290, 'tag' => 33],
//            ['link' => 291, 'tag' => 10],
//            ['link' => 292, 'tag' => 43],
//            ['link' => 293, 'tag' => 11],
//            ['link' => 294, 'tag' => 9],
//            ['link' => 294, 'tag' => 10],
//            ['link' => 294, 'tag' => 11],
//            ['link' => 295, 'tag' => 19],
//            ['link' => 296, 'tag' => 3],
//            ['link' => 297, 'tag' => 19],
//            ['link' => 298, 'tag' => 2],
//            ['link' => 299, 'tag' => 21],
//            ['link' => 300, 'tag' => 10],
//            ['link' => 301, 'tag' => 2],
//            ['link' => 301, 'tag' => 3],
//            ['link' => 302, 'tag' => 10],
//            ['link' => 303, 'tag' => 2],
//            ['link' => 303, 'tag' => 3],
//            ['link' => 303, 'tag' => 14],
//            ['link' => 304, 'tag' => 10],
//            ['link' => 305, 'tag' => 14],
//            ['link' => 306, 'tag' => 4],
//            ['link' => 306, 'tag' => 50],
//            ['link' => 307, 'tag' => 25],
//            ['link' => 308, 'tag' => 3],
//            ['link' => 309, 'tag' => 9],
//            ['link' => 309, 'tag' => 10],
//            ['link' => 310, 'tag' => 19],
//            ['link' => 311, 'tag' => 19],
//            ['link' => 313, 'tag' => 21],
//            ['link' => 314, 'tag' => 45],
//            ['link' => 314, 'tag' => 50],
//            ['link' => 315, 'tag' => 14],
//            ['link' => 316, 'tag' => 4],
//            ['link' => 316, 'tag' => 55],
//            ['link' => 317, 'tag' => 19],
//        ];
//
//        foreach ($tab as $t) {
//            dump($t['link']);
//            dump($t['tag']);
//
//            $link = $this->em->find(Links::class, $t['link']);
//            $tag = $this->em->find(Tags::class, $t['tag']);
//
//            if($link && $tag) {
//                $link->addTag($tag);
//                $this->em->persist($link);
//                $this->em->flush();
//            }
//        }
//
//        dd('stop');
//    }
}
