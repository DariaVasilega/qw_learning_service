<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property int $id
 * @property string $email
 * @property int $test_id
 * @property int $question_id
 * @property int $answer_id
 * @property float $points
 * @property \App\Domain\Test $test
 * @property \App\Domain\Question $question
 * @property \App\Domain\Answer $answer
 */
class Score extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $table = 'score';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'email',
        'test_id',
        'question_id',
        'answer_id',
        'points',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function test(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Test::class, 'id', 'test_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function question(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function answer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Answer::class, 'id', 'answer_id');
    }
}
