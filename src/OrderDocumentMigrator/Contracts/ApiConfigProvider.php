<?php

namespace App\OrderDocumentMigrator\Contracts;

interface ApiConfigProvider
{
    public function getUrl(): string;

    public function getToken(): string;
    public function getAllConfigs(): array;
}