<?php

namespace App\Twig;

use App\Entity\YoutubeLinks;
use RicardoFiorani\Exception\ServiceNotAvailableException;
use RicardoFiorani\Matcher\VideoServiceMatcher;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class YoutubeExtension extends AbstractExtension
{
    private VideoServiceMatcher $vsm;

    public function __construct()
    {
        $this->vsm = new VideoServiceMatcher();
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('yt_img_small', [$this, 'ytImgSmall']),
            new TwigFilter('yt_img_medium', [$this, 'ytImgMedium']),
            new TwigFilter('yt_img_large', [$this, 'ytImgLarge']),
            new TwigFilter('youtube_player', [$this, 'youtubePlayer']),
        ];
    }

    public function ytImgSmall(YoutubeLinks $yt): string
    {
        if ($yt->getYoutubeId() && $yt->getImgSmall()) {
            return $yt->getImgSmall();
        }

        return $this->getThumbnails($yt, 'small');
    }

    public function ytImgMedium(YoutubeLinks $yt): string
    {
        if ($yt->getYoutubeId() && $yt->getImgMedium()) {
            return $yt->getImgMedium();
        }

        return $this->getThumbnails($yt, 'medium');
    }

    public function ytImgLarge(YoutubeLinks $yt): string
    {
        if ($yt->getYoutubeId() && $yt->getImgLarge()) {
            return $yt->getImgLarge();
        }

        return $this->getThumbnails($yt, 'large');
    }

    private function getThumbnails(YoutubeLinks $yt, string $size = 'medium'): string
    {
        try {
            $video = $this->vsm->parse($yt->getUrl());
        } catch (ServiceNotAvailableException $e) {
            return '';
        }

        try {
            $video->getEmbedUrl();

            switch ($size) {
                case 'small':
                    $img = $video->getSmallThumbnail();
                break;

                case 'medium':
                    $img = $video->getMediumThumbnail();
                break;

                case 'large':
                    $img = $video->getLargestThumbnail();
                break;

                default:
                    return '';
            }

            return $img;
        } catch (\RicardoFiorani\Exception\NotEmbeddableException $e) {
            return '';
        }
    }

    public function youtubePlayer(string $value): string
    {
        try {
            $video = $this->vsm->parse($value);
        } catch (ServiceNotAvailableException $e) {
            return '';
        }

        return $video->getEmbedCode('100%', 600, false, true);
    }
}
