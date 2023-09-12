<?php

declare(strict_types=1);

namespace App\Application\Actions\Score\Custom;

use Psr\Http\Message\ResponseInterface as Response;

class ChangeUserEmail extends \App\Application\Actions\Score\Action
{
    /**
     * @inheritDoc
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $body = $this->request->getParsedBody();
        $this->scoreRepository->updateScoresUser($body['old_email'], $body['new_email']);

        return $this->respondWithData(['message' => $this->translator->get('action.update.success')]);
    }
}
