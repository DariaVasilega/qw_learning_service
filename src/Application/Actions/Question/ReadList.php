<?php

declare(strict_types=1);

namespace App\Application\Actions\Question;

use App\Application\Actions\Question\Action;
use Psr\Http\Message\ResponseInterface as Response;

class ReadList extends Action
{
    /**
     * @var \App\Factory\SearchCriteriaFactory $criteriaFactory
     */
    private \App\Factory\SearchCriteriaFactory $criteriaFactory;

    public function __construct(
        \App\Infrastructure\Filesystem\Log\QuestionActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\QuestionRepositoryInterface $questionRepository,
        \App\Factory\SearchCriteriaFactory $criteriaFactory
    ) {
        parent::__construct(
            $logger,
            $translator,
            $questionRepository
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
            $searchResult = $this->questionRepository->getList(
                $this->criteriaFactory->create([
                    'model' => \App\Domain\Question::query()->make(),
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
