<?php

declare(strict_types=1);

namespace App\Application\Actions\Question;

use Psr\Http\Message\ResponseInterface as Response;

class Answers extends Action
{
    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     */
    protected function action(): Response
    {
        try {
            $question = $this->questionRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData(['answers' => $question->answers]);
    }
}
