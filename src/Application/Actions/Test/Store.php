<?php

declare(strict_types=1);

namespace App\Application\Actions\Test;

use Psr\Http\Message\ResponseInterface as Response;

abstract class Store extends Action
{
    /**
     * @var \App\Domain\Test\Validation\Rule $validationRule
     */
    protected \App\Domain\Test\Validation\Rule $validationRule;

    /**
     * @var \Illuminate\Validation\Factory $validatorFactory
     */
    protected \Illuminate\Validation\Factory $validatorFactory;

    /**
     * @param \App\Infrastructure\Filesystem\Log\TestActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\TestRepositoryInterface $testRepository
     * @param \App\Domain\Test\Validation\Rule $validationRule
     * @param \Illuminate\Validation\Factory $validatorFactory
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\TestActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\TestRepositoryInterface $testRepository,
        \App\Domain\Test\Validation\Rule $validationRule,
        \Illuminate\Validation\Factory $validatorFactory
    ) {
        parent::__construct($logger, $translator, $testRepository);

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
        $testData = $this->getFormData();

        $this->validate($testData);
        $test = $this->init($testData);
        $this->save($test);

        return $this->sendResponse($test);
    }

    /**
     * Validate test data before save
     *
     * @param array $testData
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \JsonException
     */
    protected function validate(array $testData): bool
    {
        $validator = $this->validatorFactory->make(
            $testData,
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
     * Save test
     *
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function save(\App\Domain\Test $test): void
    {
        try {
            $this->testRepository->save($test);
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }
    }

    /**
     * Init test
     *
     * @param array $testData
     * @return \App\Domain\Test
     */
    abstract protected function init(array $testData): \App\Domain\Test;

    /**
     * Send response
     *
     * @param \App\Domain\Test $test
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JsonException
     */
    abstract protected function sendResponse(\App\Domain\Test $test): Response;
}
