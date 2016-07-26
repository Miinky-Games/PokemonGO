<?php
namespace App\PayPalService;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

class PayPalApiContext
{
    private $apiContext;

    public function __construct()
    {
        $payPalCredentials = json_decode(file_get_contents('../assets/credentials.json'), true)['PayPal'];
        //live
        /*$live = $payPalCredentials['live'];
        $this->apiContext = new ApiContext(new OAuthTokenCredential($live['clientID'],$live['secrets'][rand(0,1)]));
        $this->apiContext->setConfig(array(
            'mode'=>'live',
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' =>'../logs/PayPal.log',
            'log.LogLevel' => 'FINE'
        ));*/
        //sandbox
        $sandbox = $payPalCredentials['sandbox'];
        $this->apiContext = new ApiContext(new OAuthTokenCredential($sandbox['clientID'], $sandbox['secret']));

    }

    public function GetApiContext()
    {
        return $this->apiContext;
    }
}