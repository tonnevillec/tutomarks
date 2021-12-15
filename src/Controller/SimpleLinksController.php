<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Categories;
use App\Entity\Links;
use App\Entity\SimpleLinks;
use App\Form\SimpleLinksEditType;
use App\Form\SimpleLinksType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/simpleLinks')]
class SimpleLinksController extends AbstractController
{
    private EntityManagerInterface $em;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    #[Route('/add/{category}', name: 'slinks.add')]
    public function add(Request $request, string $category = null): Response
    {
        $link = new SimpleLinks();
        if (!is_null($category)) {
            $cat = $this->em->getRepository(Categories::class)->findOneBy(['code' => $category]);
            if ($cat) {
                $link->setCategory($cat);
            }
        }
        $form = $this->createForm(SimpleLinksType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $request->request->get('simple_links');

            $link->setPublishedBy($this->getUser());

            $uniqurl = $this->em->getRepository(Links::class)->findOneBy(['url' => $datas['url']]);
            if ($uniqurl) {
                $this->addFlash('danger', ucfirst($this->translator->trans('link.add.error.uniq_url')));

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

                    $link->setAuthor($author);
                }
            }

            $this->em->persist($link);
            $this->em->flush();

            $this->addFlash('success', 'Votre lien a été ajouté');

            return $this->redirectToRoute('home');
        }

        return $this->render('simple_links/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'slinks.edit')]
    public function edit(Request $request, SimpleLinks $link): RedirectResponse|Response
    {
        $this->denyAccessUnlessGranted('link_edit', $link);

        $form = $this->createForm(SimpleLinksEditType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($link);
            $this->em->flush();

            $this->addFlash('success', 'Votre partage a correctement été modifié');

            return $this->redirectToRoute('users.my_links');
        }

        return $this->render('simple_links/edit.html.twig', [
            'ytLink' => $link,
            'form' => $form->createView(),
        ]);
    }
}
