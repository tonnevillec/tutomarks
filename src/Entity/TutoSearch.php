<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class TutoSearch
{
    /**
     * @var string|null
     */
    private $search;

    /**
     * @var Categories|null
     */
    private $category;

    /**
     * @var ArrayCollection|null
     */
    private $tags;

    /**
     * @var string|null
     */
    private $creator;

    /**
     * @var float|null
     */
    private $evaluation;

    /**
     * @var Langues|null
     */
    private $langue;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return TutoSearch
     */
    public function setSearch(?string $search): TutoSearch
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return Categories|null
     */
    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    /**
     * @param Categories|null $category
     * @return TutoSearch
     */
    public function setCategory(?Categories $category): TutoSearch
    {
        $this->category = $category;
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
     * @return TutoSearch
     */
    public function setTags(?ArrayCollection $tags): TutoSearch
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreator(): ?string
    {
        return $this->creator;
    }

    /**
     * @param string|null $creator
     * @return TutoSearch
     */
    public function setCreator(?string $creator): TutoSearch
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getEvaluation(): ?float
    {
        return $this->evaluation;
    }

    /**
     * @param float|null $evaluation
     * @return TutoSearch
     */
    public function setEvaluation(?float $evaluation): TutoSearch
    {
        $this->evaluation = $evaluation;
        return $this;
    }

    /**
     * @return Langues|null
     */
    public function getLangue(): ?Langues
    {
        return $this->langue;
    }

    /**
     * @param Langues|null $langue
     * @return TutoSearch
     */
    public function setLangue(?Langues $langue): TutoSearch
    {
        $this->langue = $langue;
        return $this;
    }

}