<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'float')]
    private ?float $price = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

    /* =======================
       STOCK PAR TAILLE
       ======================= */

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $stockXs = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $stockS = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $stockM = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $stockL = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $stockXl = 0;

    /* =======================
       GETTERS / SETTERS
       ======================= */

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /* ===== STOCK XS ===== */
    public function getStockXs(): int
    {
        return $this->stockXs;
    }

    public function setStockXs(int $stockXs): self
    {
        $this->stockXs = $stockXs;
        return $this;
    }

    /* ===== STOCK S ===== */
    public function getStockS(): int
    {
        return $this->stockS;
    }

    public function setStockS(int $stockS): self
    {
        $this->stockS = $stockS;
        return $this;
    }

    /* ===== STOCK M ===== */
    public function getStockM(): int
    {
        return $this->stockM;
    }

    public function setStockM(int $stockM): self
    {
        $this->stockM = $stockM;
        return $this;
    }

    /* ===== STOCK L ===== */
    public function getStockL(): int
    {
        return $this->stockL;
    }

    public function setStockL(int $stockL): self
    {
        $this->stockL = $stockL;
        return $this;
    }

    /* ===== STOCK XL ===== */
    public function getStockXl(): int
    {
        return $this->stockXl;
    }

    public function setStockXl(int $stockXl): self
    {
        $this->stockXl = $stockXl;
        return $this;
    }
}

