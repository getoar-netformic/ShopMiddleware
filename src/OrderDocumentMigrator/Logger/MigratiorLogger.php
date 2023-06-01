<?php

namespace App\OrderDocumentMigrator\Logger;

use Monolog\Logger;
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
        $this->monoLog = new MonoLogger('Logger');
        $this->monoLog->pushHandler(new StreamHandler(__DIR__ . '/./migrations_cancellation.log', Logger::INFO));
        $this->monoLog->pushHandler(new StreamHandler(__DIR__ . '/./migrations_cancellation_errors.log', Logger::ERROR));
    }

    public static function writer(): MigratiorLogger
    {
        return self::$instance ?? self::$instance = new self();
    }

    public function log(mixed $message, $method = 'error'): void
    {
        $this->monoLog->{$method}($message);
    }
}