<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property int $id
 * @property string $text
 * @property \App\Domain\Test $test
 */
class Lection extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $table = 'lection';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'text',
    ];

    public function test(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Test::class, 'lection_id', 'id');
    }
}
