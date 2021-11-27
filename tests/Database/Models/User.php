<?php

namespace Cknow\Money\Tests\Database\Models;

use Cknow\Money\Casts\MoneyDecimalCast;
use Cknow\Money\Casts\MoneyIntegerCast;
use Cknow\Money\Casts\MoneyStringCast;
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
        'credits',
        'currency',
    ];

    /**
     * The attributes to cast.
     *
     * @var array
     */
    protected $casts = [
        'money' => MoneyStringCast::class,
        'wage' => MoneyIntegerCast::class.':EUR',
        'debits' => MoneyDecimalCast::class.':currency',
        'credits' => MoneyDecimalCast::class.':USD',
    ];
}
