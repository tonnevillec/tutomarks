<?php

namespace App\Twig;

use App\Entity\Authors;
use App\Entity\Categories;
use App\Entity\Links;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FunctionsExtension extends AbstractExtension
{
    private EntityManagerInterface $em;
    private UrlGeneratorInterface $router;
    private UrlGeneratorInterface $generator;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $router, UrlGeneratorInterface $generator)
    {
        $this->em = $em;
        $this->router = $router;
        $this->generator = $generator;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('categoryIcon', [$this, 'getCategoryIcon'], ['is_safe' => ['html']]),
            new TwigFunction('menuAuthors', [$this, 'getMenuAuthors'], ['is_safe' => ['html']]),
            new TwigFunction('menuCategories', [$this, 'getMenuCategories'], ['is_safe' => ['html']]),
            new TwigFunction('footerCategories', [$this, 'getFooterCategories'], ['is_safe' => ['html']]),
            new TwigFunction('headerMenuAddByCategories', [$this, 'getHeaderMenuAddByCategories'], ['is_safe' => ['html']]),
            new TwigFunction('notPublishedLinksCount', [$this, 'getNotPublishedLinksCount'], ['is_safe' => ['html']]),
        ];
    }

    public function getCategoryIcon(string $code): string
    {
        $category = $this->em
            ->getRepository(Categories::class)
            ->findOneBy([
                'code' => $code,
            ]);

        if (!$category) {
            return 'bi bi-box';
        }

        return !is_null($category->getLogo()) && '' !== $category->getLogo() ? $category->getLogo() : 'bi bi-box';
    }

    public function getMenuAuthors(): string
    {
        $authors = $this->em
            ->getRepository(Authors::class)
            ->findTop(5)
            ;

        if (!$authors) {
            return '';
        }

        $return = '';
        foreach ($authors as $author) {
            if ($author[0]->getLogo()) {
                $logo = '<img src="'.$author[0]->getLogo().'" alt="'.$author[0]->getTitle().'" class="img-h30 me-1 rounded-circle">';
            } elseif($author[0]->getAttachment()) {
                $logo = '<img src="'.$author[0]->getAttachment().'" alt="'.$author[0]->getTitle().'" class="img-h30 me-1 rounded-circle">';
            } else {
                $logo = '<span class="ico-author me-1 text-gray"><i class="bi bi-person"></i></span>';
            }
            $r = $this->generator->generate('authors.show', [
                'slug' => $author[0]->getSlug(),
                'id' => $author[0]->getId(),
            ]);
            $return .= '<a href="'.$r.'" class="list-group-item d-flex align-items-center">
                '.$logo.'
                <span class="text-truncate">'.$author[0]->getTitle().'</span>                
            </a>';
        }

        return $return;
    }

    public function getMenuCategories(): string
    {
        $categories = $this->em
            ->getRepository(Categories::class)
            ->findBy(['is_actif' => true], ['id' => 'ASC'])
        ;

        $return = '';
        foreach ($categories as $category) {
            $logo = !is_null($category->getLogo()) && '' !== $category->getLogo() ? $category->getLogo() : 'bi bi-box';
            $path = $this->router->generate('search', [
                'categories[]' => $category->getId(),
            ]);
            $return .= '<a href="'.$path.'" class="list-group-item d-flex align-items-center">
                <i class="'.$logo.' me-1"></i>
                <span>'.$category->getTitle().'</span>
            </a>';
        }

        return $return;
    }

    public function getFooterCategories(): string
    {
        $categories = $this->em
            ->getRepository(Categories::class)
            ->findBy(['is_actif' => true], ['id' => 'ASC'])
        ;

        $return = '';
        if (count($categories) > 0) {
            $return .= '<ul  class="nav flex-column">';
            foreach ($categories as $category) {
                $logo = !is_null($category->getLogo()) && '' !== $category->getLogo() ? $category->getLogo() : 'bi bi-box';
                $path = $this->router->generate('search', [
                    'categories[]' => $category->getId(),
                ]);
                $return .= '<li class="nav-item mb-2"><a href="'.$path.'" class="nav-link py-0 ps-3 pe-0">
                    <i class="'.$logo.' me-1"></i>
                    <span>'.$category->getTitle().'</span>
                </a></li>';
            }
            $return .= '</ul>';
        }

        return $return;
    }

    public function getHeaderMenuAddByCategories(): string
    {
        $categories = $this->em
            ->getRepository(Categories::class)
            ->findBy(['is_actif' => true])
        ;

        $return = '';
        foreach ($categories as $category) {
            $logo = $category->getLogo() ? '<i class="'.$category->getLogo().' me-1"></i>' : '<i class="bi bi-plus-square me-1"></i>';

            if ('videos' === $category->getCode()) {
                $path = $this->router->generate('ytlinks.add');
            } else {
                $path = $this->router->generate('slinks.add', [
                    'category' => $category,
                ]);
            }
            $return .= '<li><a class="dropdown-item" href="'.$path.'">'.$logo.' '.$category->getTitle().'</a></li>';
        }

        return $return;
    }

    public function getNotPublishedLinksCount(int $userId): int
    {
        return count($this->em
            ->getRepository(Links::class)
            ->findBy([
                'published_by' => $userId,
                'is_publish' => false,
            ])
        );
    }
}
