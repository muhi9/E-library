<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(inversedBy: 'cart')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $cartUser = null;

    #[ORM\ManyToOne(inversedBy: 'cart')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $cartBook = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeletedAt() {
        return $this->deletedAt;
    }
    /**
     * @param \DateTime $deletedAt
     * @return void
     */
    public function setDeletedAt(\DateTime $deletedAt) {
        $this->deletedAt = $deletedAt;
    }

    public function getCartUser(): ?User
    {
        return $this->cartUser;
    }

    public function setCartUser(?User $cartUser): static
    {
        $this->cartUser = $cartUser;

        return $this;
    }

    public function getCartBook(): ?Book
    {
        return $this->cartBook;
    }

    public function setCartBook(?Book $cartBook): static
    {
        $this->cartBook = $cartBook;

        return $this;
    }    
}
