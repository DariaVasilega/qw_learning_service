<?php

declare(strict_types=1);

namespace App\Application\Actions\Lection;

use Psr\Http\Message\ResponseInterface as Response;

class Create extends Store
{
    /**
     * @inheritDoc
     */
    protected function init(array $lectionData): \App\Domain\Lection
    {
        /** @var \App\Domain\Lection $lection */
        $lection = \App\Domain\Lection::query()->make($lectionData);

        return $lection;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\Lection $lection): Response
    {
        return $this->respondWithData([
            'message' => $this->translator->get('action.create.success'),
            'lection' => [
                'id' => $lection->id,
            ],
        ]);
    }
}
