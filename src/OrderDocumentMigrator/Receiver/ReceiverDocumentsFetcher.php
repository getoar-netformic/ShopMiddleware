<?php

namespace App\OrderDocumentMigrator\Receiver;

use App\OrderDocumentMigrator\Contracts\RestClient;
use App\OrderDocumentMigrator\Shared\Transfer;

class ReceiverDocumentsFetcher
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
    public function getDocuments(Transfer $ordersFilters): Transfer
    {
        $data = $this->client->get('/document', $ordersFilters->getParams());

        $ordersFilters->setResponseData($data->getResponseData());

        return $ordersFilters;
    }

    /**
     * @param string $orderId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInvoicesDocumentsByOrderId(string $orderId)
    {
        $filters = (new Transfer())->setParams(['filter[orderId]' => $orderId, 'filter[documentTypeId]' => '3406f3abe1784849873d5e4e714208b8']);

        return  $this->getDocuments($filters)->getResponseData()?->getData() ?? [];
    }

    /**
     * @param \App\OrderDocumentMigrator\Shared\Transfer $orderFilters
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \App\OrderDocumentMigrator\Shared\Transfer
     */
    public function getDocument(Transfer $orderFilters): Transfer
    {
        $documentId =  $orderFilters->getParams()["documentId"];
        $data = $this->client->get("/document/$documentId");

        $orderFilters->setResponseData($data->getResponseData());

        return $orderFilters;
    }
}