<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Persistence;

class ScoreRepository extends AbstractRepository implements
    \App\Domain\ScoreRepositoryInterface
{
    protected const SELECTION_KEY = 'scores';

    /**
     * @var \Illuminate\Database\Eloquent\Builder $queryBuilder
     */
    private \Illuminate\Database\Eloquent\Builder $queryBuilder;

    /**
     * @inheritDoc
     */
    protected function init(): void
    {
        /**
         * @phpmd-ignore-next-line
         */
        $this->queryBuilder = \App\Domain\Score::query();
    }

    /**
     * @inheritDoc
     */
    public function save(\App\Domain\Score $score): bool
    {
        try {
            return $score->save();
        } catch (\Exception $exception) {
            $resetIncrement = $this->queryBuilder->newQuery()->max('id') - 1;

            $fixIncrementQuery = <<<SQL
ALTER TABLE `{$score->getTable()}` AUTO_INCREMENT=$resetIncrement;
SQL;

            $this->queryBuilder->newQuery()->getConnection()->statement($fixIncrementQuery);

            throw new \App\Domain\DomainException\DomainRecordNotSavedException(
                'repository.error.not_saved',
                (int)$exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function get(int $scoreId): \App\Domain\Score
    {
        try {
            /**
             * @var \App\Domain\Score $score
             * @phpstan-ignore-next-line
             */
            $score = $this->queryBuilder->newQuery()->findOrFail($scoreId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotFoundException(
                'repository.error.not_found',
                (int)$exception->getCode(),
                $exception
            );
        }

        return $score;
    }

    /**
     * @inheritDoc
     */
    public function delete(\App\Domain\Score $score): bool
    {
        try {
            $score->delete();
        } catch (\LogicException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotRemovedException(
                'repository.error.not_removed',
                (int)$exception->getCode(),
                $exception
            );
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $scoreId): bool
    {
        $this->delete($this->get($scoreId));

        return true;
    }
}
