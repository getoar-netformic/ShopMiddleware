<?php

namespace App\OrderDocumentMigrator\Service;

use App\OrderDocumentMigrator\Contracts\ApiConfigProvider;

class ExporterApiConfigProvider implements ApiConfigProvider
{
    public function __construct(protected array $config)
    {
    }

    public function getUrl(): string
    {
        return $this->config['url'];
    }

    public function getToken(): string
    {
        return 'Basic ' . base64_encode($this->config['key'] . ':' . $this->config['secret']);
    }

    public function getAllConfigs(): array
    {
        return $this->config;
    }
}