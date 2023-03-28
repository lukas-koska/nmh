<?php

declare(strict_types=1);

namespace App\Rest\ResourceType;

use App\Rest\AsArray;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

class SearchRequest implements RequestInterface
{
    use AsArray;

    #[Assert\Blank]
    #[Assert\Null]
    #[Assert\MoreThan(0)]
    private int $id;

    #[Assert\Blank]
    #[Assert\Null]
    #[Assert\Regex('/[\p{L}\p{M}]+ [\p{L}\p{M}]+/u')]
    private string $name;

    #[Assert\Blank]
    #[Assert\Null]
    private ?string $description;

    #[Assert\Blank]
    #[Assert\Null]
    #[Assert\Regex('/[\p{L}\p{M}]+ [\p{L}\p{M}]+/u')]
    private ?string $manufacturer;

    #[Assert\Blank]
    #[Assert\Null]
    private ?float $price = 0;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
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
     * @return float|int|null
     */
    public function getPrice(): float|int|null
    {
        return $this->price;
    }

    /**
     * @param float|int|string|null $price
     */
    public function setPrice(float|int|string|null $price): void
    {
        if (is_string($price)) {
            $this->price = floatval($price);
            return;
        }
        $this->price = $price;
    }

}
