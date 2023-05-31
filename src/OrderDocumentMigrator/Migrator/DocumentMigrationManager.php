<?php

namespace App\OrderDocumentMigrator\Migrator;

use App\OrderDocumentMigrator\Exporter\ExporterDocumentFetcher;
use App\OrderDocumentMigrator\Receiver\ReceiverDocumentsUploader;
use App\OrderDocumentMigrator\Receiver\ReceiverOrdersFetcher;
use App\OrderDocumentMigrator\Shared\ExecuteManager;
use App\OrderDocumentMigrator\Shared\Transfer;
use Exception;

class DocumentMigrationManager
{
    const PROP_LIMIT = 100;

    public function __construct(
        protected ExporterDocumentFetcher   $exporterDocumentFetcher,
        protected ReceiverOrdersFetcher     $receiverOrdersFetcher,
        protected ReceiverDocumentsUploader $receiverDocumentsUploader
    ) {
    }

    /**
     * @param \App\OrderDocumentMigrator\Shared\ExecuteManager|null $executeManager
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return void
     */
    public function importOrdersDocuments(?ExecuteManager $executeManager): void
    {
        $executeManager = $executeManager ?? new ExecuteManager();
        $executeManager->progressBarStart();

        $filters = new Transfer(['limit' => self::PROP_LIMIT, 'page' => 1, 'total-count-mode' => 1]);
        $responseData = $this->receiverOrdersFetcher->getOrders($filters)->getResponseData();
        $offset = 0;

        $executeManager->progressBarSetSteps($responseData->getTotal());
        sleep(1);
        $executeManager->progressBarSetStep(self::PROP_LIMIT * $filters->getParams()['page']);

        while ($offset < $responseData->getTotal()) {
            $filters = new Transfer([
                'limit' => self::PROP_LIMIT, 'page' => $filters->getParams()['page'] + 1, 'total-count-mode' => 1
            ]);
            $offset = self::PROP_LIMIT * $filters->getParams()['page'];

            $this->createUploadOrdersDocuments($responseData->getData());
            $executeManager->progressBarAdvance(self::PROP_LIMIT);

            $responseData = $this->receiverOrdersFetcher->getOrders($filters)->getResponseData();
        }

        $executeManager->progressBarFinish();
    }


    /**
     * @param string $orderId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return void
     */
    public function importOrderDocuments(string $orderId): void
    {
        $params = new Transfer(['orderId' => $orderId]);
        $responseData = $this->receiverOrdersFetcher->getOrder($params)->getResponseData();

        $this->createUploadOrderDocuments($responseData->getData());
    }


    /**
     * @param array $orders
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return void
     */
    protected function createUploadOrdersDocuments(array $orders): void
    {
        foreach ($orders as $order) {
            $this->createUploadOrderDocuments($order);
        }
    }

    /**
     * @param array $order
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return void
     */
    protected function createUploadOrderDocuments(array $order): void
    {
        $uploadTransfer = (new Transfer())->setParams([
            'order' => $order, 'documents' => $this->getOrderDocuments($order)
        ]);

        $this->receiverDocumentsUploader->uploadOrderDocuments($uploadTransfer);
    }

    /**
     * @param mixed $order
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array
     */
    private function getOrderDocuments(mixed $order): array
    {
        try {
            $filters = new Transfer(['useNumberAsId' => true, 'orderNumber' => $order['orderNumber']]);
            $response = $this->exporterDocumentFetcher->getDocumentData($filters)->getResponseData()?->getData() ?? [];

            return $response['documents'] ?? [];
        } catch (Exception) {
            return [];
        }

    }
}