<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property int $id
 * @property string $label
 * @property float $passing_score
 * @property int $lection_id
 * @property \App\Domain\Lection $lection
 * @property \Illuminate\Database\Eloquent\Collection $questions
 */
class Test extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $table = 'test';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'label',
        'passing_score',
        'lection_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lection(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Lection::class, 'id', 'lection_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Question::class, 'test_id', 'id');
    }
}
