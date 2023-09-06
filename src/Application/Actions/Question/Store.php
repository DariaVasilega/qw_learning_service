<?php

declare(strict_types=1);

namespace App\Application\Actions\Question;

use App\Application\Actions\Question\Action;
use Psr\Http\Message\ResponseInterface as Response;

abstract class Store extends Action
{
    /**
     * @var \App\Domain\Question\Validation\Rule $validationRule
     */
    protected \App\Domain\Question\Validation\Rule $validationRule;

    /**
     * @var \Illuminate\Validation\Factory $validatorFactory
     */
    protected \Illuminate\Validation\Factory $validatorFactory;

    /**
     * @param \App\Infrastructure\Filesystem\Log\QuestionActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\QuestionRepositoryInterface $questionRepository
     * @param \App\Domain\Question\Validation\Rule $validationRule
     * @param \Illuminate\Validation\Factory $validatorFactory
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\QuestionActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\QuestionRepositoryInterface $questionRepository,
        \App\Domain\Question\Validation\Rule $validationRule,
        \Illuminate\Validation\Factory $validatorFactory
    ) {
        parent::__construct($logger, $translator, $questionRepository);

        $this->validationRule = $validationRule;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $questionData = $this->getFormData();

        $this->validate($questionData);
        $question = $this->init($questionData);
        $this->save($question);

        return $this->sendResponse($question);
    }

    /**
     * Validate question data before save
     *
     * @param array $questionData
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \JsonException
     */
    protected function validate(array $questionData): bool
    {
        $validator = $this->validatorFactory->make(
            $questionData,
            $this->validationRule->getRules(),
            $this->validationRule->getMessages()
        );

        if ($validator->fails()) {
            throw new \App\Domain\DomainException\DomainRecordNotSavedException(
                json_encode($validator->getMessageBag(), JSON_THROW_ON_ERROR)
            );
        }

        return true;
    }

    /**
     * Save question
     *
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function save(\App\Domain\Question $question): void
    {
        try {
            $this->questionRepository->save($question);
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }
    }

    /**
     * Init question
     *
     * @param array $questionData
     * @return \App\Domain\Question
     */
    abstract protected function init(array $questionData): \App\Domain\Question;

    /**
     * Send response
     *
     * @param \App\Domain\Question $question
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JsonException
     */
    abstract protected function sendResponse(\App\Domain\Question $question): Response;
}
