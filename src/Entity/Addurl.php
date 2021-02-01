<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Addurl
{
    /**
     * @var string
     * @Assert\Url(message = "l'url '{{ value }}' n'est pas valide")
     */
    private $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
