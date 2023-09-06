<?php

declare(strict_types=1);

namespace App\Application\Actions\Score;

use Psr\Http\Message\ResponseInterface as Response;

abstract class Store extends Action
{
    /**
     * @var \App\Domain\Score\Validation\Rule $validationRule
     */
    protected \App\Domain\Score\Validation\Rule $validationRule;

    /**
     * @var \Illuminate\Validation\Factory $validatorFactory
     */
    protected \Illuminate\Validation\Factory $validatorFactory;

    /**
     * @param \App\Infrastructure\Filesystem\Log\ScoreActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\ScoreRepositoryInterface $scoreRepository
     * @param \App\Domain\Score\Validation\Rule $validationRule
     * @param \Illuminate\Validation\Factory $validatorFactory
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\ScoreActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\ScoreRepositoryInterface $scoreRepository,
        \App\Domain\Score\Validation\Rule $validationRule,
        \Illuminate\Validation\Factory $validatorFactory
    ) {
        parent::__construct($logger, $translator, $scoreRepository);

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
        $scoreData = $this->getFormData();

        $this->validate($scoreData);
        $score = $this->init($scoreData);
        $this->save($score);

        return $this->sendResponse($score);
    }

    /**
     * Validate score data before save
     *
     * @param array $scoreData
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \JsonException
     */
    protected function validate(array $scoreData): bool
    {
        $validator = $this->validatorFactory->make(
            $scoreData,
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
     * Save score
     *
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function save(\App\Domain\Score $score): void
    {
        try {
            $this->scoreRepository->save($score);
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }
    }

    /**
     * Init score
     *
     * @param array $scoreData
     * @return \App\Domain\Score
     */
    abstract protected function init(array $scoreData): \App\Domain\Score;

    /**
     * Send response
     *
     * @param \App\Domain\Score $score
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JsonException
     */
    abstract protected function sendResponse(\App\Domain\Score $score): Response;
}
