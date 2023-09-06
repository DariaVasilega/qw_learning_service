<?php

declare(strict_types=1);

namespace App\Application\Actions\Answer;

use App\Application\Actions\Answer\Action;
use Psr\Http\Message\ResponseInterface as Response;

class Delete extends Action
{
    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     */
    protected function action(): Response
    {
        try {
            $this->answerRepository->deleteById((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData(['message' => $this->translator->get('action.delete.success')]);
    }
}
