<?php

namespace chadrackkanza\Flexpay;
use Illuminate\Support\Facades\Http;

class Flexpay
{
    protected $URL_API;

    protected $URL_C2B;
    protected $URL_B2C;
    protected $URL_CHECK_TRANSACTION;
    protected $TOKEN;
    protected $MERCHANT ;
    protected $TYPE_OPERATION_MOBILE_MONEY = 1;


    public function __construct() 
    {
        $this->URL_API = config('flexpay.url_api');
        $this->URL_API_CARD = config('flexpay.url_api_card');
        
        $this->URL_C2B = $this->URL_API . config('flexpay.url_c2b');
        $this->URL_B2C = $this->URL_API . config('flexpay.url_b2c');
        $this->URL_CARD = $this->URL_API_CARD . config('flexpay.url_card');
        $this->URL_CHECK_TRANSACTION = $this->URL_API . config('flexpay.url_check_transaction');
        $this->TOKEN = config('flexpay.token');
        $this->MERCHANT = config('flexpay.merchant');
        $this->TYPE_OPERATION_MOBILE_MONEY = 1;
    }

    /*
     * Payment consumer to business
     *
     * @param string $phoneNumber
     * @param float $amount
     * @param string $currency
     * @param string $callbackUrl
     * @param float $commission = 0
     */
    public function c2b(string $phoneNumber, float $amount, string $currency, string $callbackUrl, float $commission = 0)
    {
        $result = $this->init(
            $this->URL_C2B,
            [
                "merchant" => $this->MERCHANT,
                "type" => $this->TYPE_OPERATION_MOBILE_MONEY,
                "reference" => $this->getReferenceCode(),
                "phone" => $this->formatPhoneNumber($phoneNumber),
                "amount" => $this->calcTotalAmount($amount, $commission),
                "currency" => $currency,
                "callbackUrl" => $callbackUrl
            ]
        );

        //var_dump($result);
        return $result;
    }

    public function calcTotalAmount(float $amount, float $commission = 0)
    {
        return (($amount * $commission) / 100) + $amount;
    }

    /*
     * Payment business to consumer
     *
     * @param string $phoneNumber
     * @param float $amount
     * @param string $currency
     * @param string $callbackUrl
     * @param float $commission = 0
     */
    public function b2c(string $phoneNumber, float $amount, string $currency, string $callbackUrl, float $commission = 0)
    {
        $result = $this->init(
            $this->URL_B2C,
            [
                "merchant" => $this->MERCHANT,
                "type" => $this->TYPE_OPERATION_MOBILE_MONEY,
                "reference" => $this->getReferenceCode(),
                "phone" => $this->formatPhoneNumber($phoneNumber),
                "amount" => $this->calcTotalAmount($amount, $commission),
                "currency" => $currency,
                "callbackUrl" => $callbackUrl
            ]
        );

        //var_dump($result);
        return $result;
    }

    public function payment(string $reference , string $description , float $amount, string $currency,
             string $callbackUrl, string $approveUrl, string $cancelUrl, string $declineUrl,string $homeUrl )
    {
        $result = $this->init(
            $this->URL_CARD,
            [
                "authorization" => $this->TOKEN,
                "merchant" => $this->MERCHANT,
                "reference" => $reference ,
                "description" => $description ,
                "amount" => $this->calcTotalAmount($amount),
                "currency" => $currency,
                "callback_url" => $callbackUrl,
                "approve_url" => $approveUrl,
                "cancel_url" => $cancelUrl,
                "decline_url" => $declineUrl,
                "home_url" => $homeUrl,
            ]
        );

        //var_dump($result);
        return $result;
    }
    /*
     * Check transaction
     *
     * @param string $orderNumber
     */
    public function checkTransaction(string $orderNumber)
    {
        $result = Http::acceptJson()
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept'        => 'application/json'

        ])
        ->withToken($this->TOKEN)
        ->retry(3,100)
        ->get($this->URL_CHECK_TRANSACTION . '/' . $orderNumber);

         return json_decode($result->getBody());
    }

    /*
     * getReference code
     */
    public function getReferenceCode(string $prefix = '')
    {
        return uniqid($prefix);
    }

    /*
     * Format phone number consumer
     *
     * @param string $phoneNumber
     */
    public function formatPhoneNumber(string $phoneNumber)
    {
        // Format the number according to your needs
        return $phoneNumber;
    }

    /*
     * Init flexPlay operation
     *
     * @param string $uri
     * @param Array $body
     * @param string $method
     */
    private function init(string $uri, array $body, string $method = 'POST')
    {
        try {

            $response = Http::acceptJson()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'        => 'application/json'

                ])
                ->withToken($this->TOKEN)
                ->retry(3,100)
                ->post($uri, $body);

            return json_decode($response->getBody());
        }
        catch (\Exception $exception) {
            // operation failed
            // var_dump($exception)
            return false;
        }
    }
}