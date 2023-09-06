<?php

declare(strict_types=1);

namespace App\Application\Actions\Score;

use Psr\Http\Message\ResponseInterface as Response;

class Create extends Store
{
    /**
     * @inheritDoc
     */
    protected function init(array $scoreData): \App\Domain\Score
    {
        /** @var \App\Domain\Score $score */
        $score = \App\Domain\Score::query()->make($scoreData);

        return $score;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\Score $score): Response
    {
        return $this->respondWithData([
            'message' => $this->translator->get('action.create.success'),
            'score' => [
                'id' => $score->id,
            ],
        ]);
    }
}
