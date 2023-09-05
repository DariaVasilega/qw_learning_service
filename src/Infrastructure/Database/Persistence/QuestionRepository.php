<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Persistence;

class QuestionRepository extends AbstractRepository implements
    \App\Domain\QuestionRepositoryInterface
{
    protected const SELECTION_KEY = 'questions';

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
        $this->queryBuilder = \App\Domain\Question::query();
    }

    /**
     * @inheritDoc
     */
    public function save(\App\Domain\Question $question): bool
    {
        try {
            return $question->save();
        } catch (\Exception $exception) {
            $resetIncrement = $this->queryBuilder->newQuery()->max('id') - 1;

            $fixIncrementQuery = <<<SQL
ALTER TABLE `{$question->getTable()}` AUTO_INCREMENT=$resetIncrement;
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
    public function get(int $questionId): \App\Domain\Question
    {
        try {
            /**
             * @var \App\Domain\Question $question
             * @phpstan-ignore-next-line
             */
            $question = $this->queryBuilder->newQuery()->findOrFail($questionId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \App\Domain\DomainException\DomainRecordNotFoundException(
                'repository.error.not_found',
                (int)$exception->getCode(),
                $exception
            );
        }

        return $question;
    }

    /**
     * @inheritDoc
     */
    public function delete(\App\Domain\Question $question): bool
    {
        try {
            $question->delete();
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
    public function deleteById(int $questionId): bool
    {
        $this->delete($this->get($questionId));

        return true;
    }
}
