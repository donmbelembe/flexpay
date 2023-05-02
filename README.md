# Package FlexPay API

## Installation

You can install the package via composer:

```bash
composer require chadrackkanza/flexpay
```



You can publish the config file with:

```bash
php artisan vendor:publish --tag="flexpay-config"
```

This is the contents of the published config file:

```php
return [
    'token' => env('FLEXPAY_TOKEN'),
    'merchant' => env('FLEXPAY_MERCHANT'),
    'url_api' => env('FLEXPAY_URL_API','http://41.243.7.46:3006'),
    'url_c2b' => '/api/rest/v1/paymentService',
    'url_b2c' => '/api/rest/v1/merchantPayOutService',
    'url_check_transaction' => '/api/rest/v1/check',
];
```



## Usage

```php
use chadrackkanza\Flexpay\Facades\Flexpay;
echo Flexpay::c2b(
        '243XXXXXXXXX', //phoneNumber
        '1500', //amount
        'cdf', //currency
        'www.flexpay.cd', //callbackUrl
        1 //commission
   );
```

Add this lines in your .env file

FLEXPAY_TOKEN="YOUR_FLEXPAY_TOKEN"
FLEXPAY_MERCHANT="YOUR_FLEXPAY_MERCHANT"
FLEXPAY_URL_API="FLEXPAY_URL_API"



## Credits

- [Chadrack KANZA](https://github.com/chadrackkanza)
