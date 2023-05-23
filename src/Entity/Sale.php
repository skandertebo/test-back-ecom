<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
class Sale implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;


    #[ORM\Column]
    private ?float $rate = null;

    #[ORM\OneToMany(mappedBy: 'sale', targetEntity: Product::class)]
    private Collection $products;

    #[ORM\Column]
    private ?bool $expired = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $expireDate = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }


    public function getExpireDate(): ?\DateTimeInterface
    {
        return $this->expireDate;
    }

    public function setExpireDate(?\DateTimeInterface $expireDate): self
    {
        $this->expireDate = $expireDate;

        return $this;
    }
    

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        
        $this->name = $name;

        return $this;
    }


    /**
     * @return Collection<int, product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setSale($this);
        }

        return $this;
    }

    public function removeProduct(product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSale() === $this) {
                $product->setSale(null);
            }
        }

        return $this;
    }

    public function isExpired(): ?bool
    {
        return $this->expired;
    }

    public function setExpired(bool $expired): self
    {
        $this->expired = $expired;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'rate' => $this->getRate(),
            'expired' => $this->isExpired(),
            'expireDate' => $this->getExpireDate(),
        ];
    }
}
