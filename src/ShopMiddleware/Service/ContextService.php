<?php

namespace App\ShopMiddleware\Service;

use App\ShopMiddleware\ShopMiddlewareConfig;
use Vin\ShopwareSdk\Data\Context;

class ContextService
{
    private ApiAuthService $apiAuthService;

    public function __construct(ApiAuthService $apiAuthService)
    {
        $this->apiAuthService = $apiAuthService;
    }

    public function getDefaultContext(): Context
    {
        return (new Context(ShopMiddlewareConfig::SHOP_URL, $this->apiAuthService->authenticate()));
    }
}