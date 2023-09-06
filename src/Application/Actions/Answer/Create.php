<?php

declare(strict_types=1);

namespace App\Application\Actions\Answer;

use App\Application\Actions\Answer\Store;
use Psr\Http\Message\ResponseInterface as Response;

class Create extends Store
{
    /**
     * @inheritDoc
     */
    protected function init(array $answerData): \App\Domain\Answer
    {
        /** @var \App\Domain\Answer $answer */
        $answer = \App\Domain\Answer::query()->make($answerData);

        return $answer;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\Answer $answer): Response
    {
        return $this->respondWithData([
            'message' => $this->translator->get('action.create.success'),
            'answer' => [
                'id' => $answer->id,
            ],
        ]);
    }
}
