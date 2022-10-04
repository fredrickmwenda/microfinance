<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Env;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment_gateways = PaymentGateway::all();
        return view('payment-gateways.index', compact('payment_gateways'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payment-gateways.create');
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',           
            'status' => 'required',
            'logo' => 'required',
            
        ]);
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('assets/images/payment-gateways/', $filename);
        }

        $payment_gateway = new PaymentGateway([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'status' => $request->get('status'),
            'logo' => $filename,
            'paybill' => $request->get('paybill'),
            'settings' => $request->get('settings'),
            'env_merchant_id' => $request->get('env_merchant_id'),
            'env_merchant_key' => $request->get('client_key'),
            'env_merchant_secret' => $request->get('client_secret'),
            'env_merchant_account' => $request->get('env_merchant_account'),
            'env_merchant_email' => $request->get('env_merchant_email'),
        ]);
        $payment_gateway->save();
        return redirect()->route('admin.payment-gateways.index')->with('success', 'Payment Gateway created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentGateway  $paymentGateway
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentGateway $paymentGateway)
    {
        return view('payment-gateways.show', compact('paymentGateway'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentGateway  $paymentGateway
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentGateway $paymentGateway)
    {
        $paymentGateway = PaymentGateway::find($paymentGateway->id);
        return view('payment-gateways.edit', compact('paymentGateway'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentGateway  $paymentGateway
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
            'logo' => 'required',
            'settings' => 'required',
        ]);
        $paymentGateway->update($request->all());
        return redirect()->route('payment-gateways.index')->with('success', 'Payment Gateway updated successfully');

    }


    //disable payment gateway
    public function disable(PaymentGateway $paymentGateway)
    {
        $paymentGateway = PaymentGateway::find($paymentGateway->id);
        $paymentGateway->status = "disabled";
        $paymentGateway->save();
        return redirect()->route('payment-gateways.index')->with('success', 'Payment Gateway disabled successfully');
    }

    //connect payment gateway
    public function connect($id)
    {
        $paymentGateway = PaymentGateway::find($id);
        $paymentGateway->status = "active";
        $paymentGateway->save();
        return redirect()->route('payment-gateways.index')->with('success', 'Payment Gateway connected successfully');
    }

    //disconnect payment gateway
    public function disconnect($id)
    {
        $paymentGateway = PaymentGateway::find($id);
        $paymentGateway->status = "in-active";
        $paymentGateway->save();
        return redirect()->route('payment-gateways.index')->with('success', 'Payment Gateway disconnected successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentGateway  $paymentGateway
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentGateway = PaymentGateway::find($id);
        $paymentGateway->delete();
        return redirect()->route('payment-gateways.index')->with('success', 'Payment Gateway deleted successfully');
    }

    public function callback_status($reqId)
    {
        # code...

        if (CallbackResponse::where('transaction_id', $reqId)->first() == null) {
            # code...
            return 'null';
        }
        return CallbackResponse::where('transaction_id', $reqId)->first()->status;
    }

    //B2C Mpesa
    public function b2c(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'phone' => 'required',
            'reference' => 'required',
            'description' => 'required',
        ]);
        $paymentGateway = PaymentGateway::where('name', 'mpesa')->first();
        // $settings = json_decode($paymentGateway->settings);
        // $mpesa_consumer_key = $settings->mpesa_consumer_key;
        $mpesa_consumer_key = env('MPESA_CONSUMER_KEY');
        $mpesa_consumer_secret = env('MPESA_CONSUMER_SECRET');
        $mpesa_passkey = env('MPESA_PASSKEY');
        $mpesa_shortcode = env('MPESA_SHORTCODE');
        $mpesa_initiator_name = env('MPESA_INITIATOR_NAME');
        $mpesa_security_credential = env('MPESA_SECURITY_CREDENTIAL');
        $mpesa_callback_url = env('MPESA_CALLBACK_URL');
        $mpesa_timeout_url = env('MPESA_TIMEOUT_URL');
        $mpesa_result_url = env('MPESA_RESULT_URL');
        $mpesa_queue_timeout_url = env('MPESA_QUEUE_TIMEOUT_URL');
        $mpesa_transaction_type = env('MPESA_TRANSACTION_TYPE');
        $mpesa_party_a = env('MPESA_PARTY_A');
        $mpesa_party_b = env('MPESA_PARTY_B');
        $mpesa_account_reference = env('MPESA_ACCOUNT_REFERENCE');
        $mpesa_transaction_desc = env('MPESA_TRANSACTION_DESC');
        $mpesa_command_id = env('MPESA_COMMAND_ID');
        $mpesa_amount = $request->amount;
        $mpesa_party_b = $request->phone;
        $mpesa_initiator_name = $request->reference;
        $mpesa_transaction_desc = $request->description;
        $mpesa_timestamp = date('YmdHis');
        $mpesa_password = base64_encode($mpesa_shortcode . $mpesa_passkey . $mpesa_timestamp);
        $mpesa_access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $mpesa_access_token_credentials = base64_encode($mpesa_consumer_key . ':' . $mpesa_consumer_secret);
        $mpesa_access_token_headers = array(
            'Authorization: Basic ' . $mpesa_access_token_credentials,
            'Content-Type: application/json'
        );
        $mpesa_access_token = $this->curl($mpesa_access_token_url, null, $mpesa_access_token_headers);
        $mpesa_access_token = json_decode($mpesa_access_token);
        $mpesa_access_token = $mpesa_access_token->access_token;
        $mpesa_b2c_url = 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
        $mpesa_b2c_headers = array(
            'Authorization: Bearer ' . $mpesa_access_token,
            'Content-Type: application/json'
        );
        $mpesa_b2c_data = array(
            'InitiatorName' => $mpesa_initiator_name,
            'SecurityCredential' => $mpesa_security_credential,
            'CommandID' => $mpesa_command_id,
            'Amount' => $mpesa_amount,
            'PartyA' => $mpesa_party_a,
            'PartyB' => $mpesa_party_b,
            'Remarks' => $mpesa_transaction_desc,
            'QueueTimeOutURL' => $mpesa_queue_timeout_url,
            'ResultURL' => $mpesa_result_url,
            'Occasion' => $mpesa_transaction_desc
        );
        $mpesa_b2c_data = json_encode($mpesa_b2c_data);
        $mpesa_b2c = $this->curl($mpesa_b2c_url, $mpesa_b2c_data, $mpesa_b2c_headers);
        $mpesa_b2c = json_decode($mpesa_b2c);
        return $mpesa_b2c;

        // $curl_outh = curl_init();
        // curl_setopt(
        //     $curl_outh, 
        //     CURLOPT_URL, 
        //     'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest'

        // );
        // curl_setopt($curl_outh, CURLOPT_HTTPHEADER, array(
        //     'Content-Type:application/json',
        //     'Authorization:Bearer ' . $settings->access_token
        // ));

        // $mpesa = new Mpesa($settings->env_merchant_key, $settings->env_merchant_secret, $settings->env_merchant_passkey, $settings->env_merchant_shortcode, $settings->env_merchant_initiator, $settings->env_merchant_security, $settings->env_merchant_timeout, $settings->env_merchant_type, $settings->env_merchant_queue, $settings->env_merchant_result, $settings->env_merchant_confirmation, $settings->env_merchant_validation);
        // $mpesa->b2c($request->amount, $request->phone, $request->reference, $request->description);
        // return redirect()->route('payment-gateways.index')->with('success', 'Payment Gateway created successfully.');
    }
    // public function makePayment($source, $customer_id, $loan_id, $phonee, $amount)
    // {

    //     $stk_request_url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    //     $outh_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    //     $safaricom_pass_key = "c944dda39b228671623e4269224f9e03ef92a45190aa2c8267dd43708b2b11c0";
    //     $safaricom_party_b = "720455"; //"572077";
    //     $safaricom_bussiness_short_code = "720455"; //"572077";
    //     $safaricom_Auth_key = "i5nzZJPq0pd4lprLMK77T1mi2IS0RC6t";
    //     $safaricom_Secret = "usx82pvDtj4bR0RA";
    //     // AUTHENTIFICATION PART
    //     $outh = $safaricom_Auth_key . ':' . $safaricom_Secret;
    //     $curl_outh = curl_init($outh_url);
    //     curl_setopt($curl_outh, CURLOPT_RETURNTRANSFER, 1);
    //     $credentials = base64_encode($outh);
    //     curl_setopt($curl_outh, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
    //     curl_setopt($curl_outh, CURLOPT_HEADER, false);
    //     curl_setopt($curl_outh, CURLOPT_SSL_VERIFYPEER, false);
    //     $curl_outh_response = curl_exec($curl_outh);
    //     // I GET THE  RESPONSE HERE THAT HAS OUR ACCESS TOKEN
    //     $json = json_decode($curl_outh_response, true);
    //     // ACCESS TOKEN IS $json['access_token']
    //     $time = date("YmdHis", time());
    //     $password = $safaricom_bussiness_short_code . $safaricom_pass_key . $time;
    //     $curl_stk = curl_init();
    //     $id = 'test';
    //     curl_setopt($curl_stk, CURLOPT_URL, $stk_request_url);
    //     curl_setopt($curl_stk, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $json['access_token'])); //setting custom header
    //     //PREPARE THE PARAMETER FOR THE STK CALL
    //     //MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMTgwODE0MDg1NjIw

    //     # code...
    //     $curl_post_data = array(
    //         'BusinessShortCode' => '720455',
    //         'Password' => base64_encode($password),
    //         'Timestamp' => $time,
    //         'TransactionType' => 'CustomerPayBillOnline',
    //         'Amount' => $amount,
    //         'PartyA' => $phonee,
    //         'PartyB' => '720455',
    //         'PhoneNumber' => $phonee,
    //         'CallBackURL' => 'https://zalegoacademy.ac.ke/public/api/enrollment_callback?user_id=' . $user_id . '&course_id=' . $course_id,
    //         'AccountReference' => 'Zalego Academy',
    //         'TransactionDesc' => ' Zalego Course Enrollment Payment'
    //     );

    //     $data_string = json_encode($curl_post_data);
    //     curl_setopt($curl_stk, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($curl_stk, CURLOPT_POST, true);
    //     curl_setopt($curl_stk, CURLOPT_HEADER, false);
    //     curl_setopt($curl_stk, CURLOPT_POSTFIELDS, $data_string);
    //     $curl_stk_response = curl_exec($curl_stk);
    //     $jsonData = json_decode($curl_stk_response, true);
    //     return $jsonData;
    // }

}
