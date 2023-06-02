<?php

use App\Console\EnhanceDocumentsCommand;
use App\Console\MigrateDocumentsCommand;

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

function public_path() {
    return __DIR__.'/../public';
}

$application = new Application();

$application->add(new EnhanceDocumentsCommand());
$application->add(new MigrateDocumentsCommand());

$application->run();
