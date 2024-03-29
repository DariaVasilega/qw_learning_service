<?php

declare(strict_types=1);

use App\Application\Directory\LocaleInterface;
use App\Application\Settings\SettingsInterface;
use App\Infrastructure\Filesystem\Log\AnswerActionLogger;
use App\Infrastructure\Filesystem\Log\LectionActionLogger;
use App\Infrastructure\Filesystem\Log\QuestionActionLogger;
use App\Infrastructure\Filesystem\Log\ScoreActionLogger;
use App\Infrastructure\Filesystem\Log\TestActionLogger;
use DI\ContainerBuilder;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory as ValidationFactory;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\PathPrefixer;
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
            $illuminateContainer = $c->get(IlluminateContainer::class);
            $settings = $c->get(SettingsInterface::class);

            $capsule = new Capsule($illuminateContainer);
            $capsule->addConnection($settings->get('db'));
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        },
        Translator::class => function (ContainerInterface $c) {
            /** @var LocaleInterface $locale */
            $locale = $c->get(LocaleInterface::class);
            $localeCode = $locale->getCurrentLocale();
            $settings = $c->get(SettingsInterface::class);
            $translationsPath = $settings->get('translationsPath');

            $loader = new FileLoader(new Filesystem(), $translationsPath);
            $loader->addNamespace('i18n', $translationsPath);
            $loader->load($localeCode, 'global', 'i18n');

            return new Translator($loader, $localeCode);
        },
        ValidationFactory::class => function (ContainerInterface $c) {
            $validationFactory = new ValidationFactory($c->get(Translator::class));

            /** @var Capsule $capsule */
            $capsule = $c->get(Capsule::class);
            $presenceVerifier = new DatabasePresenceVerifier($capsule->getDatabaseManager());

            $validationFactory->setPresenceVerifier($presenceVerifier);

            return $validationFactory;
        },
        FilesystemAdapter::class => function (ContainerInterface $c) {
            return new \League\Flysystem\Local\LocalFilesystemAdapter(dirname(__DIR__));
        },
        PathPrefixer::class => function (ContainerInterface $c) {
            return new PathPrefixer(dirname(__DIR__));
        },
        IlluminateContainer::class => function (ContainerInterface $c) {
            $illuminateContainer = new IlluminateContainer();
            $illuminateContainer->alias(EventsDispatcher::class, 'events');

            return $illuminateContainer;
        },
        LectionActionLogger::class => function (ContainerInterface $c) {
            $logger = $c->get(LoggerInterface::class);
            $logFile = isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/lection_action.log';
            $handler = new StreamHandler($logFile, Logger::ERROR);

            $userActionsLogger = $logger->withName('lection-action');
            $userActionsLogger->setHandlers([$handler]);

            return new LectionActionLogger($userActionsLogger);
        },
        TestActionLogger::class => function (ContainerInterface $c) {
            $logger = $c->get(LoggerInterface::class);
            $logFile = isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/test_action.log';
            $handler = new StreamHandler($logFile, Logger::ERROR);

            $userActionsLogger = $logger->withName('test-action');
            $userActionsLogger->setHandlers([$handler]);

            return new TestActionLogger($userActionsLogger);
        },
        QuestionActionLogger::class => function (ContainerInterface $c) {
            $logger = $c->get(LoggerInterface::class);
            $logFile = isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/question_action.log';
            $handler = new StreamHandler($logFile, Logger::ERROR);

            $userActionsLogger = $logger->withName('question-action');
            $userActionsLogger->setHandlers([$handler]);

            return new QuestionActionLogger($userActionsLogger);
        },
        AnswerActionLogger::class => function (ContainerInterface $c) {
            $logger = $c->get(LoggerInterface::class);
            $logFile = isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/answer_action.log';
            $handler = new StreamHandler($logFile, Logger::ERROR);

            $userActionsLogger = $logger->withName('answer-action');
            $userActionsLogger->setHandlers([$handler]);

            return new AnswerActionLogger($userActionsLogger);
        },
        ScoreActionLogger::class => function (ContainerInterface $c) {
            $logger = $c->get(LoggerInterface::class);
            $logFile = isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/score_action.log';
            $handler = new StreamHandler($logFile, Logger::ERROR);

            $userActionsLogger = $logger->withName('score-action');
            $userActionsLogger->setHandlers([$handler]);

            return new ScoreActionLogger($userActionsLogger);
        },
    ]);
};
