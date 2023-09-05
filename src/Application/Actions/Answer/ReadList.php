<?php

declare(strict_types=1);

namespace App\Application\Actions\Answer;

use App\Application\Actions\Answer\Action;
use Psr\Http\Message\ResponseInterface as Response;

class ReadList extends Action
{
    /**
     * @var \App\Factory\SearchCriteriaFactory $criteriaFactory
     */
    private \App\Factory\SearchCriteriaFactory $criteriaFactory;

    public function __construct(
        \App\Infrastructure\Filesystem\Log\AnswerActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\AnswerRepositoryInterface $answerRepository,
        \App\Factory\SearchCriteriaFactory $criteriaFactory
    ) {
        parent::__construct(
            $logger,
            $translator,
            $answerRepository
        );

        $this->criteriaFactory = $criteriaFactory;
    }

    /**
     * @inheritDoc
     * @throws \App\Domain\DomainException\DomainException
     * @throws \JsonException
     * @throws \Exception
     */
    protected function action(): Response
    {
        try {
            $searchResult = $this->answerRepository->getList(
                $this->criteriaFactory->create([
                    'model' => \App\Domain\Answer::query()->make(),
                    'request' => \Illuminate\Http\Request::capture()
                ])
            );
        } catch (\App\Domain\DomainException\DomainException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->respondWithData($searchResult);
    }
}
