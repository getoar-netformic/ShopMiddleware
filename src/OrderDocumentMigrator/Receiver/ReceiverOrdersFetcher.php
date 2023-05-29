<?php

namespace App\OrderDocumentMigrator\Receiver;

use App\OrderDocumentMigrator\Contracts\RestClient;
use App\OrderDocumentMigrator\Shared\Transfer;

class ReceiverOrdersFetcher
{
    /**
     * @param \App\OrderDocumentMigrator\Contracts\RestClient $client
     */
    public function __construct(protected RestClient $client)
    {
    }

    /**
     * @param \App\OrderDocumentMigrator\Shared\Transfer $ordersFilters
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \App\OrderDocumentMigrator\Shared\Transfer
     */
    public function getOrders(Transfer $ordersFilters): Transfer
    {
        $data = $this->client->get('/order', $ordersFilters->getParams());

        $ordersFilters->setResponseData($data->getResponseData());

        return $ordersFilters;
    }

    /**
     * @param \App\OrderDocumentMigrator\Shared\Transfer $orderFilters
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \App\OrderDocumentMigrator\Shared\Transfer
     */
    public function getOrder(Transfer $orderFilters): Transfer
    {
        $orderId =  $orderFilters->getParams()["orderId"];
        $data = $this->client->get("/order/$orderId");
        $orderFilters->setResponseData($data->getResponseData());

        return $orderFilters;
    }
}