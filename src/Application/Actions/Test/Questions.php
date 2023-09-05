<?php

declare(strict_types=1);

namespace App\Application\Actions\Test;

use Psr\Http\Message\ResponseInterface as Response;

class Questions extends Action
{
    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     */
    protected function action(): Response
    {
        try {
            $test = $this->testRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData(['questions' => $test->questions]);
    }
}
