<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Events;
use App\Form\EventsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/events')]
class EventsController extends AbstractController
{
    private EntityManagerInterface $em;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    #[Route('/', name: 'events.index')]
    public function index(): Response
    {
        $events = $this->em->getRepository(Events::class)->findFuturEvents();
        $finished = $this->em->getRepository(Events::class)->findFinishEvents();

        return $this->render('events/index.html.twig', [
            'events' => $events,
            'finished' => $finished,
        ]);
    }

    #[Route('/add', name: 'events.add')]
    public function add(Request $request): Response
    {
        $event = new Events();
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $request->request->get('events');

            $event->setPublishedBy($this->getUser());

            $uniqurl = $this->em->getRepository(Events::class)->findOneBy(['url' => $datas['url']]);
            if ($uniqurl) {
                $this->addFlash('danger', ucfirst($this->translator->trans('events.add.error.uniq_url')));

                return $this->render('', [
                    'form' => $form->createView(),
                ]);
            }

            if ('' === $datas['author']) {
                $newAuthor = $request->request->get('newauthors');

                if ('' === $newAuthor['title']) {
                    $this->addFlash('danger', ucfirst($this->translator->trans('channel.add.title.error')));

                    return $this->render('', [
                        'form' => $form->createView(),
                        'new_author' => 'simple_links_author_title',
                    ]);
                }

                $author = null;
                if ('' !== $newAuthor['youtube']) {
                    $notuniq = $this->em->getRepository(Authors::class)->findOneBy(['youtube' => $newAuthor['youtube']]);
                    if ($notuniq) {
                        $author = $notuniq;
                    }
                }

                if (is_null($author)) {
                    $author = (new Authors())
                        ->setTitle($newAuthor['title'])
                        ->setGithub($newAuthor['github'])
                        ->setTwitch($newAuthor['twitch'])
                        ->setTwitter($newAuthor['twitter'])
                        ->setYoutube($newAuthor['youtube'])
                        ->setSiteUrl($newAuthor['site_url'])
                    ;
                    $this->em->persist($author);
                    $this->em->flush();

                    $event->setAuthor($author);
                }
            }

            $this->em->persist($event);
            $this->em->flush();

            $this->addFlash('success', 'Votre événement a été ajouté');

            return $this->redirectToRoute('home');
        }

        return $this->render('events/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
