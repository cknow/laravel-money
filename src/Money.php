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
use NumberFormatter;

/**
 * Money.
 *
 * @method static Money AED(int|string $amount)
 * @method static Money AFN(int|string $amount)
 * @method static Money ALL(int|string $amount)
 * @method static Money AMD(int|string $amount)
 * @method static Money ANG(int|string $amount)
 * @method static Money AOA(int|string $amount)
 * @method static Money ARS(int|string $amount)
 * @method static Money AUD(int|string $amount)
 * @method static Money AWG(int|string $amount)
 * @method static Money AZN(int|string $amount)
 * @method static Money BAM(int|string $amount)
 * @method static Money BBD(int|string $amount)
 * @method static Money BDT(int|string $amount)
 * @method static Money BGN(int|string $amount)
 * @method static Money BHD(int|string $amount)
 * @method static Money BIF(int|string $amount)
 * @method static Money BMD(int|string $amount)
 * @method static Money BND(int|string $amount)
 * @method static Money BOB(int|string $amount)
 * @method static Money BOV(int|string $amount)
 * @method static Money BRL(int|string $amount)
 * @method static Money BSD(int|string $amount)
 * @method static Money BTN(int|string $amount)
 * @method static Money BWP(int|string $amount)
 * @method static Money BYN(int|string $amount)
 * @method static Money BZD(int|string $amount)
 * @method static Money CAD(int|string $amount)
 * @method static Money CDF(int|string $amount)
 * @method static Money CHE(int|string $amount)
 * @method static Money CHF(int|string $amount)
 * @method static Money CHW(int|string $amount)
 * @method static Money CLF(int|string $amount)
 * @method static Money CLP(int|string $amount)
 * @method static Money CNY(int|string $amount)
 * @method static Money COP(int|string $amount)
 * @method static Money COU(int|string $amount)
 * @method static Money CRC(int|string $amount)
 * @method static Money CUC(int|string $amount)
 * @method static Money CUP(int|string $amount)
 * @method static Money CVE(int|string $amount)
 * @method static Money CZK(int|string $amount)
 * @method static Money DJF(int|string $amount)
 * @method static Money DKK(int|string $amount)
 * @method static Money DOP(int|string $amount)
 * @method static Money DZD(int|string $amount)
 * @method static Money EGP(int|string $amount)
 * @method static Money ERN(int|string $amount)
 * @method static Money ETB(int|string $amount)
 * @method static Money EUR(int|string $amount)
 * @method static Money FJD(int|string $amount)
 * @method static Money FKP(int|string $amount)
 * @method static Money GBP(int|string $amount)
 * @method static Money GEL(int|string $amount)
 * @method static Money GHS(int|string $amount)
 * @method static Money GIP(int|string $amount)
 * @method static Money GMD(int|string $amount)
 * @method static Money GNF(int|string $amount)
 * @method static Money GTQ(int|string $amount)
 * @method static Money GYD(int|string $amount)
 * @method static Money HKD(int|string $amount)
 * @method static Money HNL(int|string $amount)
 * @method static Money HRK(int|string $amount)
 * @method static Money HTG(int|string $amount)
 * @method static Money HUF(int|string $amount)
 * @method static Money IDR(int|string $amount)
 * @method static Money ILS(int|string $amount)
 * @method static Money INR(int|string $amount)
 * @method static Money IQD(int|string $amount)
 * @method static Money IRR(int|string $amount)
 * @method static Money ISK(int|string $amount)
 * @method static Money JMD(int|string $amount)
 * @method static Money JOD(int|string $amount)
 * @method static Money JPY(int|string $amount)
 * @method static Money KES(int|string $amount)
 * @method static Money KGS(int|string $amount)
 * @method static Money KHR(int|string $amount)
 * @method static Money KMF(int|string $amount)
 * @method static Money KPW(int|string $amount)
 * @method static Money KRW(int|string $amount)
 * @method static Money KWD(int|string $amount)
 * @method static Money KYD(int|string $amount)
 * @method static Money KZT(int|string $amount)
 * @method static Money LAK(int|string $amount)
 * @method static Money LBP(int|string $amount)
 * @method static Money LKR(int|string $amount)
 * @method static Money LRD(int|string $amount)
 * @method static Money LSL(int|string $amount)
 * @method static Money LTL(int|string $amount)
 * @method static Money LVL(int|string $amount)
 * @method static Money LYD(int|string $amount)
 * @method static Money MAD(int|string $amount)
 * @method static Money MDL(int|string $amount)
 * @method static Money MGA(int|string $amount)
 * @method static Money MKD(int|string $amount)
 * @method static Money MMK(int|string $amount)
 * @method static Money MNT(int|string $amount)
 * @method static Money MOP(int|string $amount)
 * @method static Money MRO(int|string $amount)
 * @method static Money MUR(int|string $amount)
 * @method static Money MVR(int|string $amount)
 * @method static Money MWK(int|string $amount)
 * @method static Money MXN(int|string $amount)
 * @method static Money MXV(int|string $amount)
 * @method static Money MYR(int|string $amount)
 * @method static Money MZN(int|string $amount)
 * @method static Money NAD(int|string $amount)
 * @method static Money NGN(int|string $amount)
 * @method static Money NIO(int|string $amount)
 * @method static Money NOK(int|string $amount)
 * @method static Money NPR(int|string $amount)
 * @method static Money NZD(int|string $amount)
 * @method static Money OMR(int|string $amount)
 * @method static Money PAB(int|string $amount)
 * @method static Money PEN(int|string $amount)
 * @method static Money PGK(int|string $amount)
 * @method static Money PHP(int|string $amount)
 * @method static Money PKR(int|string $amount)
 * @method static Money PLN(int|string $amount)
 * @method static Money PYG(int|string $amount)
 * @method static Money QAR(int|string $amount)
 * @method static Money RON(int|string $amount)
 * @method static Money RSD(int|string $amount)
 * @method static Money RUB(int|string $amount)
 * @method static Money RWF(int|string $amount)
 * @method static Money SAR(int|string $amount)
 * @method static Money SBD(int|string $amount)
 * @method static Money SCR(int|string $amount)
 * @method static Money SDG(int|string $amount)
 * @method static Money SEK(int|string $amount)
 * @method static Money SGD(int|string $amount)
 * @method static Money SHP(int|string $amount)
 * @method static Money SLL(int|string $amount)
 * @method static Money SOS(int|string $amount)
 * @method static Money SRD(int|string $amount)
 * @method static Money SSP(int|string $amount)
 * @method static Money STD(int|string $amount)
 * @method static Money SVC(int|string $amount)
 * @method static Money SYP(int|string $amount)
 * @method static Money SZL(int|string $amount)
 * @method static Money THB(int|string $amount)
 * @method static Money TJS(int|string $amount)
 * @method static Money TMT(int|string $amount)
 * @method static Money TND(int|string $amount)
 * @method static Money TOP(int|string $amount)
 * @method static Money TRY(int|string $amount)
 * @method static Money TTD(int|string $amount)
 * @method static Money TWD(int|string $amount)
 * @method static Money TZS(int|string $amount)
 * @method static Money UAH(int|string $amount)
 * @method static Money UGX(int|string $amount)
 * @method static Money USD(int|string $amount)
 * @method static Money USN(int|string $amount)
 * @method static Money UYI(int|string $amount)
 * @method static Money UYU(int|string $amount)
 * @method static Money UZS(int|string $amount)
 * @method static Money VEF(int|string $amount)
 * @method static Money VND(int|string $amount)
 * @method static Money VUV(int|string $amount)
 * @method static Money WST(int|string $amount)
 * @method static Money XAF(int|string $amount)
 * @method static Money XAG(int|string $amount)
 * @method static Money XAU(int|string $amount)
 * @method static Money XBA(int|string $amount)
 * @method static Money XBB(int|string $amount)
 * @method static Money XBC(int|string $amount)
 * @method static Money XBD(int|string $amount)
 * @method static Money XCD(int|string $amount)
 * @method static Money XDR(int|string $amount)
 * @method static Money XOF(int|string $amount)
 * @method static Money XPD(int|string $amount)
 * @method static Money XPF(int|string $amount)
 * @method static Money XPT(int|string $amount)
 * @method static Money XSU(int|string $amount)
 * @method static Money XTS(int|string $amount)
 * @method static Money XUA(int|string $amount)
 * @method static Money XXX(int|string $amount)
 * @method static Money YER(int|string $amount)
 * @method static Money ZAR(int|string $amount)
 * @method static Money ZMW(int|string $amount)
 * @method static Money ZWL(int|string $amount)
 */
class Money implements Arrayable, Jsonable, JsonSerializable, Renderable
{
    use CurrenciesTrait;
    use LocaleTrait;

    /**
     * @var \Money\Money
     */
    private $money;

    /**
     * Money.
     *
     * @param int|string     $amount
     * @param \Money\Currency $currency
     */
    public function __construct($amount, Currency $currency)
    {
        $this->money = new \Money\Money($amount, $currency);
    }

    /**
     * __callStatic.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return \Cknow\Money\Money
     */
    public static function __callStatic($method, array $arguments)
    {
        return new self($arguments[0], new Currency($method));
    }

    /**
     * __call.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return $this->money->{$method}(...$arguments);
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
     *
     * @return string
     */
    public function format($locale = null, Currencies $currencies = null)
    {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), NumberFormatter::CURRENCY);
        $formatter = new IntlMoneyFormatter($numberFormatter, $currencies ?: static::getCurrencies());

        return $formatter->format($this->money);
    }

    /**
     * Format simple.
     *
     * @param \Money\Currencies $currencies
     *
     * @return string
     */
    public function formatSimple(Currencies $currencies = null)
    {
        $formatter = new DecimalMoneyFormatter($currencies ?: static::getCurrencies());

        return $formatter->format($this->money);
    }

    /**
     * Json serialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->money->jsonSerialize();
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
}
