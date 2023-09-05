<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Persistence;

class AnswerRepository extends \App\Infrastructure\Database\Persistence\AbstractRepository implements
    \App\Domain\AnswerRepositoryInterface
{
    protected const SELECTION_KEY = 'answers';

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
        $this->queryBuilder = \App\Domain\Answer::query();
    }

    /**
     * @inheritDoc
     */
    public function save(\App\Domain\Answer $answer): bool
    {
        try {
            return $answer->save();
        } catch (\Exception $exception) {
            $resetIncrement = $this->queryBuilder->newQuery()->max('id') - 1;

            $fixIncrementQuery = <<<SQL
ALTER TABLE `{$answer->getTable()}` AUTO_INCREMENT=$resetIncrement;
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
    public function get(int $answerId): \App\Domain\Answer
    {
        try {
            /**
             * @var \App\Domain\Answer $answer
             * @phpstan-ignore-next-line
             */
            $answer = $this->queryBuilder->newQuery()->findOrFail($answerId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotFoundException(
                'repository.error.not_found',
                (int) $exception->getCode(),
                $exception
            );
        }

        return $answer;
    }

    /**
     * @inheritDoc
     */
    public function delete(\App\Domain\Answer $answer): bool
    {
        try {
            $answer->delete();
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
    public function deleteById(int $answerId): bool
    {
        $this->delete($this->get($answerId));

        return true;
    }
}
