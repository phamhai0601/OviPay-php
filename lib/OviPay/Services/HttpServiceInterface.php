<?php
namespace OviPay\Services;

interface HttpServiceInterface
{
    public function getHttpClient();
    public function getEndpoint();
}