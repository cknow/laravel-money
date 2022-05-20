<?php

namespace Cknow\Money\Rules;

use Cknow\Money\Money;
use Illuminate\Contracts\Validation\Rule;

class Currency implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Money::isValidCurrency($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = trans('validation.currency');

        return $message === 'validation.currency'
            ? 'The :attribute is not a valid currency.'
            : $message;
    }
}
