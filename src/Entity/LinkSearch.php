<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class LinkSearch
{
    /**
     * @var string|null
     */
    private $search;

    /**
     * @var int|null
     */
    private $page;

    /**
     * @var ArrayCollection|null
     */
    private $categories;

    /**
     * @var ArrayCollection|null
     */
    private $tags;

    /**
     * @var ArrayCollection|null
     */
    private $languages;

    /**
     * @var ArrayCollection|null
     */
    private $authors;

    public function __construct()
    {
        $this->page = 1;
        $this->tags = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string|null $search
     * @return LinkSearch
     */
    public function setSearch(?string $search): LinkSearch
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     * @return LinkSearch
     */
    public function setPage(?int $page): LinkSearch
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getCategories(): ?ArrayCollection
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection|null $categories
     * @return LinkSearch
     */
    public function setCategories(?ArrayCollection $categories): LinkSearch
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getTags(): ?ArrayCollection
    {
        return $this->tags;
    }

    /**
     * @param ArrayCollection|null $tags
     * @return LinkSearch
     */
    public function setTags(?ArrayCollection $tags): LinkSearch
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getLanguages(): ?ArrayCollection
    {
        return $this->languages;
    }

    /**
     * @param ArrayCollection|null $languages
     * @return LinkSearch
     */
    public function setLanguages(?ArrayCollection $languages): LinkSearch
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getAuthors(): ?ArrayCollection
    {
        return $this->authors;
    }

    /**
     * @param ArrayCollection|null $authors
     * @return LinkSearch
     */
    public function setAuthors(?ArrayCollection $authors): LinkSearch
    {
        $this->authors = $authors;
        return $this;
    }


}