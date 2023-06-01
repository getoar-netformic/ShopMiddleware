<?php

namespace App\OrderDocumentMigrator;

use App\OrderDocumentMigrator\Exporter\ExporterDocumentFetcher;
use App\OrderDocumentMigrator\Migrator\DocumentMigrationManager;
use App\OrderDocumentMigrator\Migrator\DocumentsEnhancer;
use App\OrderDocumentMigrator\Receiver\ReceiverDocumentsFetcher;
use App\OrderDocumentMigrator\Receiver\ReceiverDocumentsUploader;
use App\OrderDocumentMigrator\Receiver\ReceiverDocumentUpdater;
use App\OrderDocumentMigrator\Receiver\ReceiverOrdersFetcher;
use App\OrderDocumentMigrator\Service\ExporterApiConfigProvider;
use App\OrderDocumentMigrator\Service\ReceiverApiConfigProvider;
use App\OrderDocumentMigrator\Service\RestApiClient;

class OrderDocumentMigratorFactory
{
    public function createDocumentManager(): DocumentMigrationManager
    {
        return new DocumentMigrationManager(
            $this->createDocumentFetcher(),
            $this->createOrdersFetcher(),
            $this->createDocumentUploader()
        );
    }

    public function createDocumentEnhancer(): DocumentsEnhancer
    {
        return new DocumentsEnhancer(
            $this->creteDocumentUpdater(),
            $this->createReceiverDocumentsFetcher()
        );
    }

    public function createDocumentFetcher(): ExporterDocumentFetcher
    {
        return new ExporterDocumentFetcher($this->createExporterRestClient());
    }

    public function createDocumentUploader(): ReceiverDocumentsUploader
    {
        return new ReceiverDocumentsUploader($this->createReceiverRestClient(), $this->createReceiverDocumentsFetcher());
    }

    public function createOrdersFetcher(): ReceiverOrdersFetcher
    {
        return new ReceiverOrdersFetcher($this->createReceiverRestClient());
    }

    public function createReceiverDocumentsFetcher(): ReceiverDocumentsFetcher
    {
        return new ReceiverDocumentsFetcher($this->createReceiverRestClient());
    }

    protected function createExporterRestClient(): RestApiClient
    {
        return new RestApiClient($this->createExporterConfigProvider());
    }

    protected function createReceiverRestClient(): RestApiClient
    {
        return new RestApiClient($this->createReceiverConfigProvider());
    }

    protected function createExporterConfigProvider(): ExporterApiConfigProvider
    {
        return new ExporterApiConfigProvider(OrderDocumentMigratorConfig::EXPORTER_ACCESS_GRANT);
    }

    protected function createReceiverConfigProvider(): ReceiverApiConfigProvider
    {
        return new ReceiverApiConfigProvider(OrderDocumentMigratorConfig::RECEIVER_ACCESS_GRANT);
    }

    protected function creteDocumentUpdater(): ReceiverDocumentUpdater
    {
        return new ReceiverDocumentUpdater($this->createReceiverRestClient());
    }
}