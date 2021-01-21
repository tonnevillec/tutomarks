<?php
namespace App\Twig;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\Tutos;
use App\Entity\User;
use App\Entity\UserTutosInformations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twitter\Text\Autolink;

class FunctionsExtension extends AbstractExtension
{
    private $em;
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tweets', [$this, 'getTweets'], ['is_safe' => ['html']]),
            new TwigFunction('autolink', [$this, 'getAutolink'], ['is_safe' => ['html']]),
            new TwigFunction('userTutoInformations', [$this, 'getUserTutoInformations'], ['is_safe' => ['html']]),
            new TwigFunction('countPined', [$this, 'getCountPined'], ['is_safe' => ['html']]),
            new TwigFunction('pinForTuto', [$this, 'getPinForTuto'], ['is_safe' => ['html']]),
            new TwigFunction('shownForTuto', [$this, 'getShownForTuto'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param $key
     * @param $secret
     * @param $bearer
     * @return array|object
     */
    public function getTweets($key, $secret, $bearer)
    {
//        return $key . ' / ' . $secret . ' / ' . $bearer;
        $connection = new TwitterOAuth($key, $secret, null, $bearer);
        $tweets = $connection->get("statuses/user_timeline", ['screen_name' => 'tutomarks', 'count' => 3]);

        return $tweets;
    }

    /**
     * @param $tweet
     * @return string
     */
    public function getAutolink($tweet)
    {
        return Autolink::create()->autoLink($tweet);
    }

    /**
     * @param User $user
     * @param Tutos $tutos
     * @return object|null
     */
    public function getUserTutoInformations(User $user, Tutos $tutos): ?object
    {
        return $this->em
            ->getRepository(UserTutosInformations::class)
            ->findOneBy([
                'user'  => $user->getId(),
                'tutos' => $tutos->getId()
            ])
        ;
    }

    /**
     * @param Tutos $tutos
     * @return int
     */
    public function getCountPined(Tutos $tutos): int
    {
        $infos = $this->em
            ->getRepository(UserTutosInformations::class)
            ->findBy([
                'tutos' => $tutos->getId()
            ]);
        return count($infos);
    }

    /**
     * @param Tutos $tutos
     * @param User|null $user
     * @return string
     */
    public function getPinForTuto(Tutos $tutos, ?User $user = null): string
    {
        $pin = '<i class="fas fa-thumbtack"></i>';

        if(is_null($user)){
            return $pin;
        }

        $infos = $this->em
            ->getRepository(UserTutosInformations::class)
            ->findOneBy([
                'tutos' => $tutos->getId(),
                'user'  => $user->getId()
            ]);

        if(!$infos){
            return $pin;
        }

        return $infos->getPined() ? '<span class="text-success" data-toggle="tooltip" data-placement="top" title="'.ucfirst($this->translator->trans('btn.userpin')).'">'.$pin.'</span>' : $pin;
    }

    /**
     * @param Tutos $tutos
     * @param User|null $user
     * @return string
     */
    public function getShownForTuto(Tutos $tutos, ?User $user = null): string
    {
        $show = '<span class="mr-2 text-success" data-toggle="tooltip" data-placement="top" title="'.ucfirst($this->translator->trans('btn.usershow')).'"><i class="fas fa-eye"></i></span>';
        $notshow = '<span class="mr-2" data-toggle="tooltip" data-placement="top" title="'.ucfirst($this->translator->trans('btn.usernotshow')).'"><i class="fas fa-eye-slash"></i></span>';

        if(is_null($user)){
            return $notshow;
        }

        $infos = $this->em
            ->getRepository(UserTutosInformations::class)
            ->findOneBy([
                'tutos' => $tutos->getId(),
                'user'  => $user->getId()
            ]);

        if(!$infos){
            return $notshow;
        }

        return $infos->getShown() ? $show : $notshow;
    }
}