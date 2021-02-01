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
     * @var Channels|null
     */
    private $channel;

    /**
     * @var float|null
     */
    private $evaluation;

    /**
     * @var Langues|null
     */
    private $langue;

    /**
     * @var Prices|null
     */
    private $price;

    /**
     * @var Levels|null
     */
    private $minlevel;

    /**
     * @var boolean|null
     */
    private $pined;

    /**
     * @var boolean|null
     */
    private $shown;

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
     * @return Channels|null
     */
    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * @param Channels|null $channel
     * @return TutoSearch
     */
    public function setChannel(?Channels $channel): TutoSearch
    {
        $this->channel = $channel->getId();
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

    /**
     * @return Prices|null
     */
    public function getPrice(): ?Prices
    {
        return $this->price;
    }

    /**
     * @param Prices|null $price
     * @return TutoSearch
     */
    public function setPrice(?Prices $price): TutoSearch
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return Levels|null
     */
    public function getMinlevel(): ?Levels
    {
        return $this->minlevel;
    }

    /**
     * @param Levels|null $minlevel
     * @return TutoSearch
     */
    public function setMinlevel(?Levels $minlevel): TutoSearch
    {
        $this->minlevel = $minlevel;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPined(): ?bool
    {
        return $this->pined;
    }

    /**
     * @param bool|null $pined
     * @return TutoSearch
     */
    public function setPined(?bool $pined): TutoSearch
    {
        $this->pined = $pined;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getShown(): ?bool
    {
        return $this->shown;
    }

    /**
     * @param bool|null $shown
     * @return TutoSearch
     */
    public function setShown(?bool $shown): TutoSearch
    {
        $this->shown = $shown;
        return $this;
    }
}