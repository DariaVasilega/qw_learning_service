<?php

declare(strict_types=1);

namespace App\Application\Actions\Answer;

use App\Application\Actions\Answer\Store;
use Psr\Http\Message\ResponseInterface as Response;

class Update extends Store
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $answerData = $this->getFormData();
        $answer = $this->init($answerData);
        $answerData['id'] = $answer->id;

        $this->validate($answerData);
        $this->save($answer->fill($answerData));

        return $this->sendResponse($answer);
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function init(array $answerData): \App\Domain\Answer
    {
        try {
            $answer = $this->answerRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $answer;
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
        $allValidationRules = $this->validationRule->getRules();
        $suitableRules = array_intersect_key($allValidationRules, $answerData);

        $validator = $this->validatorFactory->make(
            $answerData,
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
    protected function sendResponse(\App\Domain\Answer $answer): Response
    {
        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
