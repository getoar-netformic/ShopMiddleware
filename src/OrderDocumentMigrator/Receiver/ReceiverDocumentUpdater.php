<?php

namespace App\OrderDocumentMigrator\Receiver;

use App\OrderDocumentMigrator\Contracts\RestClient;
use App\OrderDocumentMigrator\Logger\MigratiorLogger;
use App\OrderDocumentMigrator\Shared\Transfer;
use Exception;

class ReceiverDocumentUpdater
{
    /**
     * @param \App\OrderDocumentMigrator\Contracts\RestClient $client
     */
    public function __construct(protected RestClient $client)
    {
    }

    public function updateDocumentsConfig(array $documents): void
    {
        foreach ($documents as $document) {
            $this->updateDocumentConfig($document);
        }
    }

    public function updateDocumentConfig(array $document): Transfer
    {
        if (!empty($document['config']['custom'])) {
            MigratiorLogger::writer()->log('Skipped: '. $document['_uniqueIdentifier'], 'info');
            return new Transfer();
        }

        try {
            $documentId = $document['_uniqueIdentifier'];
            $enhancedConfig = $this->enhanceConfigData($document);

            $response = $this->client->patch("/document/$documentId", json_encode(['config' => $enhancedConfig]), ['Content-Type' => 'application/json']);
            MigratiorLogger::writer()->log('Enhanced: '. $document['_uniqueIdentifier'], 'info');
            return new Transfer(null, $response->getResponseData());
        } catch (Exception $e) {
            MigratiorLogger::writer()->log($e->getMessage());
        }

        return new Transfer();
    }

    protected function enhanceConfigData(array $document)
    {
        $config = $document['config'] ?? [];

        if (!isset($config['documentNumber']) || !isset($config['name'])) {
            throw new Exception('No documentNumber or name in config!');
        }

        $config['custom'][$this->getTypeNumberKey($config['name'])] = $config['documentNumber'];

        return $config;
    }


    protected function getTypeNumberKey($type): string
    {
        $separator = '_';

        return lcfirst(str_replace($separator, '', ucwords($type, $separator))).'Number';
    }

}