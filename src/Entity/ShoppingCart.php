<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingCartRepository::class)]
class ShoppingCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $dateadded;

    #[ORM\Column(type: 'float')]
    private $productsum;

    #[ORM\Column(type: 'float')]
    private $deliverysum;

    #[ORM\Column(type: 'text', nullable: true)]
    private $note;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'shoppingCart', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id', nullable: false)]
    private $User;

    #[ORM\OneToMany(targetEntity: ShoppingCartEntry::class, mappedBy: 'shoppingCart', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private $ShoppingCartEntry;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $idaddress;

    public function __construct()
    {
        $this->ShoppingCartEntry = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateadded(): ?\DateTimeInterface
    {
        return $this->dateadded;
    }

    public function setDateadded(\DateTimeInterface $dateadded): self
    {
        $this->dateadded = $dateadded;

        return $this;
    }

    public function getProductsum(): ?float
    {
        return $this->productsum;
    }

    public function setProductsum(float $productsum): self
    {
        $this->productsum = $productsum;

        return $this;
    }

    public function getDeliverysum(): ?float
    {
        return $this->deliverysum;
    }

    public function setDeliverysum(float $deliverysum): self
    {
        $this->deliverysum = $deliverysum;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingCartEntry>
     */
    public function getShoppingCartEntry(): Collection
    {
        return $this->ShoppingCartEntry;
    }

    public function addShoppingCartEntry(ShoppingCartEntry $newShoppingCartEntry): self
    {
        foreach($this->getShoppingCartEntry() as $existingProduct){
            if($existingProduct->getProduct()->getId() == $newShoppingCartEntry->getProduct()->getId()){
                $existingProduct->setQuantity(($newShoppingCartEntry->getQuantity()+$existingProduct->getQuantity()));

                return $this;    
            }
            
        }
        $this->ShoppingCartEntry[] = $newShoppingCartEntry;
        $newShoppingCartEntry->setShoppingCart($this);

        return $this;
    }

    public function removeShoppingCartEntry(ShoppingCartEntry $shoppingCartEntry): self
    {
        if ($this->ShoppingCartEntry->removeElement($shoppingCartEntry)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCartEntry->getShoppingCart() === $this) {
                $shoppingCartEntry->setShoppingCart(null);
            }
        }

        return $this;
    }


    public function getFilteredShoppingCartEntry($field, $value): Collection
    {
        $expr = new Comparison($field, Comparison::EQ, $value);
        $criteria = new Criteria();
        $criteria->where($expr);
        return $this->ShoppingCartEntry->matching($criteria);
    }

    public function getIdaddress(): ?int
    {
        return $this->idaddress;
    }

    public function setIdaddress(?int $idaddress): self
    {
        $this->idaddress = $idaddress;

        return $this;
    }

    public function getItemQuantity(){
        return count($this->ShoppingCartEntry);
    }



  

}
