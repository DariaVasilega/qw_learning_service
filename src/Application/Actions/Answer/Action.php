<?php

declare(strict_types=1);

namespace App\Application\Actions\Answer;

abstract class Action extends \App\Application\Actions\Action
{
    /**
     * @var \App\Domain\AnswerRepositoryInterface $answerRepository
     */
    protected \App\Domain\AnswerRepositoryInterface $answerRepository;

    /**
     * @param \App\Infrastructure\Filesystem\Log\AnswerActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\AnswerRepositoryInterface $answerRepository
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\AnswerActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\AnswerRepositoryInterface $answerRepository
    ) {
        parent::__construct($logger, $translator);

        $this->answerRepository = $answerRepository;
    }
}
