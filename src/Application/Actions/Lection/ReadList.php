<?php

declare(strict_types=1);

namespace App\Application\Actions\Lection;

use Psr\Http\Message\ResponseInterface as Response;

class ReadList extends Action
{
    /**
     * @var \App\Factory\SearchCriteriaFactory $criteriaFactory
     */
    protected \App\Factory\SearchCriteriaFactory $criteriaFactory;

    public function __construct(
        \App\Infrastructure\Filesystem\Log\LectionActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\LectionRepositoryInterface $lectionRepository,
        \App\Factory\SearchCriteriaFactory $criteriaFactory
    ) {
        parent::__construct(
            $logger,
            $translator,
            $lectionRepository
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
            $searchResult = $this->lectionRepository->getList(
                $this->criteriaFactory->create([
                    'model' => \App\Domain\Lection::query()->make(),
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
