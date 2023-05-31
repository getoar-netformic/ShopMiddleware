<?php

namespace App\OrderDocumentMigrator;

interface OrderDocumentMigratorConfig
{



//    public const RECEIVER_URL = 'http://192.168.178.172:8080/api';
//    public const RECEIVER_KEY = 'SWIAYXH5MEFVN0S4TVRYVNBRZA';
//    public const RECEIVER_SECRET = 'd2JTZHZsdTZmbXh1MVp0c1UwU1lNWGtvc1BUQVptU2NrSHVNV0Y';



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