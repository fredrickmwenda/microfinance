<?php

namespace App\Jobs;

use App\Models\EquityToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class EquityTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $jengaAccount;
    protected $jengaToken;
    protected $url;
    protected $headers;
    protected $body;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->jengaAccount = JengaAccount::where('active', 1)->first();
        $this->jengaToken = EquityToken::orderBy('id', 'desc')->first();
        // $this->url = config('jenga.auth_url') . '/authenticate/merchant';
        $this->url = "https://uat.finserve.africa/authentication/api/v3/authenticate/merchant";
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Api-Key' => config('app.equity_api_key'),
        ];
        $this->body = [
            'merchantCode' => config('app.equity_api_username'),
            'consumerSecret' => config('app.equity_api_password'),
        ];
    }

    /**
     * Execute the job. This should run every 10 minutes as the token expires within 15 minutes.
     *
     * @return void
     */
    public function handle()
    {
        //Check if any tokens exist in the database. If not, create one.
        if (!$this->jengaToken) {
            $response = Http::withHeaders($this->headers)->post($this->url, $this->body);
            if ($response->successful()) {
                $response = json_decode($response->getBody()->getContents());
                $jengaToken = new EquityToken();
                $jengaToken->merchant_code = config('app.equity_api_username');
                $jengaToken->access_token = $response->accessToken;
                $jengaToken->refresh_token = $response->refreshToken;
                $jengaToken->expires_in = strtotime($response->expiresIn);
                $jengaToken->issued_at = strtotime($response->issuedAt);
                $jengaToken->token_type = $response->tokenType;
                $jengaToken->save();
            }
            $this->release();
        } else {
            //Check if the token has expired. If it has, refresh the token.
            if (time() > $this->jengaToken->expires_in) {
                $response = Http::withHeaders($this->headers)->post($this->url, $this->body);
                if ($response->successful()) {
                    $response = json_decode($response->getBody()->getContents());
                    $jengaToken = EquityToken::find($this->jengaToken->id);
                    $jengaToken->access_token = $response->accessToken;
                    $jengaToken->refresh_token = $response->refreshToken;
                    $jengaToken->expires_in = strtotime($response->expiresIn);
                    $jengaToken->issued_at = strtotime($response->issuedAt);
                    $jengaToken->token_type = $response->tokenType;
                    $jengaToken->save();
                }
                $this->release();
            }
        }
    }
}

