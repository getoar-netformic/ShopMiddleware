<?php

namespace App\ShopMiddleware\Writer;

use App\ShopMiddleware\Reader\ProductReader;
use App\ShopMiddleware\Service\ContextService;
use App\ShopMiddleware\Utils\ProductConstants;
use Vin\ShopwareSdk\Data\Entity\Product\ProductDefinition;
use Vin\ShopwareSdk\Factory\RepositoryFactory;

class ProductImportWriter
{
    private ContextService $contextService;

    private ProductReader $reader;

    public function __construct(ContextService $contextService, ProductReader $reader)
    {
        $this->contextService = $contextService;
        $this->reader = $reader;
    }

    public function importProductsByJson(string $productsJsonList): array
    {
        $productsList = json_decode($productsJsonList, true);

        return $this->importProducts($productsList);
    }

    public function importProductByJson(string $productDataString): ?array
    {
        $productData = json_decode($productDataString, true);

        return $this->importProduct($productData);
    }

    public function importProducts(array $productsList): array
    {
        $result = [];

        foreach ($productsList as $product) {
            $result[] = $this->importProduct($product);
        }

        return $result;
    }

    public function importProduct(array $productData): ?array
    {
        $product = $this->reader->finByEan($productData[ProductConstants::PROP_EAN]);

        if (!empty($product)) {
            return $this->updateProductByEan($productData, $product);
        }

        return $this->createProduct($productData);
    }

    public function createProduct(array $productData): ?array
    {
        $productRepository = RepositoryFactory::create(ProductDefinition::ENTITY_NAME);

        $productRepository->create($productData, $this->contextService->getDefaultContext());

        return isset($productData[ProductConstants::PROP_EAN])
            ? $this->reader->finByEan($productData[ProductConstants::PROP_EAN])
            : null;
    }

    public function updateProductByEan(array $productData, array $product): array
    {
        $productRepository = RepositoryFactory::create(ProductDefinition::ENTITY_NAME);
        $productData = array_merge($product, $productData);

        $productRepository->update($productData, $this->contextService->getDefaultContext());

        return $this->reader->finByEan($productData[ProductConstants::PROP_EAN]);
    }
}