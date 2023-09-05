<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property int $id
 * @property string $text
 * @property int $question_id
 * @property float $points
 * @property \App\Domain\Question $question
 */
class Answer extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $table = 'answer';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'text',
        'question_id',
        'points',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function question(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}
