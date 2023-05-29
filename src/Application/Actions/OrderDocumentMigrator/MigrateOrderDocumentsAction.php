<?php

namespace App\Application\Actions\OrderDocumentMigrator;

use App\Application\Actions\Action;
use App\OrderDocumentMigrator\OrderDocumentMigrator;
use Psr\Http\Message\ResponseInterface as Response;

class MigrateOrderDocumentsAction extends Action
{
    protected function action(): Response
    {
        $orderId = $this->getFormData()['orderId'];
        OrderDocumentMigrator::migrateOrderDocuments($orderId);

        return $this->respondWithData(['success' => true]);
    }
}