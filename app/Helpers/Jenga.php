<?php

namespace App\Helpers;

use App\Models\EquityToken;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use App\Jobs\EquityTokenJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Termwind\Components\Dd;

class Jenga
{
    public $username;
    public $password;
    public $api_key;
    public $phone;
    public $account_number;
    public $endpoint;
    public $source_name;
    // public $token;

    public function __construct()
    {
        $this->username = config('app.equity_api_username') ?? '';
        $this->password = config('app.equity_api_password') ?? '';
        $this->api_key = config('app.equity_api_key') ?? '';
        $this->phone = config('app.equity_api_phone') ?? '';
        $this->account_number = config('app.equity_api_account_number') ?? '';
        $this->endpoint = config('app.equity_api_base_endpoint') ?? '';
        $this->source_name = config('app.equity_api_alert_username') ?? '';
        // $this->token = $this->authenticate() ?? '';
        // $this->token = $this->getBearerToken()->accessToken ?? '';
    }


    public static function jengaToken(){
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

        // $token = $this->jengaToken();
        $response = Http::withHeaders([
            'Api-Key' => config('app.equity_api_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api-finserve-prod.azure-api.net/authentication/api/v3/authenticate/merchant', [
            'merchantCode' => config('app.equity_api_username'),
            'consumerSecret' => config('app.equity_api_password'),
        ]);
        if ($response->successful()) {
            $token  = $response->json()['accessToken'];
            // dd($token);
        


        // if (!$token) {
        //     return response()->json(['error' => 'true', 'message' => 'Token not found']);
        // }
            $params = [
                'accountID' => $this->account_number,
                'countryCode' => 'KE',
            ];
            $defaults = [
                'accountID' => $this->account_number,
                'countryCode' => 'KE',
                'date' => date('Y-m-d'),
            ];
            
            
            $params = array_merge($defaults, $params);
            // dd($params);
        
            // $token = $this->token;
            try {
                $client = new Client();
                // https://uat.finserve.africa/v3-apis/v3.0/accounts/balances/{countryCode}/{accountId}
                $request = $client->request('GET', 'https://uat.finserve.africa/v3-apis/v3.0/accounts/balances/'.$params['countryCode'].'/'.$params['accountID'], [
                    'headers' => [
                    'Content-type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                    'signature' =>  $this->generateSignature($params['countryCode']. $params['accountID']),
                    ],
                ]);
                $response = json_decode($request->getBody()->getContents());
                
                return $response;
            } catch (RequestException $e) {
                return (string) $e->getResponse()->getBody();
            }
        }
    }


    public function generateSignature($data)
    {
        // dd($data);
        $plainText  = $data;
        $try = file_get_contents(storage_path('app/keys/private.pem'));
        // dd($try);
        $privateKey = openssl_pkey_get_private($try, env('EQUITY_PRIVATE_KEY_PASSWD'));
        // dd($privateKey);
        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        // dd(base64_encode($signature));
        return base64_encode($signature);
    }  


    public function sendMoney($params)
    {
        $params= [
            'type' => 'MobileWallet',

        ];
        $defaults = [
            'country_code' => 'KE',
            'source_name' => $this->source_name,
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
        dd($params);

        if ($params['type'] != null && $params['type'] == 'MobileWallet') {
            
            return $this->sendMobileMoney($params = null);
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
    public  function sendMobileMoney($params){
    
        $defaults = [
            'country_code' => 'KE',
            'source_name' => $this->source_name,
            'source_accountNumber' => $this->account_number,
            'customer_name' =>'Fredrick Mwenda',
            'customer_mobileNumber' => '0713723353',
            'wallet_name' => 'Mpesa',
            'currencyCode' => 'KES',
            'amount' => '10000',
            'type' => 'MobileWallet', 
            'reference' => rand(100000000000, 999999999999),
            'date' => date('Y-m-d'),
            'description' => 'Test',
        ];
        $params = array_merge($defaults, $params);
        // dd($params['amount'].$params['currencyCode'].$params['reference'].$params['source_accountNumber']);
        $token = $this->jengaToken();

        if (!$token) {
            return response()->json(['error' => 'true', 'message' => 'Token not found', 'status' => 401]);
        }
        try {
            $client = new Client();
            $request = $client->request('POST', 'https://api.finserve.africa/v3-apis/transaction-api/v3.0/remittance/sendmobile', [
                'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
                'signature' => $this->generateSignature($params['amount'].$params['currencyCode'].$params['reference'].$params['source_accountNumber']),
                ],
                'json' => [
                    'source' => [
                        'countryCode' => $params['country_code'],
                        'name' => $params['source_name'],
                        'accountNumber' => $params['source_accountNumber'],
                    ],
                    'destination' => [
                        "type" => "mobile",
                        "countryCode" => $params['country_code'],
                        "name" => $params['customer_name'],
                        "mobileNumber" => $params['customer_mobileNumber'],
                        "walletName" => $params['wallet_name'],
                    ],
                    'transfer' => [
                        'type' => $params['type'],
                        'amount' => $params['amount'],
                        'currencyCode' => $params['currencyCode'],
                        'reference' => $params['reference'],
                        'date' => $params['date'],
                        'description' => $params['description'],
                    ],
                ],
                
            ]
            );

            $response = json_decode($request->getBody()->getContents());
            //response is in stdClass format, therefore throwing this error Object of class stdClass could not be converted to string when trying to log the response
            //so we have to convert it to json format
            $logged_data = json_encode($response);
            Log::info($logged_data);
            // dd($response);
            return $response;
        } catch (RequestException $e) {
            Log::info('Send Mobile Money Error: '.$e->getResponse()->getBody());
            return (string) $e->getResponse()->getBody();
        }
    }

    //Transaction Status callback after sendMobileMoney request is successfull
    public function transactionStatus($data){
        Log::info('Transaction Status: '.$data);
        // $data = $request->all();
        // Log::info($data);
        // call the api with the transaction id to check on the payment status
        $token =  $this->jengaToken();

        if (!$token) {
            return response()->json(['error' => 'true', 'message' => 'Token not found']);
        }
        try {
            $client = new Client();
            $request = $client->request('POST', 'https://sandbox.jengahq.io/transaction-test/v2/b2c/status/query', [
                'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
                ],
                'body' => json_encode([
                    'requestId' => $data['requestId'],
                    'destination' => [
                        'type' =>"M-Pesa"
                    ],
                    'transfer' => [
                        'date' => $data['date'],
                    ],
                ]),
            ]);

            $response = json_decode($request->getBody()->getContents());
            Log::info($response);
            return $response;
        } catch (RequestException $e) {
            return (string) $e->getResponse()->getBody();
        }
    }


  //send from equity account 




}