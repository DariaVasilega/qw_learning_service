<?php

declare(strict_types=1);

namespace App\Domain;

interface LectionRepositoryInterface extends RepositoryInterface
{
    /**
     * @param \App\Domain\Lection $lection
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotSavedException
     */
    public function save(\App\Domain\Lection $lection): bool;

    /**
     * @param int $lectionId
     * @return \App\Domain\Lection
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     */
    public function get(int $lectionId): \App\Domain\Lection;

    /**
     * @param \App\Domain\Lection $lection
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function delete(\App\Domain\Lection $lection): bool;

    /**
     * @param int $lectionId
     * @return bool
     * @throws \App\Domain\DomainException\DomainRecordNotFoundException
     * @throws \App\Domain\DomainException\DomainRecordNotRemovedException
     */
    public function deleteById(int $lectionId): bool;
}
