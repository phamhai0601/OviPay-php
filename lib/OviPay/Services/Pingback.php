<?php
namespace OviPay\Services;

use OviPay\Gateway;

class Pingback
{
    const EXPIRED_TIME_V2 = 300;

    private $gateway;

    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function validate($params = [])
    {
        return $this->validateV2($params);
       
    }

    public function validateV2($params)
    {

        if (empty($params['pingbackData']) || empty($params['signature'])) {
            return false;
        }

        $expectedSignature = $this->gateway->signature()->calculatePingbackSignature($params['pingbackData']);
        return hash_equals($expectedSignature, $params['signature']);
    }
}