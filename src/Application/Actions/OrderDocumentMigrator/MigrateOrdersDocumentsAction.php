<?php

namespace App\Application\Actions\OrderDocumentMigrator;

use App\Application\Actions\Action;
use App\OrderDocumentMigrator\OrderDocumentMigrator;
use Psr\Http\Message\ResponseInterface as Response;

class MigrateOrdersDocumentsAction extends Action
{
    protected function action(): Response
    {
       OrderDocumentMigrator::migrateOrdersDocuments();

       return $this->respondWithData(['success' => true]);
    }

    public function importOrderDocuments()
    {
        $orderId = $this->getFormData();
        var_dump($orderId); die();
        OrderDocumentMigrator::migrateOrderDocuments();
    }
}