<?php

declare(strict_types=1);

namespace App\Domain\Question\Validation;

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
            'text' => [
                'required',
                'max:512',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        return [
            'text.required' => $this->translator->get('validation.required'),
            'text.max' => $this->translator->get('validation.max.string'),
        ];
    }
}
