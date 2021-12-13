<?php

namespace App\DataFixtures;

use App\Entity\Authors;
use App\Entity\Categories;
use App\Entity\Languages;
use App\Entity\SimpleLinks;
use App\Entity\Tags;
use App\Entity\Users;
use App\Entity\YoutubeLinks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->setCategories($manager);
        $this->setTags($manager);
        $this->setLanguages($manager);
//        $this->setAuthors($manager);

        $manager->flush();

//        $this->setAdministrator($manager);

//        $this->loadForTest($manager);
    }

    private function setCategories(ObjectManager $manager)
    {
        $categories = [
            [
                'title' => 'Vidéos',
                'logo' => 'bi bi-youtube',
                'link_entity' => 'youtube',
                'code' => 'videos',
                'is_actif' => true,
            ],
            [
                'title' => 'Articles',
                'logo' => 'bi bi-newspaper',
                'link_entity' => 'simple',
                'code' => 'articles',
                'is_actif' => true,
            ],
            [
                'title' => 'Podcasts',
                'logo' => 'bi bi-broadcast-pin',
                'link_entity' => 'simple',
                'code' => 'podcasts',
                'is_actif' => true,
            ],
            [
                'title' => 'Formations',
                'logo' => 'bi bi-award',
                'link_entity' => 'simple',
                'code' => 'formations',
                'is_actif' => false,
            ],
            [
                'title' => 'Ressources',
                'logo' => 'bi bi-tools',
                'link_entity' => 'simple',
                'code' => 'ressources',
                'is_actif' => true,
            ],
        ];

        foreach ($categories as $category) {
            $entity = (new Categories())
                ->setTitle($category['title'])
                ->setLogo($category['logo'])
                ->setLinkEntity($category['link_entity'])
                ->setCode($category['code'])
                ->setIsActif($category['is_actif'])
            ;
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function setTags(ObjectManager $manager)
    {
        $tags = [
            [
                'id' => 1,
                'title' => 'React',
                'code' => 'react',
                'color' => '61DAFB',
            ],
            [
                'id' => 2,
                'title' => 'PHP',
                'code' => 'php',
                'color' => '777BB4',
            ],
            [
                'id' => 3,
                'title' => 'Symfony',
                'code' => 'symfony',
                'color' => 'black',
            ],
            [
                'id' => 4,
                'title' => 'Laravel',
                'code' => 'laravel',
                'color' => 'FF2D20',
            ],
            [
                'id' => 5,
                'title' => 'Wordpress',
                'code' => 'wordpress',
                'color' => 'black',
            ],
            [
                'id' => 6,
                'title' => 'NodeJs',
                'code' => 'node.js',
                'color' => '43853D',
            ],
            [
                'id' => 7,
                'title' => 'Ruby',
                'code' => 'ruby',
                'color' => 'black',
            ],
            [
                'id' => 8,
                'title' => 'Ruby on Rails',
                'code' => 'ruby on rails',
                'color' => 'black',
            ],
            [
                'id' => 9,
                'title' => 'HTML',
                'code' => 'html5',
                'color' => 'E34F26',
            ],
            [
                'id' => 10,
                'title' => 'CSS',
                'code' => 'css3',
                'color' => '1572B6',
            ],
            [
                'id' => 11,
                'title' => 'Javascript',
                'code' => 'javascript',
                'color' => 'F7DF1E',
            ],
            [
                'id' => 12,
                'title' => 'Vue.js',
                'code' => 'vue.js',
                'color' => '4FC08D',
            ],
            [
                'id' => 13,
                'title' => 'Vagrant',
                'code' => 'vagrant',
                'color' => 'black',
            ],
            [
                'id' => 14,
                'title' => 'Docker',
                'code' => 'docker',
                'color' => '2496ed',
            ],
            [
                'id' => 15,
                'title' => 'Linux',
                'code' => 'linux',
                'color' => 'black',
            ],
            [
                'id' => 16,
                'title' => 'Git',
                'code' => 'git',
                'color' => 'F05032',
            ],
            [
                'id' => 17,
                'title' => 'Java',
                'code' => 'java',
                'color' => 'violet',
            ],
            [
                'id' => 18,
                'title' => 'Bulma.io',
                'code' => 'bulma.io',
                'color' => 'black',
            ],
            [
                'id' => 19,
                'title' => 'Autre',
                'code' => 'autre',
                'color' => 'black',
            ],
            [
                'id' => 20,
                'title' => 'Tailwindcss',
                'code' => 'tailwind-css',
                'color' => '38B2AC',
            ],
            [
                'id' => 21,
                'title' => 'Qualité web',
                'code' => 'qualité web',
                'color' => 'black',
            ],
            [
                'id' => 22,
                'title' => 'SEO',
                'code' => 'seo',
                'color' => 'ffffff',
            ],
            [
                'id' => 23,
                'title' => 'Python',
                'code' => 'python',
                'color' => '3776AB',
            ],
            [
                'id' => 24,
                'title' => 'Flask',
                'code' => 'flask',
                'color' => 'black',
            ],
            [
                'id' => 25,
                'title' => 'Angular',
                'code' => 'angular',
                'color' => 'DD0031',
            ],
            [
                'id' => 26,
                'title' => 'Firebase',
                'code' => 'firebase',
                'color' => 'black',
            ],
            [
                'id' => 27,
                'title' => 'GraphQl',
                'code' => 'graphql',
                'color' => 'black',
            ],
            [
                'id' => 28,
                'title' => 'NPM',
                'code' => 'npm',
                'color' => 'black',
            ],
            [
                'id' => 29,
                'title' => 'Gitlab',
                'code' => 'gitlab',
                'color' => 'black',
            ],
            [
                'id' => 30,
                'title' => 'RabbitMQ',
                'code' => 'rabbitmq',
                'color' => 'black',
            ],
            [
                'id' => 31,
                'title' => 'Apache',
                'code' => 'apache',
                'color' => 'black',
            ],
            [
                'id' => 32,
                'title' => 'Nginx',
                'code' => 'nginx',
                'color' => 'black',
            ],
            [
                'id' => 33,
                'title' => 'Tests',
                'code' => 'tests',
                'color' => 'black',
            ],
            [
                'id' => 34,
                'title' => 'Kubernetes',
                'code' => 'kubernetes',
                'color' => 'black',
            ],
            [
                'id' => 35,
                'title' => 'Windows',
                'code' => 'windows',
                'color' => 'black',
            ],
            [
                'id' => 36,
                'title' => 'WSL',
                'code' => 'wsl',
                'color' => 'black',
            ],
            [
                'id' => 37,
                'title' => 'IDE',
                'code' => 'ide',
                'color' => 'black',
            ],
            [
                'id' => 38,
                'title' => 'CI/CD',
                'code' => 'ci/cd',
                'color' => 'black',
            ],
            [
                'id' => 39,
                'title' => 'Github',
                'code' => 'github',
                'color' => 'black',
            ],
            [
                'id' => 40,
                'title' => 'PhpStorm',
                'code' => 'phpstorm',
                'color' => 'black',
            ],
            [
                'id' => 41,
                'title' => 'VsCode',
                'code' => 'vscode',
                'color' => 'black',
            ],
            [
                'id' => 42,
                'title' => 'Gulp',
                'code' => 'gulp',
                'color' => 'black',
            ],
            [
                'id' => 43,
                'title' => 'Redis',
                'code' => 'redis',
                'color' => 'black',
            ],
            [
                'id' => 44,
                'title' => 'Composer',
                'code' => 'composer',
                'color' => 'black',
            ],
            [
                'id' => 45,
                'title' => 'Bootstrap',
                'code' => 'bootstrap',
                'color' => '563D7C',
            ],
            [
                'id' => 46,
                'title' => 'Sass',
                'code' => 'sass',
                'color' => 'CC6699',
            ],
            [
                'id' => 47,
                'title' => 'Figma',
                'code' => 'figma',
                'color' => 'black',
            ],
            [
                'id' => 48,
                'title' => 'Ansible',
                'code' => 'ansible',
                'color' => 'black',
            ],
            [
                'id' => 49,
                'title' => 'TypeScript',
                'code' => 'typescript',
                'color' => 'black',
            ],
            [
                'id' => 50,
                'title' => 'NextJs',
                'code' => 'nextjs',
                'color' => 'black',
            ],
            [
                'id' => 51,
                'title' => 'Svelte',
                'code' => 'svelte',
                'color' => 'black',
            ],
            [
                'id' => 52,
                'title' => 'Django',
                'code' => 'django',
                'color' => 'black',
            ],
            [
                'id' => 53,
                'title' => 'MySql',
                'code' => 'mysql',
                'color' => 'black',
            ],
            [
                'id' => 54,
                'title' => 'Alpine.js',
                'code' => 'alpine.js',
                'color' => 'black',
            ],
        ];

        foreach ($tags as $tag) {
            $entity = (new Tags())
                ->setTitle($tag['title'])
                ->setCode($tag['code'])
                ->setColor($tag['color'])
            ;
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function setLanguages(ObjectManager $manager)
    {
        $languages = [
            [
                'name' => 'Francais',
                'shortname' => 'FR',
                'logo' => '',
            ],
            [
                'name' => 'Anglais',
                'shortname' => 'EN',
                'logo' => '',
            ],
            [
                'name' => 'Espagnol',
                'shortname' => 'ES',
                'logo' => '',
            ],
        ];

        foreach ($languages as $language) {
            $entity = (new Languages())
                ->setName($language['name'])
                ->setShortname($language['shortname'])
                ->setLogo($language['logo'])
            ;
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function setAuthors(ObjectManager $manager)
    {
        $authors = [
            [
                'title' => 'YoanDev',
                'description' => 'Yoan le développeur qui fait du Symfony',
                'logo' => 'https://yt3.ggpht.com/ytc/AAUvwnhPs5SD0uJxihTyciO4SScwDAWebP2bnnNOIW8M=s88-c-k-c0x00ffffff-no-rj',
                'yt_logo' => 'https://yt3.ggpht.com/ytc/AAUvwnhPs5SD0uJxihTyciO4SScwDAWebP2bnnNOIW8M=s88-c-k-c0x00ffffff-no-rj',
                'site_url' => 'https://www.yoandev.com',
                'twitter' => 'yOyO38',
                'twitch' => '',
                'github' => 'https://www.github.com/yoandev',
                'youtube' => 'https://www.youtube.com/UCRlsJWh1XwmNGxZPFgJ0Zlw',
            ],
            [
                'title' => 'Lior Chamla',
                'description' => 'Un développeur qui fait du Symfony, du Angular et qui est fort',
                'logo' => 'https://yt3.ggpht.com/ytc/AAUvwnibA9asWibjOw39QRbW4zX3CTd4cx2hQghFb8FlVA=s88-c-k-c0x00ffffff-no-rj',
                'yt_logo' => 'https://yt3.ggpht.com/ytc/AAUvwnibA9asWibjOw39QRbW4zX3CTd4cx2hQghFb8FlVA=s88-c-k-c0x00ffffff-no-rj',
                'site_url' => 'https://learn.web-develop.me/',
                'twitter' => 'LiiorC',
                'twitch' => '',
                'github' => 'https://github.com/liorchamla/',
                'youtube' => 'https://www.youtube.com/UCS71mal_TkTW_PpZR9YLpIA',
            ],
        ];

        foreach ($authors as $author) {
            $entity = (new Authors())
                ->setTitle($author['title'])
                ->setDescription($author['description'])
                ->setSiteUrl($author['site_url'])
                ->setTwitch($author['twitch'])
                ->setTwitter($author['twitter'])
                ->setGithub($author['github'])
                ->setYoutube($author['youtube'])
                ->setYtLogo($author['yt_logo'])
                ->setLogo($author['logo'])
            ;
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function setAdministrator(ObjectManager $manager)
    {
        $user = (new Users())
            ->setEmail('admin@tutomarks.fr')
            ->setUsername('Admin Tutomarks')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ;
        $user->setPassword($this->encoder->hashPassword($user, 'admin'));
        $manager->persist($user);
        $manager->flush();
    }

    private function loadForTest(ObjectManager $manager)
    {
        $categorie1 = $manager->getRepository(Categories::class)
            ->findOneBy(['code' => 'videos']);
        $categorie2 = $manager->getRepository(Categories::class)
            ->findOneBy(['code' => 'articles']);
        $langue = $manager->getRepository(Languages::class)
            ->findOneBy(['shortname' => 'FR']);
        $author1 = $manager->getRepository(Authors::class)
            ->findOneBy(['title' => 'YoanDev']);
        $author2 = $manager->getRepository(Authors::class)
            ->findOneBy(['twitter' => 'LiiorC']);

        $user = $manager->find(Users::class, 1);

        $ytlinks = (new YoutubeLinks())
            ->setTitle('SYMFONY : Du DEV à la PROD (et DÉPLOIEMENT CONTINU avec GITHUB ACTION) (feat @Cloudways)')
            ->setAuthor($author1)
            ->setLanguage($langue)
            ->setCategory($categorie1)
            ->setUrl('https://www.youtube.com/watch?v=Id5y2aRUZok')
            ->setYoutubeId('Id5y2aRUZok')
            ->setImgSmall('https://i.ytimg.com/vi/Id5y2aRUZok/mqdefault.jpg')
            ->setImgLarge('https://i.ytimg.com/vi/Id5y2aRUZok/hqdefault.jpg')
            ->setPublishedBy($user)
            ->setIsPublish(true)
        ;
        $manager->persist($ytlinks);

        $links = (new SimpleLinks())
            ->setTitle('LE DESIGN PATTERN OBSERVER (JAVASCRIPT) - BEST OF TWITCH')
            ->setAuthor($author2)
            ->setLanguage($langue)
            ->setCategory($categorie2)
            ->setUrl('https://www.youtube.com/watch?v=Id5y2aRUZok')
            ->setPublishedBy($user)
        ;

        $manager->persist($links);
        $manager->flush();
    }
}
