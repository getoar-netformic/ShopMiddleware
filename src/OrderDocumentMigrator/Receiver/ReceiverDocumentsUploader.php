<?php

namespace App\OrderDocumentMigrator\Receiver;

use App\OrderDocumentMigrator\Logger\MigratiorLogger;
use App\OrderDocumentMigrator\Service\RestApiClient;
use App\OrderDocumentMigrator\Shared\Transfer;
use Slim\App;
use Slim\Logger;

class ReceiverDocumentsUploader
{
    public function __construct(protected RestApiClient $client)
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
            $documentData = $this->parseCreateDocumentData($order, $document);
            $createdDocument = $this->createDocumentForOrder($documentData)->getResponseData()?->getData();

            if ($createdDocument) {
                $documentEntity = $this->parseUploadDocumentData(array_merge($document, $createdDocument));
                $this->uploadMediaFileForDocument($documentEntity);
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
        if (!file_exists($data['file'])) {
            MigratiorLogger::writer()->log('File not exist: '. $data['file']);
            return new Transfer();
        }

        $path = sprintf('/_action/document/%s/upload?fileName=%s&extension=%s',
            $data['documentId'], $data['fileName'], $data['extension']
        );

        $response = $this->client->post($path, file_get_contents($data['file']),
            ['Content-Type' => 'application/octet-stream']
        );

        return new Transfer(null, $response->getResponseData());
    }

    /**
     * @param $order
     * @param array $document
     *
     * @return array
     */
    protected function parseCreateDocumentData($order, array $document): array
    {
        return [
            'orderId' => $order['id'],
            'type' => $document['type']['key'],
            'fileType' => $this->getExtensionByType($document['type']['key']),
            'static' => true,
            'config' => [
                'documentNumber' => (string) $document['id'].'migrateda', //TODO: documentId
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
        $link = sprintf('%s/files/%s.%s',
            public_path(), $document['hash'], $this->getExtensionByType($document['type']['key'])
        );

        return [
            'file' => $link,
            'extension' => $this->getExtensionByType($document['type']['key']),
            'fileName' => $document['hash'],
            'documentId' => $document['documentId'],
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

}