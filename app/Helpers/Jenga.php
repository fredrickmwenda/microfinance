<?php

namespace App\Helpers;

use App\Models\EquityToken;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use App\Jobs\EquityTokenJob;

class Jenga
{
    public $username;
    public $password;
    public $api_key;
    public $phone;
    public $account_number;
    public $endpoint;
    // public $token;

    public function __construct()
    {
        $this->username = config('app.equity_api_username') ?? '';
        $this->password = config('app.equity_api_password') ?? '';
        $this->api_key = config('app.equity_api_key') ?? '';
        $this->phone = config('app.equity_api_phone') ?? '';
        $this->account_number = config('app.equity_api_account_number') ?? '';
        $this->endpoint = config('app.equity_api_base_endpoint') ?? '';
        // $this->token = $this->authenticate() ?? '';
        // $this->token = $this->getBearerToken()->accessToken ?? '';
    }



    public function getBearerToken()
    {
        // dd(env('EQUITY_CONSUMER_KEY'), env('EQUITY_CONSUMER_SECRET'), env('EQUITY_MERCHANT_ID'));
        // $url = config('jenga.auth_url') . '/authenticate/merchant';
        $url = 'https://uat.finserve.africa/authentication/api/v3/authenticate/merchant';
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Api-Key' => $this->api_key,
            // 'Api-Key' =>env('EQUITY_API_KEY'),
        ];
        $body = [
            // 'merchantCode' =>  env('EQUITY_MERCHANT_ID'),
            // 'consumerSecret' => env('EQUITY_CONSUMER_SECRET'),
            'merchantCode' =>  $this->username,
            'consumerSecret' => $this->password,
        ];
        $response = Http::withHeaders($headers)->post($url, $body);

        if ($response->successful()) {
            $response = json_decode($response->getBody()->getContents());
            return $response;
            // return $response->accessToken;
        }

        return response()->json(['error' => 'true', 'message' => json_decode($response->getBody()->getContents())]);
    }

    // public function createToken(){

    // }

    public function jengaToken(){
        $token = EquityToken::first();
    if (!$token) {
        dispatch(new EquityTokenJob());

        $token = EquityToken::first();
        if ($token) {
            return $token->access_token;
        }

        return null;
    }
    //Check if the token exixts and has expired. If it has, refresh the token.
    if ($token && time() > $token->expires_in) {
        dispatch(new EquityTokenJob());

        $token = EquityToken::first();
        if ($token) {
            return $token->access_token;
        }

        return null;
    }
    return $token->access_token;
}



 
    // params can be null
    public function accountBalance()
    {

        $token = $this->jengaToken();


        if (!$token) {
            return response()->json(['error' => 'true', 'message' => 'Token not found']);
        }
        $params = [
            'accountID' => $this->account_number,
            'countryCode' => 'KE',
        ];
        $defaults = [
            'accountID' => 127381,
            'countryCode' => 'KE',
            'date' => date('Y-m-d'),
        ];
        
        
        $params = array_merge($defaults, $params);
        // dd($params);
        // $token = $this->token;
        try {
            $client = new Client();
            $request = $client->request('GET', 'https://uat.finserve.africa/v3-apis/account-api/v3.0/accounts/balances/'.$params['countryCode'].'/'.$params['accountID'], [
                'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
                'signature' =>  $this->generateSignature($params['countryCode']. $params['accountID']),
                ],
            ]);
            $response = json_decode($request->getBody()->getContents());
            // dd($response);
            return $response;
        } catch (RequestException $e) {
            return (string) $e->getResponse()->getBody();
        }
    }


    public function generateSignature($data)
    {
        // dd($data);
        $plainText  = $data;
        $try = file_get_contents(storage_path('app\keys\private.pem'));
        $privateKey = openssl_pkey_get_private($try, env('EQUITY_PRIVATE_KEY_PASSWD'));
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        // dd(base64_encode($signature));
        return base64_encode($signature);
    }   


    public function sendMoney()
    {
        $params= [
            'type' => 'MobileWallet',

        ];
        $defaults = [
            'country_code' => 'KE',
            'source_name' => 'Fredrick Mwenda',
            'source_accountNumber' => $this->account_number,
            'customer_name' =>'Fredrick Mwenda',
            'customer_mobileNumber' => '0713723353',
            'wallet_name' => 'Mpesa',
            'currencyCode' => 'KES',
            'amount' => '10',
            'type' => 'MobileWallet', 
            'reference' => rand(100000000000, 999999999999),
            'date' => date('Y-m-d'),
            'description' => 'Test',
        ];
        $params = array_merge($defaults, $params);

        if ($params['type'] != null && $params['type'] == 'MobileWallet') {
            
            return $this->sendMobileMoney($params);
        }

        if ($params['transfer_type'] == null) {
            return 'Please specify the transfer type';
        }
    }

    /**
     * Send money to a Mpesa number.
     * @param  array $params An array of data equired by the API to perform the request
     * @return Action         Call to the appropriate action
     */
    public static function sendMobileMoney(){

        $params= [
            'type' => 'MobileWallet',

        ];
        $defaults = [
            'country_code' => 'KE',
            'source_name' => 'Fredrick Mwenda',
            'source_accountNumber' => $this->account_number,
            'customer_name' =>'Fredrick Mwenda',
            'customer_mobileNumber' => '0713723353',
            'wallet_name' => 'Mpesa',
            'currencyCode' => 'KES',
            'amount' => '10',
            'type' => 'MobileWallet', 
            'reference' => rand(100000000000, 999999999999),
            'date' => date('Y-m-d'),
            'description' => 'Test',
        ];
        $params = array_merge($defaults, $params);

        $token = $this->jengaToken();

        if (!$token) {
            return response()->json(['error' => 'true', 'message' => 'Token not found']);
        }
        // $plainText = $params['transfer_amount'].$params['transfer_currencyCode'].$params['transfer_reference'].$params['source_accountNumber'];
        $token = $this->jengaToken();
       
        try {
            $client = new Client();
            $request = $client->request('POST', 'https://uat.finserve.africa/v3-apis/transaction-api/v3.0/remittance/sendmobile', [
                'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
                'signature' => $this->generateSignature($params['amount'].$params['currencyCode'].$params['reference'].$params['source_accountNumber']),
                ],
                'body' => json_encode([
                    'source' => [
                        'countryCode' =>$params['country_code'],
                        'accountNumber' => $params['source_accountNumber'],
                        'name' => $params['source_name'],
                    ],
                    'destination' => [
                        'type' => "mobile",
                        'countryCode' => $params['country_code'],
                        'name' => $params['customer_name'],
                        'mobileNumber' => $params['customer_mobileNumber'],
                        'walletName' =>  'Mpesa',
                    ],
                    'transfer' => [
                        'type' => $params['type'],
                        'amount' => $params['amount'],
                        'currencyCode' => $params['currencyCode'],
                        'reference' => $params['reference'],
                        'date' => $params['date'],
                        'description' => 'Test',

                    ],
                ]),

                

                // 'json' => [
                //     'source' => [
                //         'countryCode' => $params['country_code'],
                //         'name' => $params['source_name'],
                //         'accountNumber' => $params['source_accountNumber'],
                //     ],
                //     'destination' => [
                //         "type" => "mobile",
                //         "countryCode" => $params['country_code'],
                //         "name" => $params['customer_name'],
                //         "mobileNumber" => $params['customer_mobileNumber'],
                //         "walletName" => $params['wallet_name'],
                //     ],
                //     'transfer' => [
                //         'type' => $params['type'],
                //         'amount' => $params['amount'],
                //         'currencyCode' => $params['currencyCode'],
                //         'reference' => $params['reference'],
                //         'date' => $params['date'],
                //         'description' => $params['description'],
                //     ],
                // ],
                
                // 'body' => '{"source":{"countryCode": "'.$params['country_code'].'","name": "'.$params['source_name'].'","accountNumber": "'.$params['source_accountNumber'].'"},"destination":{"type":"mobile","countryCode": "'.$params['country_code'].'","name": "'.$params['customer_name'].'","mobileNumber": "'.$params['customer_mobileNumber'].'""walletName": "'.$params['wallet_name'].'"},"transfer":{"type":"MobileWallet","amount": "'.$params['transfer_amount'].'","currencyCode": "'.$params['transfer_currencyCode'].'","reference": "'.$params['transfer_reference'].'","date": "'.$params['date'].'","description": "some remarks here"}}',
                                        // 'reference' => $params['transfer_reference'],
            ]
            );

            $response = json_decode($request->getBody()->getContents());
            // dd($response);
            return $response;
        } catch (RequestException $e) {
            return (string) $e->getResponse()->getBody();
        }
    }


    // public function authenticate()
    // {
        
    //     try {
    //         $client = new \GuzzleHttp\Client();
    //         $response = $client->request('POST', 'https://uat.finserve.africa/authentication/api/v3/authenticate/merchant', [

    //             'headers'=>[
    //                 'Content-Type' => 'application/json',
    //                 'Accept' => 'application/json',
    //                 'Api-Key' =>env('EQUITY_API_KEY'),
    //             ],
    //             'form_params' => [
    //                 'merchantCode' => env('EQUITY_MERCHANT_ID'),
    //                 'consumerSecret' => env('EQUITY_CONSUMER_SECRET'),
    //               ],
    //           ]);
    //         $response = json_decode($response->getBody()->getContents());

    //         return $response->AccessToken;


    //     } catch (RequestException $e) {
    //         return (string) $e->getResponse()->getBody();
    //     }
    // }







}