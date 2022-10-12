<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Categories;
use App\Entity\Languages;
use App\Entity\Tags;
use App\Entity\YoutubeLinks;
use App\Form\YoutubeLinksEditType;
use App\Form\YoutubeLinksType;
use App\Service\CallApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/links')]
class YoutubeLinksController extends AbstractController
{
    public function __construct(
        private readonly CallApiService $apiService,
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface $translator
    ) {
    }

    #[Route('/addYtLink', name: 'ytlinks.add')]
    public function add(Request $request): Response
    {
        $ytLink = new YoutubeLinks();
        $form = $this->createForm(YoutubeLinksType::class, $ytLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->request->all()['url'];

            $ytId = $this->apiService->getYoutubeId($url);
            if (!is_null($ytId)) {
                $uniq = $this->em
                    ->getRepository(YoutubeLinks::class)
                    ->findOneBy([
                        'youtube_id' => $ytId,
                    ])
                ;

                if ($uniq) {
                    $this->addFlash('danger', ucfirst($this->translator->trans('tutos.add.not_uniq')));

                    return $this->redirectToRoute('ytlinks.add');
                }

                $video = $this->apiService->getVideoInformations($ytId);
                $ytauthors = $this->apiService->getChannelInformations($video->getChannelId());
                if (!is_null($ytauthors)) {
                    $authors = $this->em
                        ->getRepository(Authors::class)
                        ->findOneBy([
                            'title' => $ytauthors['title'],
                        ]);

                    if (!$authors) {
                        $authors = (new Authors())
                            ->setTitle($ytauthors['title'])
                            ->setDescription($ytauthors['description'])
                            ->setLogo($ytauthors['thumbnails']['default']['url'])
                            ->setYoutube($video['channelId'])
                        ;
                        $this->em->persist($authors);
                        $this->em->flush();
                    }
                } else {
                    $this->addFlash('danger', ucfirst($this->translator->trans('ytlinks.add.unknown_author')));

                    return $this->redirectToRoute('ytlinks.add');
                }

                $category = $this->em->getRepository(Categories::class)->findOneBy(['code' => 'videos']);
                $language = $this->em->getRepository(Languages::class)->findOneBy(['shortname' => 'FR']);

                $ytLink = (new YoutubeLinks())
                    ->setYoutubeId($ytId)
                    ->setTitle($video->getTitle())
                    ->setAuthor($authors)
                    ->setCategory($category)
                    ->setLanguage($language)
                    ->setUrl($url)
                    ->setDescription($video->getDescription())
                    ->setIsPublish(false)
                    ->setPublishedBy($this->getUser())
                ;
                if ($video->getThumbnails()->getMedium()) {
                    $ytLink->setImgSmall($video->getThumbnails()->getDefault()->getUrl());
                }
                if ($video->getThumbnails()->getMedium()) {
                    $ytLink->setImgMedium($video->getThumbnails()->getMedium()->getUrl());
                }
                if ($video->getThumbnails()->getHigh()) {
                    $ytLink->setImgLarge($video->getThumbnails()->getHigh()->getUrl());
                }

                $this->em->persist($ytLink);
                $this->em->flush();

                $this->addFlash('info', 'Vérifier et compléter les informations');

                return $this->redirectToRoute('ytlinks.edit', [
                    'id' => $ytLink->getId(),
                ]);
            }

            $this->addFlash('danger', ucfirst($this->translator->trans('tutos.add.no_datas')));

            return $this->redirectToRoute('ytlinks.add');
        }

        return $this->render('youtube_links/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/addPlaylist', name: 'ytlinks.playlist.add')]
    public function addPlaylist(): Response
    {
        return $this->render('youtube_links/playlist_add.html.twig');
    }

    #[Route('/search/playlist/{search}', name: 'ytlinks.playlist.api.search', methods: ['GET'])]
    public function searchPlaylist(string $search): JsonResponse
    {
        $data = [];
        $playlist = $this->apiService->getPlaylistInformations($search);

        if ($playlist[0]) {
            $infos = [
                'title' => $playlist[0]['snippet']['title'],
                'channelId' => $playlist[0]['snippet']['channelId'],
                'channelTitle' => $playlist[0]['snippet']['channelTitle'],
                'thumbnails' => $playlist[0]['snippet']['thumbnails'],
            ];

            $items = $this->apiService->getPlaylistItemsInformations($search);

            $data['infos'] = $infos;
            $data['items'] = $items;
        }

        return $this->json(['data' => $data]);
    }

    #[Route('/edit/{id}', name: 'ytlinks.edit')]
    public function edit(Request $request, YoutubeLinks $ytLink)
    {
        $this->denyAccessUnlessGranted('link_edit', $ytLink);

        $form = $this->createForm(YoutubeLinksEditType::class, $ytLink);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $datas = $request->request->all();
            $tags = array_key_exists('tags', $datas) ? $datas['tags'] : [];
            foreach ($tags as $tag) {
                $t = $this->em->find(Tags::class, $tag);
                if (!$t) {
                    $t = (new Tags())
                        ->setTitle($tag)
                    ;
                    $this->em->persist($t);
                    $this->em->flush();
                }
                $ytLink->addTag($t);
            }

            $this->em->persist($ytLink);
            $this->em->flush();

            $this->addFlash('success', 'Votre partage a correctement été modifié');

            return $this->redirectToRoute('links.show', [
                'slug' => $ytLink->getSlug(),
                'id' => $ytLink->getId(),
            ]);
        }

        return $this->render('youtube_links/edit.html.twig', [
            'ytLink' => $ytLink,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}-{id}', name: 'links.show', requirements: ['slug' => "[a-z0-9\-]*"])]
    public function show(Request $request, $slug, YoutubeLinks $yt): Response
    {
        return $this->render('youtube_links/show.html.twig', [
            'slug' => $slug,
            'yt' => $yt,
        ]);
    }
}
