<?php

namespace Cknow\Money\Rules;

use Illuminate\Contracts\Validation\Rule;
use InvalidArgumentException;
use Money\Exception\ParserException;

class Money implements Rule
{
    /**
     * @var \Money\Currency|string|null
     */
    protected $currency;

    /**
     * @var string|null
     */
    protected $locale;

    /**
     * @var \Money\Currencies|null
     */
    protected $currencies;

    /**
     * @var int|null
     */
    protected $bitCointDigits;

    /**
     * Create a new rule instance.
     *
     * @param  \Money\Currency|string|null  $currency
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     * @param  int|null  $bitCointDigits
     * @return void
     */
    public function __construct(
        $currency = null,
        $locale = null,
        $currencies = null,
        $bitCointDigits = null
    ) {
        $this->currency = $currency;
        $this->locale = $locale;
        $this->currencies = $currencies;
        $this->bitCointDigits = $bitCointDigits;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $money = \Cknow\Money\Money::parse(
                $value,
                $this->currency,
                false,
                $this->locale,
                $this->currencies,
                $this->bitCointDigits
            );

            return ! (
                $this->currency && ! $money->getCurrency()->equals(\Cknow\Money\Money::parseCurrency($this->currency))
            );
        } catch (InvalidArgumentException $e) {
            return false;
        } catch (ParserException $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = trans('validation.money');

        return $message === 'validation.money'
            ? 'The :attribute is not a valid money.'
            : $message;
    }
}
