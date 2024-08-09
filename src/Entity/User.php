<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fakNo = null;

    #[ORM\Column(nullable: true)]
    private ?float $wallet = null;

    #[ORM\OneToMany(mappedBy: 'cartUser', targetEntity: Cart::class)]
    private Collection $cart;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserBooks::class)]
    private Collection $userBooks;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Notifications::class)]
    private Collection $createdBy;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notifications::class)]
    private Collection $notifications;



    

    public function __construct()
    {
        $this->cart = new ArrayCollection();
        $this->userBooks = new ArrayCollection();
        $this->createdBy = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $LastName): static
    {
        $this->lastName = $LastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTypeUser(): ?string
    {
        return $this->typeUser;
    }

    public function setTypeUser(string $typeUser): static
    {
        $this->typeUser = $typeUser;

        return $this;
    }

    public function getFakNo(): ?string
    {
        return $this->fakNo;
    }

    public function setFakNo(?string $fakNo): static
    {
        $this->fakNo = $fakNo;

        return $this;
    }

    public function getWallet(): ?float
    {
        return $this->wallet;
    }

    public function setWallet(?float $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
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
            $cart->setCartUser($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->cart->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getCartUser() === $this) {
                $cart->setCartUser(null);
            }
        }

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
            $userBook->setUser($this);
        }

        return $this;
    }

    public function removeUserBook(UserBooks $userBook): static
    {
        if ($this->userBooks->removeElement($userBook)) {
            // set the owning side to null (unless already changed)
            if ($userBook->getUser() === $this) {
                $userBook->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notifications>
     */
    public function getCreatedBy(): Collection
    {
        return $this->createdBy;
    }

    public function addCreatedBy(Notifications $createdBy): static
    {
        if (!$this->createdBy->contains($createdBy)) {
            $this->createdBy->add($createdBy);
            $createdBy->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedBy(Notifications $createdBy): static
    {
        if ($this->createdBy->removeElement($createdBy)) {
            // set the owning side to null (unless already changed)
            if ($createdBy->getCreator() === $this) {
                $createdBy->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notifications>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

}
