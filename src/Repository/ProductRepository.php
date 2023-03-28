<?php

declare(strict_types=1);

namespace App\Repository;

use App\Controller\InjectorTrait\Validator;
use App\Entity\Product;
use App\Rest\ResourceType\ProductRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    use Validator;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function createNewProduct(array $data) : ?Product
    {
        if (array_key_exists('price', $data)) {
            $data['price'] = floatval($data['price']);
        }

        $product = $this->denormalizer->denormalize($data, Product::class);
        if ($product !== null) {
            // Also add data to ElasticSearch (via event handler from FOSElasticaBundle)
            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();
        }
        return $product;
    }

    public function editProduct(ProductRequest $dto, int $id) : ?Product
    {
        $product = $this->find($id);
        if ($product !== null) {

            $product->setName($dto->getName());
            $product->setDescription($dto->getDescription());
            $product->setManufacturer($dto->getManufacturer());
            $product->setPrice($dto->getPrice());

            $this->getEntityManager()->flush();
        }
        return $product;
    }
}
