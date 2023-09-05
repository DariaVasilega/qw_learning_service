<?php

declare(strict_types=1);

namespace App\Domain;

interface AnswerRepositoryInterface extends RepositoryInterface
{
    /**
     * @param \App\Domain\Answer $answer
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     */
    public function save(\App\Domain\Answer $answer): bool;

    /**
     * @param int $answerId
     * @return \App\Domain\Answer
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    public function get(int $answerId): \App\Domain\Answer;

    /**
     * @param \App\Domain\Answer $answer
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function delete(\App\Domain\Answer $answer): bool;

    /**
     * @param int $answerId
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function deleteById(int $answerId): bool;
}
