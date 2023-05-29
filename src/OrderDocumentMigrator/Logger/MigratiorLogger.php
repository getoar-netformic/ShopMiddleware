<?php

namespace App\OrderDocumentMigrator\Logger;

use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;

class MigratiorLogger
{
    private static MigratiorLogger $instance;
    /**
     * @var \Monolog\Logger
     */
    private MonoLogger $monoLog;

    private function __construct()
    {
        $this->monoLog = new MonoLogger('name');
        $this->monoLog->pushHandler(new StreamHandler(__DIR__.'/./migrations.log', 200));
    }

    public static function writer(): MigratiorLogger
    {
        return self::$instance ?? self::$instance = new self();
    }

    public function log(mixed $message, $method = 'info'): void
    {
        $this->monoLog->{$method}($message);
    }
}