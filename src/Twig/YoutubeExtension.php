<?php

namespace App\Twig;

use App\Entity\Tutos;
use Doctrine\ORM\EntityManagerInterface;
use RicardoFiorani\Matcher\VideoServiceMatcher;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class YoutubeExtension extends AbstractExtension
{
    private $em;
    private $vsm;
    private $helper;

    public function __construct(EntityManagerInterface $em, UploaderHelper $helper)
    {
        $this->em = $em;
        $this->helper = $helper;
        $this->vsm = new VideoServiceMatcher();
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('tutos_thumbnail_small', [$this, 'tutosThumbnailSmall']),
            new TwigFilter('tutos_thumbnail', [$this, 'tutosThumbnail']),
            new TwigFilter('tutos_thumbnail_large', [$this, 'tutosThumbnailLarge']),
            new TwigFilter('youtube_largest_thumbnail', [$this, 'youtubeLargestThumbnail']),
            new TwigFilter('youtube_player', [$this, 'youtubePlayer']),
        ];
    }

    /**
     * @param Tutos $tutos
     * @return string
     */
    public final function tutosThumbnailSmall(Tutos $tutos): string
    {
        if($tutos->getYoutubeId()) {
            if($tutos->getThumbnailsSmall()) {
                return $tutos->getThumbnailsSmall();
            }
        }

        if($tutos->getImage()) {
            return $this->helper->asset($tutos, 'imageFile');
        }

        return $this->getThumbnails($tutos, 'small');
    }

    /**
     * @param Tutos $tutos
     * @return string
     */
    public final function tutosThumbnail(Tutos $tutos): string
    {
        if($tutos->getYoutubeId()) {
            if($tutos->getThumbnailsSmall()) {
                return $tutos->getThumbnailsSmall();
            }
        }

        if(!is_null($tutos->getImage())) {
            return is_null($this->helper->asset($tutos, 'imageFile')) ? '' : $this->helper->asset($tutos, 'imageFile');
        }

        return $this->getThumbnails($tutos, 'medium');
    }

    /**
     * @param Tutos $tutos
     * @return string
     */
    public final function tutosThumbnailLarge(Tutos $tutos): string
    {
        if($tutos->getYoutubeId() && $tutos->getThumbnailsLarge()) {
            return $tutos->getThumbnailsLarge();
        }

        if($tutos->getImage()) {
            return $this->helper->asset($tutos, 'imageFile');
        }

        return $this->getThumbnails($tutos, 'large');
    }

    /**
     * @param Tutos $tutos
     * @param string $size
     * @return string
     */
    private function getThumbnails(Tutos $tutos, string $size = 'medium'): string {
        try {
            $video = $this->vsm->parse($tutos->getUrl());
        } catch (\RicardoFiorani\Exception\ServiceNotAvailableException $e) {
            return '';
        }

        try {
            $video->getEmbedUrl();

            switch($size) {
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

    /**
     * @param string $value
     * @return string
     */
    public final function youtubePlayer(string $value): string
    {
        try {
            $video = $this->vsm->parse($value);
        } catch (\RicardoFiorani\Exception\ServiceNotAvailableException $e) {
            return '';
        }

        return $video->getEmbedCode('100%', 500, true, true);
    }
}
