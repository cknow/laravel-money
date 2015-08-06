<?php

namespace Clicknow\Money;

use Clicknow\Money\Exceptions\CurrencyException;
use JsonSerializable;

class Currency implements JsonSerializable
{
    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var int
     */
    protected $precision;

    /**
     * @var int
     */
    protected $subunit;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var bool
     */
    protected $symbolFirst;

    /**
     * @var string
     */
    protected $decimalMark;

    /**
     * @var string
     */
    protected $thousandsSeparator;

    /**
     * @var array
     */
    protected static $currencies;

    /**
     * Create a new instance.
     *
     * @param string $currency
     *
     * @throws \Clicknow\Money\Exceptions\CurrencyException
     */
    public function __construct($currency)
    {
        $currency = strtoupper(trim($currency));
        $currencies = static::getCurrencies();

        if (! array_key_exists($currency, $currencies)) {
            throw new CurrencyException('Invalid currency "'.$currency.'"');
        }

        $attributes = $currencies[ $currency ];
        $this->currency = $currency;
        $this->name = (string) $attributes['name'];
        $this->code = (int) $attributes['code'];
        $this->precision = (int) $attributes['precision'];
        $this->subunit = (int) $attributes['subunit'];
        $this->symbol = (string) $attributes['symbol'];
        $this->symbolFirst = (bool) $attributes['symbol_first'];
        $this->decimalMark = (string) $attributes['decimal_mark'];
        $this->thousandsSeparator = (string) $attributes['thousands_separator'];
    }

    /**
     * __callStatic.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return \Clicknow\Money\Currency
     */
    public static function __callStatic($method, array $arguments)
    {
        return new static($method);
    }

    /**
     * setCurrencies.
     *
     * @param array $currencies
     *
     * @return void
     */
    public static function setCurrencies(array $currencies)
    {
        static::$currencies = $currencies;
    }

    /**
     * getCurrencies.
     *
     * @return array
     */
    public static function getCurrencies()
    {
        if (! isset(static::$currencies)) {
            static::$currencies = require __DIR__.'/../../resources/config/money.php';
        }

        return (array) static::$currencies;
    }

    /**
     * equals.
     *
     * @param \Clicknow\Money\Currency $currency
     *
     * @return bool
     */
    public function equals(self $currency)
    {
        return $this->getCurrency() === $currency->getCurrency();
    }

    /**
     * getCurrency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * getName.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * getCode.
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * getPrecision.
     *
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * getSubunit.
     *
     * @return int
     */
    public function getSubunit()
    {
        return $this->subunit;
    }

    /**
     * getSymbol.
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * isSymbolFirst.
     *
     * @return bool
     */
    public function isSymbolFirst()
    {
        return $this->symbolFirst;
    }

    /**
     * getDecimalMark.
     *
     * @return string
     */
    public function getDecimalMark()
    {
        return $this->decimalMark;
    }

    /**
     * getThousandsSeparator.
     *
     * @return string
     */
    public function getThousandsSeparator()
    {
        return $this->thousandsSeparator;
    }

    /**
     * jsonSerialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [$this->currency => [
            'name'                => $this->name,
            'code'                => $this->code,
            'precision'           => $this->precision,
            'subunit'             => $this->subunit,
            'symbol'              => $this->symbol,
            'symbol_first'        => $this->symbolFirst,
            'decimal_mark'        => $this->decimalMark,
            'thousands_separator' => $this->thousandsSeparator,
        ]];
    }

    /**
     * __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->currency.' ('.$this->name.')';
    }
}
