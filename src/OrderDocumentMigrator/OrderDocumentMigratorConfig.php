<?php

namespace App\OrderDocumentMigrator;

interface OrderDocumentMigratorConfig
{
    public const EXPORTER_URL = '';
    public const EXPORTER_KEY = '';
    public const EXPORTER_SECRET = '';


    public const RECEIVER_URL = 'http://192.168.178.172:8080/api';
    public const RECEIVER_KEY = 'SWIAD0DXB3HUUMFWVUFJN3PRDA';
    public const RECEIVER_SECRET = 'MnBKUTJOaWVQeVFETUZ0WVZQYnBGZVJTYXlQVVh4Y2Z4WllOa3M';

    public const EXPORTER_ACCESS_GRANT = [
        'url' => self::EXPORTER_URL,
        'key' => self::EXPORTER_KEY,
        'secret' => self::EXPORTER_SECRET,
    ];

    public const RECEIVER_ACCESS_GRANT = [
        'url' => self::RECEIVER_URL,
        'key' => self::RECEIVER_KEY,
        'secret' => self::RECEIVER_SECRET,
    ];
}