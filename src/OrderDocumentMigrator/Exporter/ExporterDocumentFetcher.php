<?php

namespace App\OrderDocumentMigrator\Exporter;

use App\OrderDocumentMigrator\Contracts\RestClient;
use App\OrderDocumentMigrator\Shared\Transfer;

class ExporterDocumentFetcher
{
    /**
     * @param \App\OrderDocumentMigrator\Contracts\RestClient $client
     */
    public function __construct(protected RestClient $client) {}

    /**
     * @param \App\OrderDocumentMigrator\Shared\Transfer $documentsFilters
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \App\OrderDocumentMigrator\Shared\Transfer
     */
    public function getDocumentData(Transfer $documentsFilters): Transfer
    {
        $filters = $documentsFilters->getParams();
        $orderNumber = $filters['orderNumber'];
        unset($filters['orderNumber']);

        $data = $this->client->get("/orders/$orderNumber", $documentsFilters->getParams());
        $documentsFilters->setResponseData($data->getResponseData());

        return $documentsFilters;
    }
}