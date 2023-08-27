<?php

declare(strict_types=1);

namespace App\Application\Actions\Lection;

use Psr\Http\Message\ResponseInterface as Response;

abstract class Store extends Action
{

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $lectionData = $this->getFormData();

        $lection = $this->init($lectionData);
        $this->save($lection);

        return $this->sendResponse($lection);
    }

    /**
     * Save lection
     *
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function save(\App\Domain\Lection $lection): void
    {
        try {
            $this->lectionRepository->save($lection);
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }
    }

    /**
     * Init lection
     *
     * @param array $lectionData
     * @return \App\Domain\Lection
     */
    abstract protected function init(array $lectionData): \App\Domain\Lection;

    /**
     * Send response
     *
     * @param \App\Domain\Lection $lection
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \JsonException
     */
    abstract protected function sendResponse(\App\Domain\Lection $lection): Response;
}
