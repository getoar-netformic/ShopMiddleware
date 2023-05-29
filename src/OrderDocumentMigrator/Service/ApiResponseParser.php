<?php

namespace App\OrderDocumentMigrator\Service;

use App\OrderDocumentMigrator\Contracts\RestResponseParser;
use App\OrderDocumentMigrator\Shared\RestDataCollection;
use Psr\Http\Message\ResponseInterface;


class ApiResponseParser implements RestResponseParser
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected ResponseInterface $response;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return \App\OrderDocumentMigrator\Shared\RestDataCollection
     */
    public function getResponseData(): RestDataCollection
    {
        $data = $this->getResponseContent();

        return new RestDataCollection(isset($data['data']) ? $data : ['data' => $data]);
    }

    /**
     * @return array
     */
    protected function getResponseContent(): array
    {
        try {
            $response = $this->response->getBody()->getContents();

            return json_decode($response, true);
        } catch (\Exception $e) {
            return [];
        }
    }
}