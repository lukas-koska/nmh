<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\InjectorTrait\Validator;
use App\Entity\Product;
use App\Rest\ResourceType\ProductRequest;
use App\Service\ElasticSearchService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class ProductController extends AbstractController
{
    use Validator;

    /**
     * @Route("/product", name="product_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        // If product has only one category, the category could be deliver with product
        $products = $doctrine
            ->getRepository(Product::class)
            ->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
            ];
        }

        return $this->json([
            'success' => true,
            'products' => $data
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     */
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        /** @var Product $product */
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No product found for id' . $id, 404);
        }

        $data =  [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'manufacturer' => $product->getManufacturer(),
            'price' => $product->getPrice(),
        ];

        return $this->json([
            'success' => true,
            'product' => $data
        ]);
    }

    /**
     * @Route("/product", name="product_new", methods={"POST"})
     */
    public function new(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $requestData = $request->request->all();
        if (array_key_exists('price', $requestData)) {
            $requestData['price'] = floatval($requestData['price']);
        }

        /** @var ProductRequest $dto */
        $dto = $this->denormalizer->denormalize($requestData, ProductRequest::class);

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json([
                'success' => false,
                'errors' => count($errors)
            ]);
        }

        $entityManager = $doctrine->getManager();

        $product = $this->denormalizer->denormalize($requestData, Product::class);
        if ($product !== null) {
            // Also add data to ElasticSearch
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'id' => $product->getId()
            ]);
        }
        return $this->json([
            'success' => false,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_new", methods={"PUT"})
     */
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        // Validate fields
        $requestData = json_decode($request->getContent(), true);
        if (array_key_exists('price', $requestData)) {
            $requestData['price'] = floatval($requestData['price']);
        }

        /** @var ProductRequest $dto */
        $dto = $this->denormalizer->denormalize($requestData, ProductRequest::class);

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json([
                'success' => false,
                'errors' => count($errors)
            ]);
        }

        $entityManager = $doctrine->getManager();

        /** @var Product $product */
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json([
                'success' => false,
                'message' => 'No product found for given id',
            ], 404);
        }

        $product->setName($dto->getName());
        $product->setDescription($dto->getDescription());
        $product->setManufacturer($dto->getManufacturer());
        $product->setPrice($dto->getPrice());

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'product' => $product->toArray()
        ]);
    }

    /**
     * @Route("/product/search", name="product_search", methods={"POST"})
     */
    public function search(ManagerRegistry $doctrine, Request $request, ElasticSearchService $elasticSearchService): JsonResponse
    {
        // Validate search fields
        $requestData = $request->request->all();
        if (array_key_exists('price', $requestData)) {
            $requestData['price'] = floatval($requestData['price']);
        }

        /** @var ProductRequest $dto */
        $dto = $this->denormalizer->denormalize($requestData, ProductRequest::class);

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json([
                'success' => false,
                'errors' => count($errors)
            ]);
        }

        // GET DATA FROM ElasticSearch
        /** @var Product[] $products */
        $products = $elasticSearchService->getSearchResultFromElastic($dto->toArray());

        return $this->json([
            'success' => true,
            'products' => $products
        ]);
    }
}
