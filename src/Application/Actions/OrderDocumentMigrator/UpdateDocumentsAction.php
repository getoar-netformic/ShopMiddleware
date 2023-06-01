<?php

namespace App\Application\Actions\OrderDocumentMigrator;

use App\Application\Actions\Action;
use App\OrderDocumentMigrator\OrderDocumentMigrator;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateDocumentsAction extends Action
{
    protected function action(): Response
    {
        OrderDocumentMigrator::enhanceAllMigratedDocuments(null);

        return $this->respondWithData(['success' => true]);
    }
}