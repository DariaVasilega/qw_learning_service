<?php

declare(strict_types=1);

namespace App\Application\Actions\Lection;

use Psr\Http\Message\ResponseInterface as Response;

class LatestLectionStatistic extends ReadList
{
    protected function action(): Response
    {
        $request = \Unlu\Laravel\Api\RequestCreator::createWithParameters([
            'order_by' => 'id,desc',
            'limit' => '1',
            'includes' => 'test',
        ]);

        $searchResult = $this->lectionRepository->getList(
            $this->criteriaFactory->create([
                'model' => \App\Domain\Lection::query()->make(),
                'request' => $request
            ])
        );

        $lastLection = current($searchResult->getItems());

        $completedUsers = \App\Domain\Score::query()
            ->where(['test_id' => $lastLection?->test?->id])
            ->select(['email'])
            ->groupBy('email')
            ->get();

        return $this->respondWithData([
            'label' => $lastLection?->test?->label,
            'test' => $lastLection?->test?->id,
            'users' => $completedUsers,
        ]);
    }
}
