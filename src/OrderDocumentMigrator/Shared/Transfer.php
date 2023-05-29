<?php

namespace App\OrderDocumentMigrator\Shared;

use App\OrderDocumentMigrator\Contracts\RestTransfer;

class Transfer implements RestTransfer
{
    /**
     * @var \ArrayObject | array
     */
    protected mixed $params;

    /**
     * @var \App\OrderDocumentMigrator\Shared\RestDataCollection|null
     */
    protected ?RestDataCollection $data;

    public function __construct(mixed $params = null, ?RestDataCollection $data = null)
    {
        $this->setParams($params);
        $this->setResponseData($data);
    }

    public function getParams(): mixed
    {
        return $this->params;
    }

    public function setParams(mixed $params): RestTransfer
    {
        $this->params = $params;

        return $this;
    }

    public function getResponseData(): ?RestDataCollection
    {
        return $this->data;
    }

    public function setResponseData(?RestDataCollection $data): RestTransfer
    {
        $this->data = $data;

        return $this;
    }
}