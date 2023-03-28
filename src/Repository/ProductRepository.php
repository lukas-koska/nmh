<?php

declare(strict_types=1);

namespace App\Repository;

use App\Controller\InjectorTrait\Validator;
use App\Entity\Product;
use App\Rest\ResourceType\ProductRequest;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository
{
    use Validator;

    public function __construct(
        private ManagerRegistry $doctrine
    )
    {
        // Nothing to do here
    }

    public function getAllProducts()
    {
        return $this
            ->doctrine
            ->getRepository(Product::class)
            ->findAll();
    }

    public function getProductFromId(int $id)
    {
        return $this
            ->doctrine
            ->getRepository(Product::class)
            ->find($id);
    }

    public function createNewProduct(array $data) : ?Product
    {
        $product = $this->denormalizer->denormalize($data, Product::class);
        if ($product !== null) {
            // Also add data to ElasticSearch (via event handler from FOSElasticaBundle)
            $this->doctrine->getManager()->persist($product);
            $this->doctrine->getManager()->flush();
        }
        return $product;
    }

    public function editProduct(ProductRequest $dto, int $id) : ?Product
    {
        $product = $this->doctrine->getManager()->getRepository(Product::class)->find($id);
        if ($product !== null) {

            $product->setName($dto->getName());
            $product->setDescription($dto->getDescription());
            $product->setManufacturer($dto->getManufacturer());
            $product->setPrice($dto->getPrice());

            $this->doctrine->getManager()->flush();
        }
        return $product;
    }
}
