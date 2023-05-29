<?php

namespace App\OrderDocumentMigrator\Contracts;

use App\OrderDocumentMigrator\Shared\RestDataCollection;

interface RestResponseParser
{
    /**
     * @return \App\OrderDocumentMigrator\Shared\RestDataCollection
     */
    public function getResponseData(): RestDataCollection;
}