<?php

declare(strict_types=1);

namespace App\Domain;

interface ScoreRepositoryInterface extends RepositoryInterface
{
    /**
     * @param \App\Domain\Score $score
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     */
    public function save(\App\Domain\Score $score): bool;

    /**
     * @param int $scoreId
     * @return \App\Domain\Score
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    public function get(int $scoreId): \App\Domain\Score;

    /**
     * @param \App\Domain\Score $score
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function delete(\App\Domain\Score $score): bool;

    /**
     * @param int $scoreId
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function deleteById(int $scoreId): bool;
}
