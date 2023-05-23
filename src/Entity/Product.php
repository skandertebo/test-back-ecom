<?php

namespace App\Entity;

use App\Entity\Type;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type = null;

    #[ORM\Column]
    private ?float $basePrice = null;

    #[ORM\Column]
    private ?int $stockQuantity = null;

    #[ORM\ManyToOne(inversedBy: 'products', )]
    #[ORM\JoinColumn(onDelete:"SET NULL")]
    private ?Sale $sale = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $images = [];

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;



    public function __construct()
    {
        $this->images = [];
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?type
    {
        return $this->type;
    }

    public function setType(?type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): self
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getStockQuantity(): ?int
    {
        return $this->stockQuantity;
    }

    public function setStockQuantity(int $stockQuantity): self
    {
        $this->stockQuantity = $stockQuantity;

        return $this;
    }

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function setSale(?Sale $sale): self
    {
        $this->sale = $sale;

        return $this;
    }

    public function addImage(string $image): self
    {
        $this->images[] = $image;
        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): self
    {
        $this->images = [];
        foreach ($images as $image) {
            $this->addImage($image);
        }
        return $this;
    }

    public function removeImage(string $image): self
    {
        $index = array_search($image, $this->images);
        if ($index !== false) {
            unset($this->images[$index]);
        }
        return $this;
    }

    public function jsonSerialize(){
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => [
                'id' => $this->getType()->getId(),
                'name' => $this->getType()->getName()
            ],
            'basePrice' => $this->getBasePrice(),
            'stockQuantity' => $this->getStockQuantity(),
            'sale' => $this->getSale(),
            'images' => $this->getImages(),
            'description' => $this->getDescription()
        ];
    }


}
