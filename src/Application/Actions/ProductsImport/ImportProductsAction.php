<?php

namespace App\Application\Actions\ProductsImport;

use App\Application\Actions\Action;
use App\ShopMiddleware\ShopMiddlewareFacade;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ImportProductsAction extends Action
{
    protected function action(): Response
    {
        $products = $this->getFormData();

        $products = ShopMiddlewareFacade::importProductsToShopFromJson(json_encode($products));

        return $this->respondWithData($products);
    }
}