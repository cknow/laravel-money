<?php

namespace Cknow\Money\Tests\Database\Models;

use Cknow\Money\MoneyIntCast;
use Illuminate\Database\Eloquent\Model;

/**
 * The testing user model.
 */
class UserInt extends Model
{
    protected $table = 'users';

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
        'money' => MoneyIntCast::class,
        'wage' => MoneyIntCast::class.':EUR',
        'debits' => MoneyIntCast::class.':currency',
    ];
}
