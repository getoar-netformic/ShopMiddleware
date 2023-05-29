<?php

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Application::class => function (ContainerInterface $c) {
            $application = new Application();

            $application->getDefinition()->addOption(
                new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'development')
            );

            foreach ($c->get('settings')['commands'] as $class) {
                $application->add($c->get($class));
            }

            return $application;
        },
    ]);
};