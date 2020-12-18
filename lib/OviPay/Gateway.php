<?php

namespace OviPay;

use OviPay\Services\GenericApiService;
use OviPay\Services\HttpService;
use OviPay\Services\PaymentForm;
use OviPay\Services\Payment;
use OviPay\Services\Subscription;
use OviPay\Services\Signature;
use OviPay\Services\Pingback;

class Gateway
{

    protected $config;
    protected $http;
    protected $baseUrl = '';
    protected $project;
    protected $extraParams = [];

    public function __construct($config = [])
    {
        if (is_array($config)) {
            $config = new Config($config);
        }

        $this->config = $config;

        $header = [
            'Authorization: Bearer' . $this->config->getPublicKey(),
            'Content-Type: application/json'
        ];

        $this->http = new HttpClient($header);
    }

    protected function getBaseUrl()
    {
        if (!$url = $this->config->getApiBaseUrl()) {
            $url = $this->baseUrl;
        }

        return $url . '/';
    }

    public function getEndPoint($endpoint)
    {

        return $this->getBaseUrl() . $endpoint;
    }

    public function getHttpClient()
    {
        return $this->http;
    }

    public function paymentForm()
    {
        return new PaymentForm($this);
    }

    public function signature()
    {
        return new Signature($this);
    }

    public function pingback()
    {
        return new Pingback($this);
    }

    public function subscriptionService()
    {
        return new Subscription(new HttpService($this));
    }

    public function paymentService()
    {
        return new Payment(new HttpService($this));
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function callApi($endpoint, array $payload, $method = GenericApiService::HTTP_METHOD_POST, $header = [])
    {
        $endpoint = $this->getEndPoint($endpoint);

        $service = new GenericApiService(new HttpClient);
        return $service->call($endpoint, $payload, $method, $header);
    }
}
