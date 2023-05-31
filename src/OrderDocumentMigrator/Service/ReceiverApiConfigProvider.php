<?php

namespace App\OrderDocumentMigrator\Service;

use App\OrderDocumentMigrator\Contracts\ApiConfigProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class ReceiverApiConfigProvider implements ApiConfigProvider
{
    protected mixed $authenticator = null;

    public function __construct(protected array $config)
    {
    }

    public function getUrl(): string
    {
        return $this->config['url'];
    }

    public function getToken(): string
    {
        return 'Bearer ' . $this->authenticate();
    }

    public function getAllConfigs(): array
    {
        return $this->config;
    }

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @return string
     */
    protected function authenticate(): string
    {
        if (!$this->isTokenValid()) {
            $url = sprintf('%s/%s', $this->config['url'], 'oauth/token');
            $request = new Request('post', $url, ['Content-Type' => 'application/json'], json_encode([
                "grant_type" => "client_credentials",
                "client_id" => $this->config['key'],
                "client_secret" => $this->config['secret']
            ]));

            $this->authenticator = json_decode((new Client())->sendRequest($request)->getBody()->getContents(), true);

            $this->authenticator['created_at'] = time() ;
            print_r('Token refreshed');
        }

        return $this->authenticator['access_token'];
    }

    /**
     * @return bool
     */
    private function isTokenValid(): bool
    {
        if ($this->authenticator) {
            $availableTill = $this->authenticator['expires_in'] + $this->authenticator['created_at'] - 2;

            return $availableTill > time();
        }

        return false;
    }
}