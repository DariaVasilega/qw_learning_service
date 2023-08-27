<?php

declare(strict_types=1);

namespace App\Application\Actions\Lection;

use Psr\Http\Message\ResponseInterface as Response;

class Update extends Store
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $lectionData = $this->getFormData();
        $lection = $this->init($lectionData);
        $lectionData['id'] = $lection->id;

        $this->save($lection->fill($lectionData));

        return $this->sendResponse($lection);
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     */
    protected function init(array $lectionData): \App\Domain\Lection
    {
        try {
            $lection = $this->lectionRepository->get((int) $this->resolveArg('id'));
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $lection;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\Lection $lection): Response
    {
        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
