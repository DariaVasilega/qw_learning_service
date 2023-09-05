<?php

declare(strict_types=1);

use App\Application\Directory\Locale;
use App\Application\Directory\LocaleInterface;
use App\Application\SearchCriteriaInterface;
use App\Application\SearchResultInterface;
use App\Application\SearchResultPageInterface;
use App\Domain\AnswerRepositoryInterface;
use App\Domain\LectionRepositoryInterface;
use App\Domain\QuestionRepositoryInterface;
use App\Domain\TestRepositoryInterface;
use App\Infrastructure\Database\Persistence\AnswerRepository;
use App\Infrastructure\Database\Persistence\LectionRepository;
use App\Infrastructure\Database\Persistence\QuestionRepository;
use App\Infrastructure\Database\Persistence\TestRepository;
use App\Infrastructure\Database\Query\SearchCriteria;
use App\Infrastructure\SearchResult;
use App\Infrastructure\SearchResultPage;
use DI\ContainerBuilder;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LocaleInterface::class => autowire(Locale::class),
        SearchCriteriaInterface::class => autowire(SearchCriteria::class),
        SearchResultInterface::class => autowire(SearchResult::class),
        SearchResultPageInterface::class => autowire(SearchResultPage::class),
        LectionRepositoryInterface::class => autowire(LectionRepository::class),
        TestRepositoryInterface::class => autowire(TestRepository::class),
        QuestionRepositoryInterface::class => autowire(QuestionRepository::class),
        AnswerRepositoryInterface::class => autowire(AnswerRepository::class),
    ]);
};
