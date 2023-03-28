<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\InjectorTrait\CustomJsonResponse;
use App\Controller\InjectorTrait\Validator;
use App\Entity\Product;
use App\Repository\ProductRepository;
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
    use CustomJsonResponse;

    public function __construct(
        private ProductRepository $productRepository
    )
    {
        // Nothing to do here
    }

    /**
     * @Route("/product", name="product_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        // If product has only one category, the category could be deliver with product
        /** @var Product $products */
        $products = $this->productRepository->findAll();

        // Do some additional manipulation with products

        return $this->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        // This could be moved to repository
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->json([
                'success' => false,
                'message' => 'No product found for id ' . $id
            ], 404);
        }

        return $this->json([
            'success' => true,
            'product' => $product->toArray(),
        ]);
    }

    /**
     * @Route("/product/create", name="product_new", methods={"POST"})
     */
    public function new(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $requestData = $request->request->all();
        /** @var ProductRequest $dto */
        $dto = $this->denormalizeRequest($requestData, ProductRequest::class);

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json([
                'success' => false,
                'errors' => count($errors),
            ]);
        }

        /** @var ?Product $product */
        $product = $this->productRepository->createNewProduct($requestData);
        if ($product !== null) {
            return $this->json([
                'success' => true,
                'id' => $product->getId(),
            ]);
        }

        return $this->json([
            'success' => false,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_edit", methods={"PUT"})
     */
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        // Validate fields
        $requestData = json_decode($request->getContent(), true);
        /** @var ProductRequest $dto */
        $dto = $this->denormalizeRequest($requestData, ProductRequest::class);

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json([
                'success' => false,
                'errors' => count($errors),
            ]);
        }

        /** @var Product $product */
        $product = $this->productRepository->editProduct($dto, $id);

        if ($product === null) {
            return $this->json([
                'success' => false,
                'message' => 'No product found for given id',
            ], 404);
        }

        return $this->json([
            'success' => true,
            'product' => $product->toArray(),
        ]);
    }

    /**
     * @Route("/product/search", name="product_search", methods={"POST"})
     */
    public function search(ManagerRegistry $doctrine, Request $request, ElasticSearchService $elasticSearchService): JsonResponse
    {
        // Validate search fields
        $requestData = $request->request->all();

        /** @var ProductRequest $dto */
        $dto = $this->denormalizeRequest($requestData, ProductRequest::class);

        $errors = $this->validator->validate($dto);


        if (count($errors) > 0) {
            return $this->json([
                'success' => false,
                'errors' => count($errors),
            ]);
        }

        // GET DATA FROM ElasticSearch
        /** @var Product[] $products */
        $products = $elasticSearchService->getSearchResultFromElastic($dto->toArray());

        return $this->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    private function denormalizeRequest(array $requestData, string $type)
    {
        // Do some additional manipulation
        if (array_key_exists('price', $requestData)) {
            $requestData['price'] = floatval($requestData['price']);
        }

        return $this->denormalizer->denormalize($requestData, $type);
    }
}
