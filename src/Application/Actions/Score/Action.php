<?php

declare(strict_types=1);

namespace App\Application\Actions\Score;

abstract class Action extends \App\Application\Actions\Action
{
    /**
     * @var \App\Domain\ScoreRepositoryInterface $scoreRepository
     */
    protected \App\Domain\ScoreRepositoryInterface $scoreRepository;

    /**
     * @param \App\Infrastructure\Filesystem\Log\ScoreActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\ScoreRepositoryInterface $scoreRepository
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\ScoreActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\ScoreRepositoryInterface $scoreRepository
    ) {
        parent::__construct($logger, $translator);

        $this->scoreRepository = $scoreRepository;
    }
}
