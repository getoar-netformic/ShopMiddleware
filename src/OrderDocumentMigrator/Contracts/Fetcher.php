<?php

namespace App\OrderDocumentMigrator\Contracts;


interface Fetcher
{
    public function fetch(RestTransfer $data): RestTransfer;
}