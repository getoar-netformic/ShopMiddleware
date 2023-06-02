<?php

namespace App\OrderDocumentMigrator\Receiver;

use App\OrderDocumentMigrator\Logger\MigratiorLogger;
use App\OrderDocumentMigrator\Service\RestApiClient;
use App\OrderDocumentMigrator\Shared\Transfer;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Slim\App;
use Slim\Logger;

class ReceiverDocumentsUploader
{
    public function __construct(protected RestApiClient $client, protected ReceiverDocumentsFetcher $documentsFetcher)
    {
    }

    /**
     * @param \App\OrderDocumentMigrator\Shared\Transfer $response
     *
     * @return void
     */
    public function uploadOrderDocuments(Transfer $response): void
    {
        $order = $response->getParams()['order'];
        $documents = $response->getParams()['documents'];

        foreach ($documents as $document) {
            try {
                if ($document['type']['key'] !== 'cancellation') continue;

                if ($document['type']['key'] === 'cancellation') {
                    $documentData = $this->parseCancellationDocument($order, $document);
                    $createdDocument = $this->createCancellationDocument($documentData)->getResponseData()?->getData();
                } else {
                    $documentData = $this->parseCreateDocumentData($order, $document);
                    $createdDocument = $this->createDocumentForOrder($documentData)->getResponseData()?->getData();
                }

                if ($createdDocument) {
                    $documentEntity = $this->parseUploadDocumentData(array_merge($document, $createdDocument));
                    $this->uploadMediaFileForDocument($documentEntity);
                }
            } catch (Exception $e) {
                continue;
            }
        }
    }

    /**
     * @param array $data
     *
     * @return \App\OrderDocumentMigrator\Shared\Transfer
     */
    public function createDocumentForOrder(array $data): Transfer
    {
        $path = sprintf('/_action/order/%s/document/%s?fileType=%s',
            $data['orderId'], $data['type'], $data['fileType']
        );

        try {
            MigratiorLogger::writer()->log($path, 'info');
            $response = $this->client->post($path, json_encode($data), ['Content-Type' => 'application/json']);

            return new Transfer(null, $response->getResponseData());
        } catch (\Exception $e) {
            MigratiorLogger::writer()->log($e->getMessage(), 'error');
            return new Transfer();
        }

    }

    /**
     * @param array $data
     *
     * @return Transfer
     */
    public function createCancellationDocument(array $data): Transfer
    {
        $path ='/document?_response=detail';

        try {
            MigratiorLogger::writer()->log($data['orderId'], 'info');
            $response = $this->client->post($path, json_encode($data), ['Content-Type' => 'application/json']);

            return new Transfer(null, $response->getResponseData());
        } catch (\Exception $e) {
            MigratiorLogger::writer()->log($e->getMessage());
            return new Transfer();
        }
    }

    /**
     * @param array $data
     *
     * @return \App\OrderDocumentMigrator\Shared\Transfer
     */
    public function uploadMediaFileForDocument(array $data): Transfer
    {
        try {
            if (!file_exists($data['file'])) {
                MigratiorLogger::writer()->log('File not exist: ' . $data['file']);
                return new Transfer();
            }

            $path = sprintf('/_action/document/%s/upload?fileName=%s&extension=%s',
                $data['documentId'], $data['fileName'], $data['extension']
            );

            $response = $this->client->post($path, file_get_contents($data['file']),
                ['Content-Type' => 'application/octet-stream']
            );

            return new Transfer(null, $response->getResponseData());
        }  catch (\Exception $e) {
            MigratiorLogger::writer()->log($e->getMessage());
            return new Transfer();
        }
    }

    /**
     * @param array $order
     * @param array $document
     *
     * @return array
     */
    protected function parseCreateDocumentData(array $order, array $document): array
    {
        $typeMap = ['cancellation' => 'storno'];
        $type = $document['type']['key'];

        return [
            'orderId' => $order['id'],
            'type' => $typeMap[$type] ?? $type,
            'fileType' => $this->getExtensionByType($type),
            'static' => true,
            'config' => [
                'custom' => [
                    $this->getTypeNumberKey($typeMap[$type] ?? $type) => (string) $document['documentId']
                ],
                'documentNumber' => (string) $document['documentId'],
                'documentDate' => $document['date'],
            ]
        ];
    }

    /**
     * @param array $order
     * @param array $document
     * @ return array
     *
     * @throws GuzzleException
     */
    protected function parseCancellationDocument(array $order, array $document): array
    {
        $documentTypeId = '0b49ed15582c47e09c85ac20658ab5f0';
        $typeMap = ['cancellation' => 'storno'];
        $type = $document['type']['key'];
        $invoices = $this->documentsFetcher->getInvoicesDocumentsByOrderId($order['id']);

        if (count($invoices) !== 1) {
            throw new Exception('No invoice can not be determined to be cancelled for this order: ' . $order['id']);
        }

        return [
            'orderId' => $order['id'],
            'documentTypeId' => $documentTypeId,
            'referencedDocumentId' => (string)$invoices[0]['_uniqueIdentifier'],
            'fileType' => $this->getExtensionByType($type),
            'deepLinkCode' => $document['hash'],
            'static' => true,
            'config' => [
                'custom' => [
                    $this->getTypeNumberKey($typeMap[$type] ?? $type) => (string)$document['documentId'],
                    'invoiceNumber' => (string)$invoices[0]['config']['documentNumber'] ?? 'null'
                ],
                'documentNumber' => (string)$document['documentId'],
                'documentDate' => $document['date'],
            ]
        ];
    }

    /**
     * @param array $document
     *
     * @return array
     */
    protected function parseUploadDocumentData(array $document): array
    {
        $link = sprintf('%s/documents/%s.%s',
            public_path(), $document['hash'], $this->getExtensionByType($document['type']['key'])
        );

        return [
            'file' => $link,
            'extension' => $this->getExtensionByType($document['type']['key']),
            'fileName' => $document['hash'],
            'documentId' => $document['_uniqueIdentifier'] ?? $document['documentId'],
        ];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getExtensionByType(string $type = 'invoice'): string
    {
        $typesToExtension = ['invoice' => 'pdf'];

        return $typesToExtension[$type] ?? 'pdf';
    }

    protected function getTypeNumberKey($type): string
    {
        $separator = '_';

        return lcfirst(str_replace($separator, '', ucwords($type, $separator))).'Number';
    }

}