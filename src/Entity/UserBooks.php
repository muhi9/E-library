<?php

namespace App\Entity;

use App\Repository\UserBooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserBooksRepository::class)]
class UserBooks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userBooks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'userBooks')]
    private Collection $orderedBook;

    #[ORM\Column(length: 255)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    public function __construct()
    {
        $this->orderedBook = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getOrderedBook(): Collection
    {
        return $this->orderedBook;
    }

    public function addOrderedBook(Book $orderedBook): static
    {
        if (!$this->orderedBook->contains($orderedBook)) {
            $this->orderedBook->add($orderedBook);
        }

        return $this;
    }

    public function removeOrderedBook(Book $orderedBook): static
    {
        $this->orderedBook->removeElement($orderedBook);

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }
}
