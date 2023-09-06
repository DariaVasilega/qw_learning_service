<?php

declare(strict_types=1);

namespace App\Application\Actions\Test;

use Psr\Http\Message\ResponseInterface as Response;

class Create extends Store
{
    /**
     * @inheritDoc
     */
    protected function init(array $testData): \App\Domain\Test
    {
        /** @var \App\Domain\Test $test */
        $test = \App\Domain\Test::query()->make($testData);

        return $test;
    }

    /**
     * @inheritDoc
     */
    protected function sendResponse(\App\Domain\Test $test): Response
    {
        return $this->respondWithData([
            'message' => $this->translator->get('action.create.success'),
            'test' => [
                'id' => $test->id,
            ],
        ]);
    }
}
