<?php

namespace App\Entity;

use App\Repository\JokesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JokesRepository::class)
 */
class Jokes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $joke;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=JokesRatings::class, mappedBy="joke")
     */
    private $jokesRatings;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->jokesRatings = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->joke;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoke(): ?string
    {
        return $this->joke;
    }

    public function setJoke(string $joke): self
    {
        $this->joke = $joke;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|JokesRatings[]
     */
    public function getJokesRatings(): Collection
    {
        return $this->jokesRatings;
    }

    public function addJokesRating(JokesRatings $jokesRating): self
    {
        if (!$this->jokesRatings->contains($jokesRating)) {
            $this->jokesRatings[] = $jokesRating;
            $jokesRating->addJoke($this);
        }

        return $this;
    }

    public function removeJokesRating(JokesRatings $jokesRating): self
    {
        if ($this->jokesRatings->removeElement($jokesRating)) {
            $jokesRating->removeJoke($this);
        }

        return $this;
    }

    public function getValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): self
    {
        $this->validated = $validated;

        return $this;
    }
}
