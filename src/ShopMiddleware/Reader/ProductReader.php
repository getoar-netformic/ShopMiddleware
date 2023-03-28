<?php

namespace App\ShopMiddleware\Reader;

use App\ShopMiddleware\Service\ContextService;
use App\ShopMiddleware\Utils\ProductConstants;
use Vin\ShopwareSdk\Data\Criteria;
use Vin\ShopwareSdk\Data\Entity\Product\ProductDefinition;
use Vin\ShopwareSdk\Data\Filter\EqualsFilter;
use Vin\ShopwareSdk\Factory\RepositoryFactory;

class ProductReader
{
    protected ContextService $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    public function finByEan(?string $ean): ?array
    {
        $productRepository = RepositoryFactory::create(ProductDefinition::ENTITY_NAME);
        $result = $productRepository->search(
            $this->getSearchWithEanCriteria($ean),
            $this->contextService->getDefaultContext()
        );

        return (array)$result->first();
    }

    protected function getSearchWithEanCriteria(?string $ean): Criteria
    {
        $criteria = (new Criteria());
        $criteria->addFilter((new EqualsFilter(ProductConstants::PROP_EAN, $ean ?? 'asdf')));

        return $criteria;
    }
}