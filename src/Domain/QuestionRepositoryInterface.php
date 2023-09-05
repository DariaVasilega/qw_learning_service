<?php

declare(strict_types=1);

namespace App\Domain;

interface QuestionRepositoryInterface extends RepositoryInterface
{
    /**
     * @param \App\Domain\Question $question
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     */
    public function save(\App\Domain\Question $question): bool;

    /**
     * @param int $questionId
     * @return \App\Domain\Question
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    public function get(int $questionId): \App\Domain\Question;

    /**
     * @param \App\Domain\Question $question
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function delete(\App\Domain\Question $question): bool;

    /**
     * @param int $questionId
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function deleteById(int $questionId): bool;
}
