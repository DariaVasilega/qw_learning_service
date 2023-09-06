<?php

declare(strict_types=1);

namespace App\Application\Actions\Score;

use Psr\Http\Message\ResponseInterface as Response;

class Update extends Store
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $scoreData = $this->getFormData();
        $score = $this->init($scoreData);
        $scoreData['id'] = $score->id;

        $this->validate($scoreData);
        $this->save($score->fill($scoreData));

        return $this->sendResponse($score);
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function init(array $scoreData): \App\Domain\Score
    {
        try {
            $score = $this->scoreRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $score;
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
        $allValidationRules = $this->validationRule->getRules();
        $suitableRules = array_intersect_key($allValidationRules, $scoreData);

        $validator = $this->validatorFactory->make(
            $scoreData,
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
    protected function sendResponse(\App\Domain\Score $score): Response
    {
        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
