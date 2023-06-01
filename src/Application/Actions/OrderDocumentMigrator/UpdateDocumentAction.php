<?php

namespace App\Application\Actions\OrderDocumentMigrator;

use App\Application\Actions\Action;
use App\OrderDocumentMigrator\OrderDocumentMigrator;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateDocumentAction extends Action
{

    protected function action(): Response
    {
        $documentId = $this->getFormData()['documentId'];
        OrderDocumentMigrator::enhanceMigratedDocumentById($documentId);

        return $this->respondWithData(['success' => true]);
    }
}