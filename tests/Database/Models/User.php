<?php

namespace Cknow\Money\Database\Models;

use Cknow\Money\MoneyCast;
use Illuminate\Database\Eloquent\Model;

/**
 * The testing user model.
 */
class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'money',
        'wage',
        'debits',
        'currency',
    ];

    /**
     * The attributes to cast.
     *
     * @var array
     */
    protected $casts = [
        'money' => MoneyCast::class,
        'wage' => MoneyCast::class.':EUR',
        'debits' => MoneyCast::class.':currency',
    ];
}
