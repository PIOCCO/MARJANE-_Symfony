<?php

namespace App\Entity;

use App\Repository\PayementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PayementRepository::class)]
class Payement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    private \DateTimeInterface $paidDate;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0, message: 'Total must be zero or greater.')]
    private float $total;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $details = null;

    public function __construct(float $total, ?string $details = null)
    {
        $this->paidDate = new \DateTime(); // Automatically set the payment date to the current date
        $this->total = $total;
        $this->details = $details;
    }

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaidDate(): \DateTimeInterface
    {
        return $this->paidDate;
    }

    public function setPaidDate(\DateTimeInterface $paidDate): self
    {
        $this->paidDate = $paidDate;
        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        if ($total < 0) {
            throw new \InvalidArgumentException('Total must be zero or greater.');
        }
        $this->total = $total;
        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;
        return $this;
    }
}
