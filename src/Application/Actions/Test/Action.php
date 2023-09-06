<?php

declare(strict_types=1);

namespace App\Application\Actions\Test;

abstract class Action extends \App\Application\Actions\Action
{
    /**
     * @var \App\Domain\TestRepositoryInterface $testRepository
     */
    protected \App\Domain\TestRepositoryInterface $testRepository;

    /**
     * @param \App\Infrastructure\Filesystem\Log\TestActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\TestRepositoryInterface $testRepository
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\TestActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\TestRepositoryInterface $testRepository
    ) {
        parent::__construct($logger, $translator);

        $this->testRepository = $testRepository;
    }
}
