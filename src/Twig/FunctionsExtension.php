<?php

namespace App\Twig;

use App\Entity\Authors;
use App\Entity\Categories;
use App\Entity\Concours;
use App\Entity\Links;
use App\Repository\YoutubeLinksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FunctionsExtension extends AbstractExtension
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UrlGeneratorInterface $router,
        private readonly UrlGeneratorInterface $generator,
        private readonly YoutubeLinksRepository $ytRepository
    ) {
    }

    public function getFunctions(): array
    {
        $default = ['is_safe' => ['html']];

        return [
            new TwigFunction('isConcoursOpen', [$this, 'isConcoursOpen'], $default),
            new TwigFunction('categoryIcon', [$this, 'getCategoryIcon'], $default),
            new TwigFunction('countForCategory', [$this, 'getCountForCategory'], $default),
            new TwigFunction('countForAuthors', [$this, 'getCountForAuthors'], $default),
            new TwigFunction('categoryPath', [$this, 'getCategoryPath'], $default),
            new TwigFunction('menuAuthors', [$this, 'getMenuAuthors'], $default),
            new TwigFunction('menuCategories', [$this, 'getMenuCategories'], $default),
            new TwigFunction('footerCategories', [$this, 'getFooterCategories'], $default),
            new TwigFunction('headerMenuAddByCategories', [$this, 'getHeaderMenuAddByCategories'], $default),
            new TwigFunction('notPublishedLinksCount', [$this, 'getNotPublishedLinksCount'], $default),
            new TwigFunction('dateToFr', [$this, 'dateToFr'], $default),
            new TwigFunction('mltSkills', [$this, 'mltSkills'], $default),
            new TwigFunction('categoryArray', [$this, 'categoryArray'], $default),
            new TwigFunction('twitter', [$this, 'twitter'], $default),
        ];
    }

    public function isConcoursOpen(): bool
    {
        $concours = $this->em->getRepository(Concours::class)->findOneBy(['isOpen' => true]);

        return (bool) $concours;
    }

    public function getCountForCategory(string $code): string
    {
        $c = $this->em
            ->getRepository(Links::class)
            ->findBy([
                'category' => $this->em->getRepository(Categories::class)->findOneBy(['code' => $code]),
            ]);

        return count($c);
    }

    public function getCountForAuthors(): string
    {
        $c = $this->em
            ->getRepository(Authors::class)
            ->findAll();

        return count($c);
    }

    public function getCategoryPath(string $code): string
    {
        $c = $this->em->getRepository(Categories::class)->findOneBy(['code' => $code]);

        return $this->router->generate('search', [
            'categories[]' => $c->getId(),
        ]);
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
            } elseif ($author[0]->getAttachment()) {
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

            $return .= '<li class="nav-item mb-2"><a href="'.$this->router->generate('events.index').'" class="nav-link py-0 ps-3 pe-0">
                    <i class="bi bi-calendar3 me-1"></i>
                    <span>Événements</span>
                </a></li>';

            $return .= '<li class="nav-item mb-2"><a href="'.$this->router->generate('hebdoo.semaine').'" class="nav-link py-0 ps-3 pe-0">
                    <i class="bi bi-h-square me-1"></i>
                    <span>Hebdoo de la semaine</span>
                </a></li>';

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

    public function dateToFr(\DateTime $date, string $format): string
    {
        $english_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $french_days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        $english_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $french_months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, $date->format($format)));
    }

    public function mltSkills(string $skills): string
    {
        $skill = explode(',', $skills);

        $return = '';
        foreach ($skill as $s) {
            $return .= '<span class="badge text-green-900 bg-green-100 font-semibold"># '.$s.'</span>';
        }

        return $return;
    }

    public function categoryArray(string $category)
    {
        return match ($category) {
            'videos' => $this->ytRepository->findWeeklyPublished(),
            'articles' => $this->em->getRepository(Links::class)->findWeeklyPublished('articles'),
            'podcasts' => $this->em->getRepository(Links::class)->findWeeklyPublished('podcasts'),
            'formations' => $this->em->getRepository(Links::class)->findWeeklyPublished('formations'),
            'ressources' => $this->em->getRepository(Links::class)->findWeeklyPublished('ressources'),
            default => [],
        };
    }

    public function twitter(string $link)
    {
        return str_replace('https://twitter.com/', '@', $link);
    }
}
