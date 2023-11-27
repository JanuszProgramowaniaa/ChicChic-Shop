<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateadded = null;

    #[ORM\Column]
    private ?float $productsum = null;

    #[ORM\Column]
    private ?float $deliverysum = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(length: 255)]
    private ?string $person = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 12)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $zip = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderStatus $orderStatus = null;

    #[ORM\OneToMany(mappedBy: 'orderId', targetEntity: OrderEntry::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $orderEntries;

    public function __construct()
    {
        $this->orderEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateadded(): ?\DateTimeInterface
    {
        return $this->dateadded;
    }

    public function setDateadded(\DateTimeInterface $dateadded): static
    {
        $this->dateadded = $dateadded;

        return $this;
    }

    public function getProductsum(): ?float
    {
        return $this->productsum;
    }

    public function setProductsum(float $productsum): static
    {
        $this->productsum = $productsum;

        return $this;
    }

    public function getDeliverysum(): ?float
    {
        return $this->deliverysum;
    }

    public function setDeliverysum(float $deliverysum): static
    {
        $this->deliverysum = $deliverysum;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getPerson(): ?string
    {
        return $this->person;
    }

    public function setPerson(string $person): static
    {
        $this->person = $person;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): static
    {
        $this->zip = $zip;

        return $this;
    }

    public function getOrderStatus(): ?OrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?OrderStatus $orderStatus): static
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * @return Collection<int, OrderEntry>
     */
    public function getOrderEntries(): Collection
    {
        return $this->orderEntries;
    }

    public function addOrderEntry(OrderEntry $orderEntry): static
    {
        if (!$this->orderEntries->contains($orderEntry)) {
            $this->orderEntries->add($orderEntry);
            $orderEntry->setOrder($this);
        }

        return $this;
    }

    public function removeOrderEntry(OrderEntry $orderEntry): static
    {
        if ($this->orderEntries->removeElement($orderEntry)) {
            // set the owning side to null (unless already changed)
            if ($orderEntry->getOrder() === $this) {
                $orderEntry->setOrder(null);
            }
        }

        return $this;
    }
}
