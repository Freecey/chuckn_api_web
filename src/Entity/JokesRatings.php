<?php

namespace App\Entity;

use App\Repository\JokesRatingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JokesRatingsRepository::class)
 */
class JokesRatings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Jokes::class, inversedBy="jokesRatings")
     */
    private $joke;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->joke = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Jokes[]
     */
    public function getJoke(): Collection
    {
        return $this->joke;
    }

    public function addJoke(Jokes $joke): self
    {
        if (!$this->joke->contains($joke)) {
            $this->joke[] = $joke;
        }

        return $this;
    }

    public function removeJoke(Jokes $joke): self
    {
        $this->joke->removeElement($joke);

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }
}
