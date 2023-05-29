<?php

namespace App\Console;

use App\OrderDocumentMigrator\OrderDocumentMigrator;
use App\OrderDocumentMigrator\Shared\ExecuteManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateDocumentsCommand extends Command
{
    /**
     * Configure.
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName('migrate:documents');
        $this->setDescription('Migrates documents from old system to new');
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input The input
     * @param OutputInterface $output The output
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return int The error code, 0 on success
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        OrderDocumentMigrator::migrateOrdersDocuments((new ExecuteManager())->setProgressBar(new ProgressBar($output)));

        return 0;
    }
}