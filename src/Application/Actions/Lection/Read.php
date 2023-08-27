<?php

declare(strict_types=1);

namespace App\Application\Actions\Lection;

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
            $lection = $this->lectionRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData(['lection' => $lection]);
    }
}
