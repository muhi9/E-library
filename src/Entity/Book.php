<?php
namespace App\Entity;


use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $book = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    // #[ORM\Column(length: 255)]
    // private ?string $author = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $releaseYear = null;

    #[ORM\Column(length: 255)]
    private ?string $cover = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $views = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isFree = null;

    #[ORM\Column(nullable: true)]
    private ?bool $validation = false;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublish = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'book')]
    #[ORM\JoinTable('category_book')]
    private Collection $categories;
    

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\OneToMany(mappedBy: 'cartBook', targetEntity: Cart::class)]
    private Collection $cart;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $publishingHouse = null;

    #[ORM\ManyToMany(targetEntity: UserBooks::class, mappedBy: 'orderedBook')]
    private Collection $userBooks;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    private Collection $avtor;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->cart = new ArrayCollection();
        $this->userBooks = new ArrayCollection();
        $this->avtor = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    // public function getAuthor(): ?string
    // {
    //     return $this->author;
    // }

    // public function setAuthor(string $author): static
    // {
    //     $this->author = $author;

    //     return $this;
    // }

    public function getReleaseYear(): ?string
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(?string $releaseYear): static
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): static
    {
        $this->views = $views;

        return $this;
    }

    public function isIsFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(bool $isFree): static
    {
        $this->isFree = $isFree;

        return $this;
    }

    public function isValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(bool $validation): static
    {
        $this->validation = $validation;

        return $this;
    }

    public function isIsPublish(): ?bool
    {
        return $this->isPublish;
    }

    public function setIsPublish(bool $isPublish): static
    {
        $this->isPublish = $isPublish;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addBook($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeBook($this);
        }

        return $this;
    }

    public function getBook(): ?string
    {
        return $this->book;
    }

    public function setBook(string $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getCategoriList()
    {
        $result = [];
        foreach ($this->getCategories() as $category) {
            $result[] = $category->getName();

        }
        return implode(', ', $result);
    }

  //----------------------------------------------------//
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

    /**
     * @return Collection<int, Cart>
     */
    public function getCart(): Collection
    {
        return $this->cart;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->cart->contains($cart)) {
            $this->cart->add($cart);
            $cart->setCartBook($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->cart->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getCartBook() === $this) {
                $cart->setCartBook(null);
            }
        }

        return $this;
    }

    public function getPublishingHouse(): ?string
    {
        return $this->publishingHouse;
    }

    public function setPublishingHouse(?string $publishingHouse): static
    {
        $this->publishingHouse = $publishingHouse;

        return $this;
    }

    /**
     * @return Collection<int, UserBooks>
     */
    public function getUserBooks(): Collection
    {
        return $this->userBooks;
    }

    public function addUserBook(UserBooks $userBook): static
    {
        if (!$this->userBooks->contains($userBook)) {
            $this->userBooks->add($userBook);
            $userBook->addOrderedBook($this);
        }

        return $this;
    }

    public function removeUserBook(UserBooks $userBook): static
    {
        if ($this->userBooks->removeElement($userBook)) {
            $userBook->removeOrderedBook($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAvtor(): Collection
    {
        return $this->avtor;
    }

    public function addAvtor(Author $avtor): static
    {
        if (!$this->avtor->contains($avtor)) {
            $this->avtor->add($avtor);
        }

        return $this;
    }

    public function removeAvtor(Author $avtor): static
    {
        $this->avtor->removeElement($avtor);

        return $this;
    }

    public function getAuthorList()
    {
        $result = [];
        foreach ($this->getAvtor() as $author) {
            $result[] = $author->getName();
        }
        return implode(', ', $result);
    }

}
