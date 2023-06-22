<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory as ValidationFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Capsule::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $capsule = new Capsule();
            $capsule->addConnection($settings->get('db'));
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        },
        Translator::class => function (ContainerInterface $c) {
            $loader = new FileLoader(new Filesystem(), dirname(__FILE__, 2) . '/lang');
            $loader->addNamespace('lang', dirname(__FILE__, 2) . '/lang');
            $loader->load('en_US', 'validation', 'lang');

            return new Translator($loader, 'en_US');
        },
        ValidationFactory::class => function (ContainerInterface $c) {
            $validationFactory = new ValidationFactory($c->get(Translator::class));

            /** @var Capsule $capsule */
            $capsule = $c->get(Capsule::class);
            $presenceVerifier = new DatabasePresenceVerifier($capsule->getDatabaseManager());

            $validationFactory->setPresenceVerifier($presenceVerifier);

            return $validationFactory;
        },
    ]);
};
