<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property int $id
 * @property string $text
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
}
