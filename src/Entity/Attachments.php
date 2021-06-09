<?php

namespace App\Entity;

use App\Repository\AttachmentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AttachmentsRepository::class)
 * @Vich\Uploadable()
 */
class Attachments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="attachments", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Tutos::class, mappedBy="attachment")
     */
    private $tutos;

    public function __construct()
    {
        $this->updated_at = new \Datetime();
        $this->tutos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     * @return Attachments
     */
    public function setImageFile(File $imageFile): Attachments
    {
        $this->imageFile = $imageFile;

        if($imageFile) {
            $this->updated_at = new \Datetime();
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->image;
    }

    /**
     * @return Collection|Tutos[]
     */
    public function getTutos(): Collection
    {
        return $this->tutos;
    }

    public function addTuto(Tutos $tuto): self
    {
        if (!$this->tutos->contains($tuto)) {
            $this->tutos[] = $tuto;
            $tuto->setAttachment($this);
        }

        return $this;
    }

    public function removeTuto(Tutos $tuto): self
    {
        // set the owning side to null (unless already changed)
        if ($this->tutos->removeElement($tuto) && $tuto->getAttachment() === $this) {
            $tuto->setAttachment(null);
        }

        return $this;
    }
}
