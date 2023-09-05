<?php

declare(strict_types=1);

namespace App\Application\Actions\Question;

use App\Application\Actions\Question\Store;
use Psr\Http\Message\ResponseInterface as Response;

class Update extends Store
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $questionData = $this->getFormData();
        $question = $this->init($questionData);
        $questionData['id'] = $question->id;

        $this->validate($questionData);
        $this->save($question->fill($questionData));

        return $this->sendResponse($question);
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function init(array $questionData): \App\Domain\Question
    {
        try {
            $question = $this->questionRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $question;
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
        $allValidationRules = $this->validationRule->getRules();
        $suitableRules = array_intersect_key($allValidationRules, $questionData);

        $validator = $this->validatorFactory->make(
            $questionData,
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
    protected function sendResponse(\App\Domain\Question $question): Response
    {
        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
