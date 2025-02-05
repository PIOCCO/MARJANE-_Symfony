<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numbrt = null;  // Renamed back to 'numbrt'

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $ordered = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $shipped = null;

    #[ORM\Column(length: 255)]
    private ?string $ship_to = null;


    // Many-to-One relationship with Customer
    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    // Getters and Setters for the Customer relationship
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    // Getters and Setters for the other fields
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumbrt(): ?string
    {
        return $this->numbrt;
    }

    public function setNumbrt(string $numbrt): static
    {
        $this->numbrt = $numbrt;

        return $this;
    }

    public function getOrdered(): ?\DateTimeInterface
    {
        return $this->ordered;
    }

    public function setOrdered(\DateTimeInterface $ordered): static
    {
        $this->ordered = $ordered;

        return $this;
    }

    public function getShipped(): ?\DateTimeInterface
    {
        return $this->shipped;
    }

    public function setShipped(\DateTimeInterface $shipped): static
    {
        $this->shipped = $shipped;

        return $this;
    }

    public function getShipTo(): ?string
    {
        return $this->ship_to;
    }

    public function setShipTo(string $ship_to): static
    {
        $this->ship_to = $ship_to;

        return $this;
    }
}
