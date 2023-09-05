<?php

declare(strict_types=1);

namespace App\Application\Actions\Question;

abstract class Action extends \App\Application\Actions\Action
{
    /**
     * @var \App\Domain\QuestionRepositoryInterface $questionRepository
     */
    protected \App\Domain\QuestionRepositoryInterface $questionRepository;

    /**
     * @param \App\Infrastructure\Filesystem\Log\QuestionActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\QuestionRepositoryInterface $questionRepository
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\QuestionActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\QuestionRepositoryInterface $questionRepository
    ) {
        parent::__construct($logger, $translator);

        $this->questionRepository = $questionRepository;
    }
}
