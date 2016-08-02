<?php

namespace Clicknow\Money;

use BadFunctionCallException;
use Closure;
use InvalidArgumentException;
use JsonSerializable;
use OutOfBoundsException;
use UnexpectedValueException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;

class Money implements Arrayable, Jsonable, JsonSerializable, Renderable
{
    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var \Clicknow\Money\Currency
     */
    protected $currency;

    /**
     * @var string
     */
    protected static $locale;

    /**
     * Create a new instance.
     *
     * @param mixed                    $amount
     * @param \Clicknow\Money\Currency $currency
     * @param bool                     $convert
     *
     * @throws \UnexpectedValueException
     */
    public function __construct($amount, Currency $currency, $convert = false)
    {
        $this->currency = $currency;
        $this->amount = $this->parseAmount($amount, $convert);
    }

    /**
     * parseAmount.
     *
     * @param mixed $amount
     * @param bool  $convert
     *
     * @return int|float|float
     *
     * @throws \UnexpectedValueException
     */
    protected function parseAmount($amount, $convert = false)
    {
        if (is_callable($amount)) {
            $amount = $amount();
        }

        if (is_string($amount)) {
            $thousandsSeparator = $this->currency->getThousandsSeparator();
            $decimalMark = $this->currency->getDecimalMark();

            $amount = preg_replace('/[^0-9\\'.$thousandsSeparator.'\\'.$decimalMark.'\-\+]/', '', $amount);
            $amount = str_replace($this->currency->getThousandsSeparator(), '', $amount);
            $amount = str_replace($this->currency->getDecimalMark(), '.', $amount);

            if (preg_match('/^([\-\+])?\d+$/', $amount)) {
                $amount = (int) $amount;
            } elseif (preg_match('/^([\-\+])?\d+\.\d+$/', $amount)) {
                $amount = (float) $amount;
            }
        }

        if (is_int($amount)) {
            return ($convert) ? $amount * $this->currency->getSubunit() : $amount;
        } elseif (is_float($amount) || is_double($amount)) {
            return (int) round(($convert) ? $amount * $this->currency->getSubunit() : $amount, 0);
        }

        throw new UnexpectedValueException('Invalid amount "'.$amount.'"');
    }

    /**
     * __callStatic.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return \Clicknow\Money\Money
     */
    public static function __callStatic($method, array $arguments)
    {
        $convert = (isset($arguments[1]) && is_bool($arguments[1])) ? (bool) $arguments[1] : false;

        return new static($arguments[0], new Currency($method), $convert);
    }

    /**
     * getLocale.
     *
     * @return string
     */
    public static function getLocale()
    {
        if (! isset(static::$locale)) {
            static::$locale = 'pt_BR';
        }

        return static::$locale;
    }

    /**
     * setLocale.
     *
     * @param string $locale
     *
     * @return void
     */
    public static function setLocale($locale)
    {
        static::$locale = $locale;
    }

    /**
     * assertSameCurrency.
     *
     * @param \Clicknow\Money\Money $other
     *
     * @throws \InvalidArgumentException
     */
    protected function assertSameCurrency(self $other)
    {
        if (! $this->isSameCurrency($other)) {
            throw new InvalidArgumentException('Different currencies "'.$this->currency.'" and "'.$other->currency.'"');
        }
    }

    /**
     * assertOperand.
     *
     * @param int|float|float $operand
     *
     * @throws \InvalidArgumentException
     */
    protected function assertOperand($operand)
    {
        if (! is_int($operand) && ! is_float($operand) && ! is_double($operand)) {
            throw new InvalidArgumentException('Operand "'.$operand.'" should be an integer, float or a double');
        }
    }

    /**
     * assertRoundingMode.
     *
     * @param int $roundingMode
     *
     * @throws \OutOfBoundsException
     */
    protected function assertRoundingMode($roundingMode)
    {
        $roundingModes = [self::ROUND_HALF_DOWN, self::ROUND_HALF_EVEN, self::ROUND_HALF_ODD, self::ROUND_HALF_UP];

        if (! in_array($roundingMode, $roundingModes)) {
            throw new OutOfBoundsException('Rounding mode should be '.implode(' | ', $roundingModes));
        }
    }

    /**
     * getAmount.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * getValue.
     *
     * @return float
     */
    public function getValue()
    {
        return round($this->amount / $this->currency->getSubunit(), $this->currency->getPrecision());
    }

    /**
     * getCurrency.
     *
     * @return \Clicknow\Money\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * isSameCurrency.
     *
     * @param \Clicknow\Money\Money $other
     *
     * @return bool
     */
    public function isSameCurrency(self $other)
    {
        return $this->currency->equals($other->currency);
    }

    /**
     * compare.
     *
     * @param \Clicknow\Money\Money $other
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    public function compare(self $other)
    {
        $this->assertSameCurrency($other);

        if ($this->amount < $other->amount) {
            return -1;
        }

        if ($this->amount > $other->amount) {
            return 1;
        }

        return 0;
    }

    /**
     * equals.
     *
     * @param \Clicknow\Money\Money $other
     *
     * @return bool
     */
    public function equals(self $other)
    {
        return $this->compare($other) == 0;
    }

    /**
     * greaterThan.
     *
     * @param \Clicknow\Money\Money $other
     *
     * @return bool
     */
    public function greaterThan(self $other)
    {
        return $this->compare($other) == 1;
    }

    /**
     * greaterThanOrEqual.
     *
     * @param \Clicknow\Money\Money $other
     *
     * @return bool
     */
    public function greaterThanOrEqual(self $other)
    {
        return $this->compare($other) >= 0;
    }

    /**
     * lessThan.
     *
     * @param \Clicknow\Money\Money $other
     *
     * @return bool
     */
    public function lessThan(self $other)
    {
        return $this->compare($other) == -1;
    }

    /**
     * lessThanOrEqual.
     *
     * @param \Clicknow\Money\Money $other
     *
     * @return bool
     */
    public function lessThanOrEqual(self $other)
    {
        return $this->compare($other) <= 0;
    }

    /**
     * convert.
     *
     * @param \Clicknow\Money\Currency $currency
     * @param int|float|float         $ratio
     * @param int                      $roundingMode
     *
     * @return \Clicknow\Money\Money
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     */
    public function convert(Currency $currency, $ratio, $roundingMode = self::ROUND_HALF_UP)
    {
        $this->assertOperand($ratio);
        $this->assertRoundingMode($roundingMode);

        return new self((int) round($this->amount * $ratio, 0, $roundingMode), $currency);
    }

    /**
     * add.
     *
     * @param \Clicknow\Money\Money $addend
     *
     * @return \Clicknow\Money\Money
     *
     * @throws \InvalidArgumentException
     */
    public function add(self $addend)
    {
        $this->assertSameCurrency($addend);

        return new self($this->amount + $addend->amount, $this->currency);
    }

    /**
     * subtract.
     *
     * @param \Clicknow\Money\Money $subtrahend
     *
     * @return \Clicknow\Money\Money
     *
     * @throws \InvalidArgumentException
     */
    public function subtract(self $subtrahend)
    {
        $this->assertSameCurrency($subtrahend);

        return new self($this->amount - $subtrahend->amount, $this->currency);
    }

    /**
     * multiply.
     *
     * @param int|float|float $multiplier
     * @param int              $roundingMode
     *
     * @return \Clicknow\Money\Money
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     */
    public function multiply($multiplier, $roundingMode = self::ROUND_HALF_UP)
    {
        return $this->convert($this->currency, $multiplier, $roundingMode);
    }

    /**
     * divide.
     *
     * @param int|float|float $divisor
     * @param int              $roundingMode
     *
     * @return \Clicknow\Money\Money
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     */
    public function divide($divisor, $roundingMode = self::ROUND_HALF_UP)
    {
        $this->assertOperand($divisor);
        $this->assertRoundingMode($roundingMode);

        if ($divisor == 0) {
            throw new InvalidArgumentException('Division by zero');
        }

        return new self((int) round($this->amount / $divisor, 0, $roundingMode), $this->currency);
    }

    /**
     * allocate.
     *
     * @param array $ratios
     *
     * @return array
     */
    public function allocate(array $ratios)
    {
        $remainder = $this->amount;
        $results = [];
        $total = array_sum($ratios);

        foreach ($ratios as $ratio) {
            $share = (int) floor($this->amount * $ratio / $total);
            $results[] = new self($share, $this->currency);
            $remainder -= $share;
        }

        for ($i = 0; $remainder > 0; $i++) {
            $results[ $i ]->amount++;
            $remainder--;
        }

        return $results;
    }

    /**
     * isZero.
     *
     * @return bool
     */
    public function isZero()
    {
        return $this->amount == 0;
    }

    /**
     * isPositive.
     *
     * @return bool
     */
    public function isPositive()
    {
        return $this->amount > 0;
    }

    /**
     * isNegative.
     *
     * @return bool
     */
    public function isNegative()
    {
        return $this->amount < 0;
    }

    /**
     * formatLocale.
     *
     * @param string  $locale
     * @param Closure $callback
     *
     * @return string
     *
     * @throws \BadFunctionCallException
     */
    public function formatLocale($locale = null, Closure $callback = null)
    {
        if (! class_exists('\NumberFormatter')) {
            throw new BadFunctionCallException('Class NumberFormatter not exists. Require ext-intl extension.');
        }

        $formatter = new \NumberFormatter($locale ?: static::getLocale(), \NumberFormatter::CURRENCY);

        if (is_callable($callback)) {
            $callback($formatter);
        }

        return $formatter->formatCurrency($this->getValue(), $this->currency->getCurrency());
    }

    /**
     * formatSimple.
     *
     * @return string
     */
    public function formatSimple()
    {
        return number_format(
            $this->getValue(),
            $this->currency->getPrecision(),
            $this->currency->getDecimalMark(),
            $this->currency->getThousandsSeparator()
        );
    }

    /**
     * format.
     *
     * @return string
     */
    public function format()
    {
        $negative = $this->isNegative();
        $value = $this->getValue();
        $amount = $negative ? -$value : $value;
        $thousands = $this->currency->getThousandsSeparator();
        $decimals = $this->currency->getDecimalMark();
        $symbolFirst = $this->currency->isSymbolFirst();
        $symbol = $this->currency->getSymbol();

        $prefix = ($negative ? '-' : '').($symbolFirst ? $symbol.' ' : '');
        $value = number_format($amount, 2, $decimals, $thousands);
        $suffix = ! $symbolFirst ? $symbol : '';

        return $prefix.$value.$suffix;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'amount'   => $this->amount,
            'value'    => $this->getValue(),
            'currency' => $this->currency,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * jsonSerialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->format();
    }

    /**
     * __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
