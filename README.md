# PHP Laravel Currency [![Packagist](https://img.shields.io/packagist/dt/easyx/php-currency.svg)](https://packagist.org/packages/easyx/php-currency)

Library for working with currency based on the Central Bank of Russia exchange rate

**:warning: The library is designed to work with Laravel and PHP 8.0 and newer**


### Supported currencies
PHP Laravel Currency supports: _RUB_, _AUD_, _AZN_, _GBP_, _AMD_, _BYN_, _BGN_, _BRL_, _HUF_, _VND_, _HKD_, _GEL_, _DKK_, _AED_, _USD_, _EUR_, _EGP_, _INR_, _IDR_, _KZT_, _CAD_, _QAR_, _KGS_, _CNY_, _MDL_, _NZD_, _NOK_, _PLN_, _RON_, _XDR_, _SGD_, _TJS_, _THB_, _TRY_, _TMT_, _UZS_, _UAH_, _CZK_, _SEK_, _CHF_, _RSD_, _ZAR_, _KRW_, _JPY_.

## Usage
Do currency manipulation in just few lines of code.

```php
use EasyX\Currency;

$currency = new Currency(100, 'RUB');
$amountUsd = $currency->convert('USD'); // 0.9341
```

## Environment recommendations
PHP Laravel Currency takes information about the current exchange rate from the Central Bank of the Russian Federation. We strongly recommend setting up [Cache](https://laravel.com/docs/11.x/cache) if you have not done it before.

```dotenv
CACHE_DRIVER=redis # just not an array
```

## License
[MIT](LICENSE)
