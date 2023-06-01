<?php

namespace App\OrderDocumentMigrator\Migrator;

use App\OrderDocumentMigrator\Contracts\RestClient;
use App\OrderDocumentMigrator\Receiver\ReceiverDocumentUpdater;
use App\OrderDocumentMigrator\Receiver\ReceiverDocumentsFetcher;
use App\OrderDocumentMigrator\Shared\ExecuteManager;
use App\OrderDocumentMigrator\Shared\Transfer;

class DocumentsEnhancer
{
    const PROP_LIMIT = 100;

    /**
     * @param ReceiverDocumentUpdater $documentUpdater
     * @param ReceiverDocumentsFetcher $documentsFetcher
     */
    public function __construct(protected ReceiverDocumentUpdater $documentUpdater, protected ReceiverDocumentsFetcher $documentsFetcher)
    {
    }

    public function enhanceMigratedDocuments(?ExecuteManager $executeManager): void
    {
        $executeManager = $executeManager ?? new ExecuteManager();
        $executeManager->progressBarStart();

        $filters = new Transfer(['limit' => self::PROP_LIMIT, 'page' => 1, 'total-count-mode' => 1]);
        $responseData = $this->documentsFetcher->getDocuments($filters)->getResponseData();
        $offset = 0;

        $executeManager->progressBarSetSteps($responseData->getTotal());
        sleep(1);
        $executeManager->progressBarSetStep(self::PROP_LIMIT * $filters->getParams()['page']);

        while ($offset < $responseData->getTotal()) {
            $filters = new Transfer([
                'limit' => self::PROP_LIMIT, 'page' => $filters->getParams()['page'] + 1, 'total-count-mode' => 1
            ]);
            $offset = self::PROP_LIMIT * $filters->getParams()['page'];

            $this->documentUpdater->updateDocumentConfig($responseData->getData());
            $executeManager->progressBarAdvance(self::PROP_LIMIT);


            $responseData = $this->documentsFetcher->getDocuments($filters)->getResponseData();
        }

        $executeManager->progressBarFinish();
    }

    public function enhanceMigratedDocumentById(string $documentId): void
    {
        $transfer = (new Transfer())->setParams(['documentId' => $documentId]);
        $this->documentsFetcher->getDocument($transfer);

        die(json_encode($transfer->getResponseData()?->getData()));
        $this->documentUpdater->updateDocumentConfig($transfer->getResponseData()?->getData() ?? []);
    }

}