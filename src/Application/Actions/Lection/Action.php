<?php

declare(strict_types=1);

namespace App\Application\Actions\Lection;

abstract class Action extends \App\Application\Actions\Action
{
    /**
     * @var \App\Domain\LectionRepositoryInterface $lectionRepository
     */
    protected \App\Domain\LectionRepositoryInterface $lectionRepository;

    /**
     * @param \App\Infrastructure\Filesystem\Log\LectionActionLogger $logger
     * @param \Illuminate\Translation\Translator $translator
     * @param \App\Domain\LectionRepositoryInterface $lectionRepository
     */
    public function __construct(
        \App\Infrastructure\Filesystem\Log\LectionActionLogger $logger,
        \Illuminate\Translation\Translator $translator,
        \App\Domain\LectionRepositoryInterface $lectionRepository
    ) {
        parent::__construct($logger, $translator);

        $this->lectionRepository = $lectionRepository;
    }
}
