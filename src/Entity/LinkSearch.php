<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class LinkSearch
{
    private ?string $search = null;
    private ?int $page = 1;
    private ?ArrayCollection $categories;
    private ?ArrayCollection $tags;
    private ?ArrayCollection $languages;
    private ?ArrayCollection $authors;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): LinkSearch
    {
        $this->search = $search;

        return $this;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): LinkSearch
    {
        $this->page = $page;

        return $this;
    }

    public function getCategories(): ?ArrayCollection
    {
        return $this->categories;
    }

    public function setCategories(?ArrayCollection $categories): LinkSearch
    {
        $this->categories = $categories;

        return $this;
    }

    public function getTags(): ?ArrayCollection
    {
        return $this->tags;
    }

    public function setTags(?ArrayCollection $tags): LinkSearch
    {
        $this->tags = $tags;

        return $this;
    }

    public function getLanguages(): ?ArrayCollection
    {
        return $this->languages;
    }

    public function setLanguages(?ArrayCollection $languages): LinkSearch
    {
        $this->languages = $languages;

        return $this;
    }

    public function getAuthors(): ?ArrayCollection
    {
        return $this->authors;
    }

    public function setAuthors(?ArrayCollection $authors): LinkSearch
    {
        $this->authors = $authors;

        return $this;
    }
}
