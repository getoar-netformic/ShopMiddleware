<?php

namespace App\ShopMiddleware;

class ShopMiddlewareFacade
{
    public static function importProductsToShopFromJson(string $productsJsonList): ?array
    {
        return self::getFactory()->createProductImportWriter()->importProductsByJson($productsJsonList);
    }

    public static function importProductToShopFromJson(string $productJson): ?array
    {
        return self::getFactory()->createProductImportWriter()->importProductByJson($productJson);
    }

    public static function importProductsToShop(array $productsList): ?array
    {
        return self::getFactory()->createProductImportWriter()->importProducts($productsList);
    }

    public static function importProductToShop(array $productData): ?array
    {
        return self::getFactory()->createProductImportWriter()->importProduct($productData);
    }

    public static function getProductByEan(string $ean): ?array
    {
        return self::getFactory()->createProductReader()->finByEan($ean);
    }

    protected static function getFactory(): ShopMiddlewareFactory
    {
        return new ShopMiddlewareFactory();
    }
}