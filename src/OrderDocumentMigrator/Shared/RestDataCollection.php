<?php

namespace App\OrderDocumentMigrator\Shared;

class RestDataCollection extends \ArrayObject
{
    public const PROP_DATA = 'data';

    public const PROP_TOTAL = 'total';

    /**
     * @return array
     */
    public function getData(): array
    {
        return$this->offsetGet(static::PROP_DATA) ?? [];
    }

    /**
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return (int)$this->offsetGet(static::PROP_TOTAL) ?? null;
    }
}