<?php

namespace Cknow\Money;

use Cknow\Money\Serializer\ArrayMoneySerializer;
use InvalidArgumentException;

trait MoneySerializerTrait
{
    /**
     * Serialize.
     *
     * @param  string|null  $locale
     * @param  \Money\Currencies|null  $currencies
     * @param  int  $style
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function serialize()
    {
        $defaultSerializer = config('money.defaultSerializer');

        if (is_null($defaultSerializer)) {
            return $this->serializeByArray();
        }

        $serializer = null;

        if (is_string($defaultSerializer)) {
            $serializer = app($defaultSerializer);
        }

        if (is_array($defaultSerializer) && count($defaultSerializer) === 2) {
            $serializer = app($defaultSerializer[0], $defaultSerializer[1]);
        }

        if ($serializer instanceof MoneySerializer) {
            return $this->serializeBySerializer($serializer);
        }

        throw new InvalidArgumentException(sprintf('Invalid default serializer %s', json_encode($defaultSerializer)));
    }

    /**
     * Serialize by array.
     *
     * @return mixed
     */
    public function serializeByArray()
    {
        $serializer = new ArrayMoneySerializer();

        return $this->serializeBySerializer($serializer);
    }

    /**
     * Serialize by serializer.
     *
     * @param  \Cknow\Money\MoneySerializer  $serializer
     * @return mixed
     */
    public function serializeBySerializer(MoneySerializer $serializer)
    {
        return $serializer->serialize($this);
    }
}
