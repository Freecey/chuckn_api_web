<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 */
class Report
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Jokes::class, inversedBy="reports")
     */
    private $joke;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_At;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reason;

    public function __construct()
    {
        $this->created_At = new \DateTime();
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

    public function getCreated_At(): ?\DateTimeInterface
    {
        return $this->created_At;
    }

    public function setCreated_At(\DateTimeInterface $created_At): self
    {
        $this->created_At = $created_At;

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

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }
}
