<?php

namespace App\Controller;

use App\Entity\Channels;
use App\Entity\Tutos;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    private $em;
    private $translator;
    private $mailer;

    /**
     * HomeController constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param MailerInterface $mailer
     */
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repo = $this->em->getRepository(Tutos::class);
        $tutos = $repo->findLatestCategory('videos');
        $articles = $repo->findLatestCategory('articles', 3);
        $podcast = $repo->findLatestCategory('podcasts', 3);
        $channels = $this->em->getRepository(Channels::class)->findAllbyTutosNumber();
        $top_channels = $this->em->getRepository(Channels::class)->findAllbyTutosNumber(3);

        $myTutos = null;
        if ($this->getUser()) {
            $myTutos = $repo->findLatestForMe($this->getUser());
        }

        return $this->render('home/index.html.twig', [
            'tutos'         => $tutos,
            'articles'      => $articles,
            'podcast'       => $podcast,
            'channels'      => $channels,
            'top_channels'  => $top_channels,
            'mytutos'       => $myTutos,
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('home/about.html.twig');
    }

    /**
     * @Route("/confidentiality", name="confidentiality")
     */
    public function confidentiality()
    {
        return $this->render('home/confidentiality.html.twig');
    }

    /**
     * @Route("/api/sendContactForm", name="api.contact", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function sendContactForm(Request $request): JsonResponse
    {
        $datas = $request->request;

        if(!$datas->has('email') || $datas->get('email') === '') {
            return $this->json([
                'message'   => ucfirst($this->translator->trans('contact.error.email_required')),
                'code'      => 403
            ], 403);
        }

        if(!$datas->has('subject') || $datas->get('subject') === '') {
            return $this->json([
                'message'   => ucfirst($this->translator->trans('contact.error.subject_required')),
                'code'      => 403
            ], 403);
        }

        if(!$datas->has('message') || $datas->get('message') === '') {
            return $this->json([
                'message'   => ucfirst($this->translator->trans('contact.error.message_required')),
                'code'      => 403
            ], 403);
        }

        $email = (new TemplatedEmail())
            ->from('no-reply@tutomarks.fr')
            ->to('support@tutomarks.fr')
            ->subject(ucfirst($this->translator->trans('mail.contact.subject')))
            ->htmlTemplate('email/contact.html.twig')
            ->context([
                'mail_from' => $datas->get('email'),
                'mail_subject' => $datas->get('subject'),
                'mail_message' => $datas->get('message'),
            ])
        ;
        $this->mailer->send($email);

        return $this->json([
            'message'   => ucfirst($this->translator->trans('contact.send_ok')),
            'code'      => 200
        ], 200);
    }
}
