<?php

declare(strict_types=1);

namespace App\Application\Actions\Test;

use Psr\Http\Message\ResponseInterface as Response;

class Update extends Store
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $testData = $this->getFormData();
        $test = $this->init($testData);
        $testData['id'] = $test->id;

        $this->validate($testData);
        $this->save($test->fill($testData));

        return $this->sendResponse($test);
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function init(array $testData): \App\Domain\Test
    {
        try {
            $test = $this->testRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $test;
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
        $allValidationRules = $this->validationRule->getRules();
        $suitableRules = array_intersect_key($allValidationRules, $testData);

        $validator = $this->validatorFactory->make(
            $testData,
            $suitableRules,
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
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\Test $test): Response
    {
        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
