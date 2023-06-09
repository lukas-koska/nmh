<?php

declare(strict_types=1);

namespace App\Entity;

use App\Trait\AsArray;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Product
 *
 * @ORM\Table(name="product")
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    use AsArray;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @Groups({"elastica"})
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private ?string $name;

    /**
     * @Groups({"elastica"})
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @Groups({"elastica"})
     *
     * @ORM\Column(name="manufacturer", type="string", nullable=true)
     */
    private ?string $manufacturer;

    /**
     * @Groups({"elastica"})
     *
     * @ORM\Column(name="price", type="float", options={"default" : "0"})
     */
    private ?float $price;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    /**
     * @param string|null $manufacturer
     */
    public function setManufacturer(?string $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }
}
