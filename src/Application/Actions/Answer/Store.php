<?php

declare(strict_types=1);

namespace App\Application\Actions\Answer;

use App\Application\Actions\Answer\Action;
use Psr\Http\Message\ResponseInterface as Response;

abstract class Store extends Action
{
    /**
     * @var \App\Domain\Answer\Validation\Rule $validationRule
     */
    protected \App\Domain\Answer\Validation\Rule $validationRule;

    /**
     * @var \Illuminate\Validation\Factory $validatorFactory
     */
    protected \Illuminate\Validation\Factory $validatorFactory;

    /**
     * @param \App\Infrastructure\Filesystem\Log\AnswerActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\AnswerRepositoryInterface $answerRepository
     * @param \App\Domain\Answer\Validation\Rule $validationRule
     * @param \Illuminate\Validation\Factory $validatorFactory
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\AnswerActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\AnswerRepositoryInterface $answerRepository,
        \App\Domain\Answer\Validation\Rule $validationRule,
        \Illuminate\Validation\Factory $validatorFactory
    ) {
        parent::__construct($logger, $translator, $answerRepository);

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
        $answerData = $this->getFormData();

        $this->validate($answerData);
        $answer = $this->init($answerData);
        $this->save($answer);

        return $this->sendResponse($answer);
    }

    /**
     * Validate answer data before save
     *
     * @param array $answerData
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \JsonException
     */
    protected function validate(array $answerData): bool
    {
        $validator = $this->validatorFactory->make(
            $answerData,
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
     * Save answer
     *
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function save(\App\Domain\Answer $answer): void
    {
        try {
            $this->answerRepository->save($answer);
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }
    }

    /**
     * Init answer
     *
     * @param array $answerData
     * @return \App\Domain\Answer
     */
    abstract protected function init(array $answerData): \App\Domain\Answer;

    /**
     * Send response
     *
     * @param \App\Domain\Answer $answer
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JsonException
     */
    abstract protected function sendResponse(\App\Domain\Answer $answer): Response;
}
