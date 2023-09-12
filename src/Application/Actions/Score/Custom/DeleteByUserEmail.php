<?php

declare(strict_types=1);

namespace App\Application\Actions\Score\Custom;

use Psr\Http\Message\ResponseInterface as Response;

class DeleteByUserEmail extends \App\Application\Actions\Score\Action
{
    /**
     * @inheritDoc
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $body = $this->request->getParsedBody();
        $this->scoreRepository->deleteUserScores($body['email']);

        return $this->respondWithData(['message' => $this->translator->get('action.delete.success')]);
    }
}
