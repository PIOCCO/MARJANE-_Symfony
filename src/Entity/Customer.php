<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Name is required.')]
    #[Assert\Length(max: 255)]
    private ?string $Name = null;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    #[Assert\Regex(
        pattern: '/^\+?[0-9]{1,15}$/',
        message: 'Please enter a valid phone number.'
    )]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Email(message: 'Please enter a valid email address.')]
    private ?string $email = null;


    // ------------One-to-Many relationship with Account and order ---------------
    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Account::class, cascade: ['persist', 'remove'])]
    private Collection $accounts;
    private Collection $orders;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    // ----------------- ID ---------------------
    public function getId(): ?int
    {
        return $this->id;
    }

    // ----------------- Name ---------------------
    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    // ----------------- Phone ---------------------
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        if ($phone && !preg_match('/^\+?[0-9]{1,20}$/', $phone)) {
            throw new \InvalidArgumentException('Invalid phone number format.');
        }

        $this->phone = $phone;
        return $this;
    }

    // ----------------- Email ---------------------
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format.');
        }

        $this->email = $email;
        return $this;
    }

    // ----------------- Accounts Relationship ---------------------
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setCustomer($this); // Set the customer in the Account entity
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            // Set the owning side to null (unless already changed)
            if ($account->getCustomer() === $this) {
                $account->setCustomer(null);
            }
        }

        return $this;
    }

    // ----------------- Account Names Access ---------------------
    public function getAccountNames(): array
    {
        $accountNames = [];
        foreach ($this->accounts as $account) {
            $accountNames[] = $account->getAccountName();  // Accessing account name from Account entity
        }

        return $accountNames;
    }

     // Getter and Setter for Orders
     public function getOrders(): Collection
     {
         return $this->orders;
     }
 
     public function addOrder(Order $order): self
     {
         if (!$this->orders->contains($order)) {
             $this->orders[] = $order;
             $order->setCustomer($this);
         }
 
         return $this;
     }
 
     public function removeOrder(Order $order): self
     {
         if ($this->orders->removeElement($order)) {
             if ($order->getCustomer() === $this) {
                 $order->setCustomer(null);
             }
         }
 
         return $this;
     }
}
