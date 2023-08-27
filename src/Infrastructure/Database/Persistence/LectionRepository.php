<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Persistence;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class LectionRepository extends \App\Infrastructure\Database\Persistence\AbstractRepository implements
    \App\Domain\LectionRepositoryInterface
{
    protected const SELECTION_KEY = 'lections';

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
        $this->queryBuilder = \App\Domain\Lection::query();
    }

    /**
     * @inheritDoc
     */
    public function save(\App\Domain\Lection $lection): bool
    {
        try {
            return $lection->save();
        } catch (\Exception $exception) {
            $resetIncrement = $this->queryBuilder->newQuery()->max('id') - 1;

            $fixIncrementQuery = <<<SQL
ALTER TABLE `{$lection->getTable()}` AUTO_INCREMENT=$resetIncrement;
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
    public function get(int $lectionId): \App\Domain\Lection
    {
        try {
            /**
             * @var \App\Domain\Lection $lection
             * @phpstan-ignore-next-line
             */
            $lection = $this->queryBuilder->newQuery()->findOrFail($lectionId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotFoundException(
                'repository.error.not_found',
                (int) $exception->getCode(),
                $exception
            );
        }

        return $lection;
    }

    /**
     * @inheritDoc
     */
    public function delete(\App\Domain\Lection $lection): bool
    {
        try {
            $lection->delete();
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
    public function deleteById(int $lectionId): bool
    {
        $this->delete($this->get($lectionId));

        return true;
    }
}
