<?php

namespace App\OrderDocumentMigrator\Contracts;

use App\OrderDocumentMigrator\Shared\RestDataCollection;

interface RestTransfer
{
    public function getParams(): mixed;

    public function setParams(mixed $params): self;

    public function getResponseData(): ?RestDataCollection;

    public function setResponseData(?RestDataCollection $data): self;
}