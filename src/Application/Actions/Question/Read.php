<?php

declare(strict_types=1);

namespace App\Application\Actions\Question;

use App\Application\Actions\Question\Action;
use Psr\Http\Message\ResponseInterface as Response;

class Read extends Action
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

        return $this->respondWithData(['question' => $question]);
    }
}
