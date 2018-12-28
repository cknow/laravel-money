<?php

namespace Cknow\Money;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use JsonSerializable;
use Money\Currency;

class Money implements Arrayable, Jsonable, JsonSerializable, Renderable
{
    use CurrenciesTrait;
    use LocaleTrait;
    use MoneyFactory;
    use MoneyFormatterTrait;
    use MoneyParserTrait;

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
     * Add.
     *
     * @param \Cknow\Money\Money[] $addends
     *
     * @return \Cknow\Money\Money
     */
    public function add(self ...$addends)
    {
        $moneys = $this->getMoneys(...$addends);

        return self::convert($this->money->add(...$moneys));
    }

    /**
     * Subtract.
     *
     * @param \Cknow\Money\Money[] $subtrahends
     *
     * @return \Cknow\Money\Money
     */
    public function subtract(self ...$subtrahends)
    {
        $moneys = $this->getMoneys(...$subtrahends);

        return self::convert($this->money->subtract(...$moneys));
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

    /**
     * Get moneys.
     *
     * @param \Cknow\Money\Money[] $moneys
     *
     * @return \Money\Money[]
     */
    private static function getMoneys(self ...$moneys)
    {
        $results = [];

        foreach ($moneys as $money) {
            $results[] = $money->getMoney();
        }

        return $results;
    }
}
