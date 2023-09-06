<?php

declare(strict_types=1);

namespace App\Domain\Test\Validation;

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
            'label' => [
                'required',
                'max:255',
            ],
            'passing_score' => [
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
            'label.required' => $this->translator->get('validation.required'),
            'label.max' => $this->translator->get('validation.max.string'),
            'passing_score.required' => $this->translator->get('validation.required'),
            'passing_score.numeric' => $this->translator->get('validation.numeric'),
        ];
    }
}
