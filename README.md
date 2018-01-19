# Laravel Money

[![Latest Stable Version](https://poser.pugx.org/cknow/laravel-money/version)](https://packagist.org/packages/cknow/laravel-money)
[![Total Downloads](https://poser.pugx.org/cknow/laravel-money/downloads)](https://packagist.org/packages/cknow/laravel-money)
[![License](https://poser.pugx.org/cknow/laravel-money/license)](https://packagist.org/packages/cknow/laravel-money)

[![StyleCI](https://styleci.io/repos/40018123/shield?style=flat)](https://styleci.io/repos/40018123)
[![Build Status](https://travis-ci.org/cknow/laravel-money.svg?branch=master)](https://travis-ci.org/cknow/laravel-money)
[![Build status](https://ci.appveyor.com/api/projects/status/7c0elm504qk99dsh/branch/master?svg=true)](https://ci.appveyor.com/project/cknow/laravel-money/branch/master)
[![Coverage Status](https://coveralls.io/repos/github/cknow/laravel-money/badge.svg?branch=master)](https://coveralls.io/github/cknow/laravel-money?branch=master)

[![Code Climate](https://codeclimate.com/github/cknow/laravel-money/badges/gpa.svg)](https://codeclimate.com/github/cknow/laravel-money)
[![Test Coverage](https://codeclimate.com/github/cknow/laravel-money/badges/coverage.svg)](https://codeclimate.com/github/cknow/laravel-money/coverage)
[![Issue Count](https://codeclimate.com/github/cknow/laravel-money/badges/issue_count.svg)](https://codeclimate.com/github/cknow/laravel-money)

[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/cknow/laravel-money.svg)](http://isitmaintained.com/project/cknow/laravel-money)
[![Percentage of issues still open](http://isitmaintained.com/badge/open/cknow/laravel-money.svg)](http://isitmaintained.com/project/cknow/laravel-money)
[![Gitter](https://badges.gitter.im/cknow/laravel-money.svg)](https://gitter.im/cknow/laravel-money?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a56211b8-224f-4345-bca7-4de0ddd40727/big.png)](https://insight.sensiolabs.com/projects/a56211b8-224f-4345-bca7-4de0ddd40727)

> **Note:** This project abstracts [MoneyPHP](http://moneyphp.org/)

## Installation

Run the following command from you terminal:

```bash
composer require cknow/laravel-money
```

or add this to require section in your composer.json file:

```
"cknow/laravel-money": "~2.0"
```

then run ```composer update```

## Usage

```php
use Cknow\Money\Money;

echo Money::BRL(500); // R$5,00
```

## Advanced Usage

> See [MoneyPHP](http://moneyphp.org/) for more information

```php
use Cknow\Money\Money;

Money::BRL(500)->add(Money::BRL(500)); // 10,00
Money::BRL(500)->subtract(Money::BRL(400)); // 1,00
Money::BRL(500)->isZero(); // false
Money::BRL(500)->isPositive(); // true
Money::BRL(500)->isNegative(); // false
Money::BRL(500)->format(); // R$5,00
Money::BRL(199)->format(null, null, \NumberFormatter::DECIMAL); // 1,99
Money::BRL(500)->formatByDecimal(); // 5.00
Money::parse('R$1,00'); // R$1,00 -> Money::BRL(100)
Money::parseByDecimal('1.00', 'BRL'); // R$1,00 -> Money::BRL(100)
```

### Create your formatter

```php
class MyFormatter implements \Money\MoneyFormatter
{
    public function format(\Money\Money $money)
    {
        return 'My Formatter';
    }
}

Money::BRL(500)->formatByFormatter(new MyFormatter());
```

## Helpers

```php
currency('BRL')
money(500, 'BRL')
money_parse('R$5,00')
```

## Blade Extensions

```php
@currency('BRL')
@money(500, 'BRL')
@money_parse('R$5,00')
```
