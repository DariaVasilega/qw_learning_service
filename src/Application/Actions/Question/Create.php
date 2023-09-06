<?php

declare(strict_types=1);

namespace App\Application\Actions\Question;

use App\Application\Actions\Question\Store;
use Psr\Http\Message\ResponseInterface as Response;

class Create extends Store
{
    /**
     * @inheritDoc
     */
    protected function init(array $questionData): \App\Domain\Question
    {
        /** @var \App\Domain\Question $question */
        $question = \App\Domain\Question::query()->make($questionData);

        return $question;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\Question $question): Response
    {
        return $this->respondWithData([
            'message' => $this->translator->get('action.create.success'),
            'question' => [
                'id' => $question->id,
            ],
        ]);
    }
}
