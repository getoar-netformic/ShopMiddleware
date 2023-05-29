<?php

namespace App\OrderDocumentMigrator\Service;

use App\OrderDocumentMigrator\Contracts\ApiConfigProvider;
use App\OrderDocumentMigrator\Contracts\RestClient;
use App\OrderDocumentMigrator\Contracts\RestResponseParser;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class RestApiClient implements RestClient
{
    protected const METHOD_GET = 'get';
    protected const METHOD_POST = 'post';

    /**
     * @var \GuzzleHttp\Client
     */
    protected Client $restClient;


    /**
     * @param \App\OrderDocumentMigrator\Contracts\ApiConfigProvider $configProvider
     */
    public function __construct(protected ApiConfigProvider $configProvider)
    {
        $this->restClient = new Client();
    }

    /**
     * @param string $path
     * @param array $params
     *
     * @return \App\OrderDocumentMigrator\Service\ApiResponseParser
     */
    public function get(string $path, array $params = []): ApiResponseParser
    {
        $request = $this->createShopwareApiRequest(self::METHOD_GET, $path . '?' . http_build_query($params));

        return new ApiResponseParser($this->restClient->sendAsync($request)->wait());
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param array $headers
     *
     * @return \App\OrderDocumentMigrator\Contracts\RestResponseParser
     */
    public function post(string $path, mixed $body = '', array $headers = []): RestResponseParser
    {
        $request = $this->createShopwareApiRequest(self::METHOD_POST, $path, $body, $headers);

        return new ApiResponseParser($this->restClient->sendAsync($request)->wait());
    }

    /**
     * @param string $method
     * @param string $path
     * @param string|null $body
     * @param array $headers
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function createShopwareApiRequest(
        string $method,
        string $path,
        mixed $body = null,
        array $headers = []
    ): RequestInterface {
        return new Request($method, $this->configProvider->getUrl() . $path,
            array_merge([
                'Authorization' => $this->configProvider->getToken(),
                'Accept' => 'application/json',
            ], $headers),
            $body ?? ''
        );
    }
}