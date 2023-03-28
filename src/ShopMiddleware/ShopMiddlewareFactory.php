<?php

namespace App\ShopMiddleware;

use App\ShopMiddleware\Reader\ProductReader;
use App\ShopMiddleware\Service\ApiAuthService;
use App\ShopMiddleware\Service\ContextService;
use App\ShopMiddleware\Writer\ProductImportWriter;

class ShopMiddlewareFactory
{
    public function createProductImportWriter(): ProductImportWriter
    {
        return new ProductImportWriter($this->createContextService(), $this->createProductReader());
    }

    public function createProductReader(): ProductReader
    {
        return new ProductReader($this->createContextService());
    }

    protected function createContextService(): ContextService
    {
        return new ContextService($this->createApiAuthService());
    }

    protected function createApiAuthService(): ApiAuthService
    {
        return new ApiAuthService();
    }
}