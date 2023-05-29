<?php

namespace App\OrderDocumentMigrator;

use App\OrderDocumentMigrator\Shared\ExecuteManager;

class OrderDocumentMigrator
{
    /**
     * @param \App\OrderDocumentMigrator\Shared\ExecuteManager|null $executeManager
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return void
     */
    public static function migrateOrdersDocuments(?ExecuteManager $executeManager): void
    {
        self::getFactory()->createDocumentManager()->importOrdersDocuments($executeManager);
    }

    /**
     * @param string $orderId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return void
     */
    public static function migrateOrderDocuments(string $orderId): void
    {
        self::getFactory()->createDocumentManager()->importOrderDocuments($orderId);
    }

    /**
     * @return \App\OrderDocumentMigrator\OrderDocumentMigratorFactory
     */
    protected static function getFactory(): OrderDocumentMigratorFactory
    {
        return new OrderDocumentMigratorFactory();
    }
}