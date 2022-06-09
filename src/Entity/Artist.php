<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArtistRepository::class)
 */
class Artist
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private int $id = 0;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $name = "anonymous";

  /**
   * @ORM\OneToOne(targetEntity=User::class, inversedBy="artist", cascade={"persist", "remove"})
   * @ORM\JoinColumn(nullable=false)
   */
  private User $user;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getUser(): ?User
  {
    return $this->user;
  }

  public function setUser(User $user): self
  {
    $this->user = $user;

    return $this;
  }
}
