<?php

namespace App\OrderDocumentMigrator\Receiver;

use App\OrderDocumentMigrator\Contracts\RestClient;

class ReceiverDocumentUpdater
{
    /**
     * @param \App\OrderDocumentMigrator\Contracts\RestClient $client
     */
    public function __construct(protected RestClient $client)
    {
    }


    public function updateDocumentConfig(array $document): void
    {

        die(json_encode($document));
    }

}