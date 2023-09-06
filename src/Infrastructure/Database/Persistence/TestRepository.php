<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Persistence;

class TestRepository extends AbstractRepository implements
    \App\Domain\TestRepositoryInterface
{
    protected const SELECTION_KEY = 'tests';

    /**
     * @var \Illuminate\Database\Eloquent\Builder $queryBuilder;
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
        $this->queryBuilder = \App\Domain\Test::query();
    }

    /**
     * @inheritDoc
     */
    public function save(\App\Domain\Test $test): bool
    {
        try {
            return $test->save();
        } catch (\Exception $exception) {
            $resetIncrement = $this->queryBuilder->newQuery()->max('id') - 1;

            $fixIncrementQuery = <<<SQL
ALTER TABLE `{$test->getTable()}` AUTO_INCREMENT=$resetIncrement;
SQL;

            $this->queryBuilder->newQuery()->getConnection()->statement($fixIncrementQuery);

            throw new \App\Domain\DomainException\DomainRecordNotSavedException(
                'repository.error.not_saved',
                (int) $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function get(int $testId): \App\Domain\Test
    {
        try {
            /**
             * @var \App\Domain\Test $test
             * @phpstan-ignore-next-line
             */
            $test = $this->queryBuilder->newQuery()->findOrFail($testId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotFoundException(
                'repository.error.not_found',
                (int) $exception->getCode(),
                $exception
            );
        }

        return $test;
    }

    /**
     * @inheritDoc
     */
    public function delete(\App\Domain\Test $test): bool
    {
        try {
            $test->delete();
        } catch (\LogicException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotRemovedException(
                'repository.error.not_removed',
                (int) $exception->getCode(),
                $exception
            );
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $testId): bool
    {
        $this->delete($this->get($testId));

        return true;
    }
}
