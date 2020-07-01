<?php
namespace App\Twig;

use Abraham\TwitterOAuth\TwitterOAuth;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twitter\Text\Autolink;

class FunctionsExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tweets', [$this, 'getTweets'], ['is_safe' => ['html']]),
            new TwigFunction('autolink', [$this, 'getAutolink'], ['is_safe' => ['html']]),
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
}