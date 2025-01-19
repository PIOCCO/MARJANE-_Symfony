<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, name: 'account_name')]
    private string $accountName;

    #[ORM\Column(type: 'boolean')]
    private bool $isClosed = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $openDate = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $closedDate = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVip = false;

    // Many-to-One relationship with Customer
    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Customer $customer = null;

    public function __construct(string $accountName)
    {
        $this->openDate = new \DateTime(); // Automatically set openDate
        $this->isClosed = false;          // Set default value
        $this->accountName = $accountName;
    }

    // ------------------- Getters and Setters -------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    public function setAccountName(string $accountName): self
    {
        $this->accountName = $accountName;
        return $this;
    }

    public function isClosed(): bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        // Automatically set closed date when account is closed
        if ($isClosed && !$this->closedDate) {
            $this->closedDate = new \DateTime();
        }

        return $this;
    }

    public function getOpenDate(): ?\DateTimeInterface
    {
        return $this->openDate;
    }

    public function setOpenDate(\DateTimeInterface $openDate): self
    {
        $this->openDate = $openDate;
        return $this;
    }

    public function getClosedDate(): ?\DateTimeInterface
    {
        return $this->closedDate;
    }

    public function setClosedDate(?\DateTimeInterface $closedDate): self
    {
        $this->closedDate = $closedDate;
        return $this;
    }

    public function isVip(): bool
    {
        return $this->isVip;
    }

    public function setIsVip(bool $isVip): self
    {
        $this->isVip = $isVip;
        return $this;
    }

    // ------------------- Customer Relationship -------------------

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;
        return $this;
    }
}
