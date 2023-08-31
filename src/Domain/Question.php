<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property int $id
 * @property string $text
 * @property int $test_id
 * @property \App\Domain\Test $test
 */
class Question extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $table = 'question';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'text',
        'test_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function test(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Test::class, 'id', 'test_id');
    }
}
