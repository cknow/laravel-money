<?php

namespace Cknow\Money;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use JsonSerializable;
use Money\Currencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\MoneyFormatter;
use Money\MoneyParser;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;

class Money implements Arrayable, Jsonable, JsonSerializable, Renderable
{
    use MoneyFactory;
    use CurrenciesTrait;
    use LocaleTrait;

    /**
     * @var \Money\Money
     */
    private $money;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * Money.
     *
     * @param int|string      $amount
     * @param \Money\Currency $currency
     */
    public function __construct($amount, Currency $currency)
    {
        $this->money = new \Money\Money($amount, $currency);
    }

    /**
     * __call.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return \Cknow\Money\Money|\Cknow\Money\Money[]|mixed
     */
    public function __call($method, array $arguments)
    {
        if (!method_exists($this->money, $method)) {
            return $this;
        }

        return $this->convertResult(call_user_func_array([$this->money, $method], $arguments), $method);
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

    /**
     * Convert.
     *
     * @param \Money\Money $intance
     *
     * @return \Cknow\Money\Money
     */
    public static function convert(\Money\Money $intance)
    {
        return new self($intance->getAmount(), $intance->getCurrency());
    }

    /**
     * Parse.
     *
     * @param string            $money
     * @param string|null       $forceCurrency
     * @param string|null       $locale
     * @param \Money\Currencies $currencies
     *
     * @return \Cknow\Money\Money
     */
    public static function parse($money, $forceCurrency = null, $locale = null, Currencies $currencies = null)
    {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), NumberFormatter::CURRENCY);
        $parser = new IntlMoneyParser($numberFormatter, $currencies ?: static::getCurrencies());

        return self::parseByParser($parser, $money, $forceCurrency);
    }

    /**
     * Parse by decimal.
     *
     * @param string            $money
     * @param string            $forceCurrency
     * @param \Money\Currencies $currencies
     *
     * @return \Cknow\Money\Money
     */
    public static function parseByDecimal($money, $forceCurrency, Currencies $currencies = null)
    {
        $parser = new DecimalMoneyParser($currencies ?: static::getCurrencies());

        return self::parseByParser($parser, $money, $forceCurrency);
    }

    /**
     * Parse by parser.
     *
     * @param \Money\MoneyParser $parser
     * @param string             $money
     * @param string|null        $forceCurrency
     *
     * @return \Cknow\Money\Money
     */
    public static function parseByParser(MoneyParser $parser, $money, $forceCurrency = null)
    {
        return self::convert($parser->parse($money, $forceCurrency));
    }

    /**
     * Add.
     *
     * @param \Cknow\Money\Money $addend
     *
     * @return \Cknow\Money\Money
     */
    public function add(self $addend)
    {
        return self::convert($this->money->add($addend->getMoney()));
    }

    /**
     * Subtract.
     *
     * @param \Cknow\Money\Money $subtrahend
     *
     * @return \Cknow\Money\Money
     */
    public function subtract(self $subtrahend)
    {
        return self::convert($this->money->subtract($subtrahend->getMoney()));
    }

    /**
     * Mod.
     *
     * @param \Cknow\Money\Money $divisor
     *
     * @return \Cknow\Money\Money
     */
    public function mod(self $divisor)
    {
        return self::convert($this->money->mod($divisor->getMoney()));
    }

    /**
     * Ratio of.
     *
     * @param \Cknow\Money\Money $money
     *
     * @return string
     */
    public function ratioOf(self $money)
    {
        return $this->money->ratioOf($money->getMoney());
    }

    /**
     * Get money.
     *
     * @return \Money\Money
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Format.
     *
     * @param string|null       $locale
     * @param \Money\Currencies $currencies
     * @param int               $style
     *
     * @return string
     */
    public function format($locale = null, Currencies $currencies = null, $style = NumberFormatter::CURRENCY)
    {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $formatter = new IntlMoneyFormatter($numberFormatter, $currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by decimal.
     *
     * @param \Money\Currencies $currencies
     *
     * @return string
     */
    public function formatByDecimal(Currencies $currencies = null)
    {
        $formatter = new DecimalMoneyFormatter($currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * Format by formatter.
     *
     * @param \Money\MoneyFormatter $formatter
     *
     * @return string
     */
    public function formatByFormatter(MoneyFormatter $formatter)
    {
        return $formatter->format($this->money);
    }

    /**
     * Attributes.
     *
     * @param array $attributes
     */
    public function attributes(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Json serialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(
            $this->attributes,
            $this->money->jsonSerialize(),
            ['formatted' => $this->render()]
        );
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->jsonSerialize();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
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
     * Convert result.
     *
     * @param mixed|\Money\Money|\Money\Money[] $result
     * @param string                            $method
     *
     * @return \Cknow\Money\Money|\Cknow\Money\Money[]|mixed
     */
    private function convertResult($result, $method)
    {
        if (!in_array($method, ['multiply', 'divide', 'allocate', 'allocateTo', 'absolute', 'negative'])) {
            return $result;
        }

        if (!is_array($result)) {
            return self::convert($result);
        }

        $results = [];

        foreach ($result as $item) {
            $results[] = self::convert($item);
        }

        return $results;
    }
}
