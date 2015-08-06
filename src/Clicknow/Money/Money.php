<?php

namespace Clicknow\Money;

use Clicknow\Money\Exceptions\MoneyException;
use JsonSerializable;
use NumberFormatter;

class Money implements JsonSerializable
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
     * @throws \Clicknow\Money\Exceptions\MoneyException
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
     * @return int|float
     *
     * @throws \Clicknow\Money\Exceptions\MoneyException
     */
    protected function parseAmount($amount, $convert = false)
    {
        if (is_callable($amount)) {
            $amount = $amount();
        }

        if (is_string($amount)) {
            $amount = preg_replace('/[^0-9\\'.$this->currency->getThousandsSeparator().'\\'.$this->currency->getDecimalMark().'\-\+]/', '', $amount);
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
        } elseif (is_float($amount)) {
            return (int) round(($convert) ? $amount * $this->currency->getSubunit() : $amount, 0);
        }

        throw new MoneyException('Invalid amount "'.$amount.'"');
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
        return new static($arguments[0], new Currency($method), (isset($arguments[1]) && is_bool($arguments[1])) ? (bool) $arguments[1] : false);
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
     * @throws \Clicknow\Money\Exceptions\MoneyException
     */
    protected function assertSameCurrency(self $other)
    {
        if (! $this->isSameCurrency($other)) {
            throw new MoneyException('Different currencies "'.$this->currency.'" and "'.$other->currency.'"');
        }
    }

    /**
     * assertOperand.
     *
     * @param int|float $operand
     *
     * @throws \Clicknow\Money\Exceptions\MoneyException
     */
    protected function assertOperand($operand)
    {
        if (! is_int($operand) && ! is_float($operand)) {
            throw new MoneyException('Operand "'.$operand.'" should be an integer or a float');
        }
    }

    /**
     * assertRoundingMode.
     *
     * @param int $roundingMode
     *
     * @throws \Clicknow\Money\Exceptions\MoneyException
     */
    protected function assertRoundingMode($roundingMode)
    {
        if (! in_array($roundingMode, [self::ROUND_HALF_DOWN, self::ROUND_HALF_EVEN, self::ROUND_HALF_ODD, self::ROUND_HALF_UP])) {
            throw new MoneyException('Rounding mode should be Money::ROUND_HALF_DOWN | Money::ROUND_HALF_EVEN | Money::ROUND_HALF_ODD | Money::ROUND_HALF_UP');
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
     * @throws \Clicknow\Money\Exceptions\MoneyException
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
     * @param int|float                $ratio
     * @param int                      $roundingMode
     *
     * @return \Clicknow\Money\Money
     *
     * @throws \Clicknow\Money\Exceptions\MoneyException
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
     * @throws \Clicknow\Money\Exceptions\MoneyException
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
     * @throws \Clicknow\Money\Exceptions\MoneyException
     */
    public function subtract(self $subtrahend)
    {
        $this->assertSameCurrency($subtrahend);

        return new self($this->amount - $subtrahend->amount, $this->currency);
    }

    /**
     * multiply.
     *
     * @param int|float $multiplier
     * @param int       $roundingMode
     *
     * @return \Clicknow\Money\Money
     *
     * @throws \Clicknow\Money\Exceptions\MoneyException
     */
    public function multiply($multiplier, $roundingMode = self::ROUND_HALF_UP)
    {
        $this->assertOperand($multiplier);
        $this->assertRoundingMode($roundingMode);

        return new self((int) round($this->amount * $multiplier, 0, $roundingMode), $this->currency);
    }

    /**
     * divide.
     *
     * @param int|float $divisor
     * @param int       $roundingMode
     *
     * @return \Clicknow\Money\Money
     *
     * @throws \Clicknow\Money\Exceptions\MoneyException
     */
    public function divide($divisor, $roundingMode = self::ROUND_HALF_UP)
    {
        $this->assertOperand($divisor);
        $this->assertRoundingMode($roundingMode);

        if ($divisor == 0) {
            throw new MoneyException('Division by zero');
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
     * @param string   $locale
     * @param callable $closure
     *
     * @return string
     */
    public function formatLocale($locale = null, callable $closure = null)
    {
        $formatter = new NumberFormatter($locale ?: static::getLocale(), NumberFormatter::CURRENCY);

        if (is_callable($closure)) {
            $closure($formatter);
        }

        return $formatter->formatCurrency($this->getValue(), $this->currency->getCurrency());
    }

    /**
     * formatSimple.
     *
     * @param int    $decimals
     * @param string $decPoint
     * @param string $thousandsSep
     *
     * @return string
     */
    public function formatSimple($decimals = 2, $decPoint = ',', $thousandsSep = '.')
    {
        return number_format($this->getValue(), $decimals, $decPoint, $thousandsSep);
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
     * jsonSerialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'amount'   => $this->amount,
            'value'    => $this->getValue(),
            'currency' => $this->currency,
        ];
    }

    /**
     * __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }
}
