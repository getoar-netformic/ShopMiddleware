<?php

namespace App\OrderDocumentMigrator\Contracts;

interface RestClient
{
    /**
     * @param string $path
     * @param array $params
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \App\OrderDocumentMigrator\Contracts\RestResponseParser
     */
    public function get(string $path, array $params = []): RestResponseParser;

    /**
     * @param string $path
     * @param array $body
     * @param array $headers
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \App\OrderDocumentMigrator\Contracts\RestResponseParser
     */
    public function post(string $path, array $body = [], array $headers = []): RestResponseParser;

    /**
     * @param string $path
     * @param array $body
     * @param array $headers
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \App\OrderDocumentMigrator\Contracts\RestResponseParser
     */
    public function patch(string $path, array $body, array $headers = []): RestResponseParser;
}