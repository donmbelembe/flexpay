<?php

// config for chadrackkanza/Flexpay
return [
    'token' => env('FLEXPAY_TOKEN'),
    'merchant' => env('FLEXPAY_MERCHANT'),
    'url_api' => env('FLEXPAY_URL_API','http://41.243.7.46:3006'),
    'url_api_card' => env('FLEXPAY_URL_API_CARD','https://beta-cardpayment.flexpay.cd'),
    'url_c2b' => '/api/rest/v1/paymentService',
    'url_b2c' => '/api/rest/v1/merchantPayOutService',
    'url_card' => '/api/rest/v1/vpos/ask',
    'url_check_transaction' => '/api/rest/v1/check',
];