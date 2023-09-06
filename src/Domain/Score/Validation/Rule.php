<?php

declare(strict_types=1);

namespace App\Domain\Score\Validation;

class Rule implements \App\Application\ValidationRuleInterface
{
    /**
     * @var \Illuminate\Translation\Translator $translator
     */
    private \Illuminate\Translation\Translator $translator;

    /**
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(
        \Illuminate\Translation\Translator $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function getRules(): array
    {
        return [
            'email' => [
                'required',
            ],
            'points' => [
                'required',
                'numeric',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        return [
            'email.required' => $this->translator->get('validation.required'),
            'points.required' => $this->translator->get('validation.required'),
            'points.numeric' => $this->translator->get('validation.numeric'),
        ];
    }
}
