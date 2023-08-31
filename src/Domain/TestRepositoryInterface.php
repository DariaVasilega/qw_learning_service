<?php

declare(strict_types=1);

namespace App\Domain;

interface TestRepositoryInterface extends RepositoryInterface
{
    /**
     * @param \App\Domain\Test $test
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     */
    public function save(\App\Domain\Test $test): bool;

    /**
     * @param int $testId
     * @return \App\Domain\Test
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    public function get(int $testId): \App\Domain\Test;

    /**
     * @param \App\Domain\Test $test
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function delete(\App\Domain\Test $test): bool;

    /**
     * @param int $testId
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function deleteById(int $testId): bool;
}
