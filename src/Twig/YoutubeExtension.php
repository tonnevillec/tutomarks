<?php

namespace App\Twig;

use RicardoFiorani\Matcher\VideoServiceMatcher;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class YoutubeExtension extends AbstractExtension
{
    private $vsm;

    public function __construct()
    {
        $this->vsm = new VideoServiceMatcher();
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('youtube_thumbnail', [$this, 'youtubeThumbnail']),
            new TwigFilter('youtube_player', [$this, 'youtubePlayer']),
        ];
    }

    /**
     * @param $value
     * @return string
     */
    public function youtubeThumbnail($value): string
    {
        try {
            $video = $this->vsm->parse($value);
        } catch (\RicardoFiorani\Exception\ServiceNotAvailableException $e) {
            return '';
        }

        try {
            $video->getEmbedUrl();
            return $video->getMediumThumbnail();
//            return $video->getSmallThumbnail();
//            return $video->getLargestThumbnail();
        } catch (\RicardoFiorani\Exception\NotEmbeddableException $e) {
            return '';
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function youtubePlayer($value): string
    {
        try {
            $video = $this->vsm->parse($value);
        } catch (\RicardoFiorani\Exception\ServiceNotAvailableException $e) {
            return '';
        }

        return $video->getEmbedCode('100%', 500, true, true);
    }
}
