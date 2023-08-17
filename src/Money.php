<?php

namespace Cknow\Money;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use ReflectionMethod;

/**
 * Money.
 *
 * @method bool isSameCurrency(Money|\Money\Money ...$others)
 * @method bool equals(Money|\Money\Money $other)
 * @method int compare(Money|\Money\Money $other)
 * @method bool greaterThan(Money|\Money\Money $other)
 * @method bool greaterThanOrEqual(Money|\Money\Money $other)
 * @method bool lessThan(Money|\Money\Money $other)
 * @method bool lessThanOrEqual(Money|\Money\Money $other)
 * @method string getAmount()
 * @method \Money\Currency getCurrency()
 * @method Money add(Money|\Money\Money ...$addends)
 * @method Money subtract(Money|\Money\Money ...$subtrahends)
 * @method Money mod(Money|\Money\Money $divisor)
 * @method Money[] allocate(array $ratios)
 * @method Money[] allocateTo(int $n)
 * @method string ratioOf(Money|\Money\Money $money)
 * @method Money absolute()
 * @method Money negative()
 * @method bool isZero()
 * @method bool isPositive()
 * @method bool isNegative()
 * @method static Money min(Money|\Money\Money $first, Money|\Money\Money ...$collection)
 * @method static Money max(Money|\Money\Money $first, Money|\Money\Money ...$collection)
 * @method static Money sum(Money|\Money\Money $first, Money|\Money\Money ...$collection)
 * @method static Money avg(Money|\Money\Money $first, Money|\Money\Money ...$collection)
 */
class Money implements Arrayable, Jsonable, JsonSerializable, Renderable
{
    use CurrenciesTrait;
    use LocaleTrait;
    use MoneyFactory {
        MoneyFactory::__callStatic as factoryCallStatic;
    }
    use MoneyFormatterTrait;
    use MoneySerializerTrait;
    use MoneyParserTrait;
    use Macroable {
        Macroable::__call as macroCall;
    }

    /**
     * @var \Money\Money
     */
    protected $money;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Money.
     *
     * @param  mixed  $amount
     * @param  \Money\Currency|string|null  $currency
     * @param  bool  $forceDecimals
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     */
    public function __construct($amount = null, $currency = null, $forceDecimals = false, $locale = null, $currencies = null)
    {
        $this->money = Money::parse($amount, $currency, $forceDecimals, $locale, $currencies, null, false);
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
     * Get attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Divide.
     *
     * @param  int|string|float  $divisor
     * @param  int  $roundingMode
     * @return \Cknow\Money\Money
     */
    public function divide($divisor, $roundingMode = \Money\Money::ROUND_HALF_UP)
    {
        return $this->__call('divide', [
            is_int($divisor) ? $divisor : strval($divisor),
            $roundingMode,
        ]);
    }

    /**
     * Multiply.
     *
     * @param  int|string|float  $multiplier
     * @param  int  $roundingMode
     * @return \Cknow\Money\Money
     */
    public function multiply($multiplier, $roundingMode = \Money\Money::ROUND_HALF_UP)
    {
        return $this->__call('multiply', [
            is_int($multiplier) ? $multiplier : strval($multiplier),
            $roundingMode,
        ]);
    }

    /**
     * Attributes.
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
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->serialize();
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
     * @param  int  $options
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
     * __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * __call.
     *
     * @param  string  $method
     * @return \Cknow\Money\Money|\Cknow\Money\Money[]|mixed
     */
    public function __call($method, array $arguments)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $arguments);
        }

        if (! method_exists($this->money, $method)) {
            return $this;
        }

        $result = call_user_func_array([$this->money, $method], static::getArguments($arguments));

        $methods = [
            'add', 'subtract',
            'multiply', 'divide', 'mod',
            'allocate', 'allocateTo',
            'absolute', 'negative',
        ];

        if (! in_array($method, $methods)) {
            return $result;
        }

        return static::convertResult($result);
    }

    /**
     * Convert.
     *
     * @return \Cknow\Money\Money
     */
    public static function convert(\Money\Money $instance)
    {
        return static::fromMoney($instance);
    }

    /**
     * __callStatic.
     *
     * @param  string  $method
     * @return \Cknow\Money\Money
     */
    public static function __callStatic($method, array $arguments)
    {
        if (in_array($method, ['min', 'max', 'avg', 'sum'])) {
            $result = call_user_func_array([\Money\Money::class, $method], static::getArguments($arguments));

            return static::convert($result);
        }

        return static::factoryCallStatic($method, $arguments);
    }

    /**
     * Get arguments.
     *
     * @return array
     */
    private static function getArguments(array $arguments = [])
    {
        $args = [];

        foreach ($arguments as $argument) {
            $args[] = $argument instanceof static ? $argument->getMoney() : $argument;
        }

        return $args;
    }

    /**
     * Convert result.
     *
     * @param  mixed  $result
     * @return \Cknow\Money\Money|\Cknow\Money\Money[]
     */
    private static function convertResult($result)
    {
        if (! is_array($result)) {
            return static::convert($result);
        }

        $results = [];

        foreach ($result as $item) {
            $results[] = static::convert($item);
        }

        return $results;
    }

    /**
     * Resolve calculator.
     *
     * @return \Money\Calculator
     *
     * @throws \RuntimeException
     */
    private static function resolveCalculator()
    {
        $reflection = new ReflectionMethod(\Money\Money::class, 'getCalculator');

        if ($reflection->isPublic()) {
            $calculator = call_user_func([\Money\Money::class, 'getCalculator']);

            return new $calculator();
        }

        $calculators = [
            \Money\Calculator\BcMathCalculator::class,
            \Money\Calculator\GmpCalculator::class,
            \Money\Calculator\PhpCalculator::class,
        ];

        foreach ($calculators as $calculator) {
            if (! class_exists($calculator)) {
                continue;
            }

            /** @var Calculator $calculator */
            if ($calculator::supported()) {
                return new $calculator();
            }
        }

        throw new \RuntimeException('Cannot find calculator for money calculations');
    }
}
