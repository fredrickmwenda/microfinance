<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Transaction;
use App\Models\customer;
use App\Models\Loan;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::with('payment_gateway', 'user', 'customer', 'loan')->paginate(100);
        return view('transaction.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get customers with status as disbursed or active
        $customers = customer::with('loans')->whereHas('loans', function($query){
            $query->where('status', 'disbursed')->orWhere('status', 'active');
        })->get();
        // dd($customers);
        $branches = branch::where('status', 'active')->get();
        return view('transaction.create', compact('customers', 'branches') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'customer_id' => 'required',
            'total_amount' => 'required',
            'payment_gateway_id' => 'required',
            'loan_id' => 'required',
            'transaction_type' => 'required',
            'transaction_status' => 'required',
            'transaction_reference' => 'required',
            'transaction_date' => 'required',
            'transaction_code' => 'required',
            'transaction_amount' => 'required',
        ]);

         //check if this is the first transaction for this loan
        $loan = Loan::find($request->loan_id);
        $transaction = Transaction::where('loan_id', $request->loan_id)->first();

        if($transaction){
            //if not first transaction, check if the transaction is a repayment
            if($request->transaction_type == 'repayment'){
                if($loan->status == 'active'){
                    //if the loan is active, check if the loan is fully repaid
                    if($loan->remaining_balance == 0){
                        return redirect()->route('transaction.index')->with('error', 'Loan is fully repaid');
                    }
                    else{
                        //if the loan is not fully repaid, update the loan status to active
                        //check if the transaction amount is greater than the remaining balance
                        if($request->transaction_amount > $loan->remaining_balance){

                            return redirect()->route('transaction.index')->with('error', 'Transaction amount is greater than the remaining balance');
                        }
                        else if ($request->transaction_amount == $loan->remaining_balance){
                            //if the transaction amount is equal to the remaining balance, update the loan status to fully repaid
                            $loan->status = 'fully_repaid';
                            $loan->payment_status = 'fully_repaid';
                            
                            //update the remaining balance
                            $loan->remaining_balance = $loan->remaining_balance - $request->transaction_amount;
                            $loan->save();
                            //save the transaction
                            $transaction = Transaction::create([
                                'customer_id' => $request->customer_id,
                                'total_amount' => $request->total_amount,
                                'payment_gateway_id' => $request->payment_gateway_id,
                                'loan_id' => $request->loan_id,
                                'transaction_type' => $request->transaction_type,
                                'transaction_status' => $request->transaction_status,
                                'transaction_reference' => $request->transaction_reference,
                                'transaction_date' => $request->transaction_date,
                                'transaction_code' => $request->transaction_code,
                                'transaction_amount' => $request->transaction_amount,
                                'user_id' => auth()->user()->id,
                            ]);
                            
                            return redirect()->route('transaction.index')->with('success', 'Transaction created successfully');
                        }
                        else{
                            //if the transaction amount is less than the remaining balance, update the loan status to active
                            $loan->status = 'active';
                            $loan->payment_status = 'in_repayment';
                            $loan->remaining_balance = $loan->remaining_balance - $request->transaction_amount;
                            $loan->save();
                            //create the transaction
                            $transaction = Transaction::create([
                                'customer_id' => $request->customer_id,
                                'total_amount' => $request->total_amount,
                                'payment_gateway_id' => $request->payment_gateway_id,
                                'loan_id' => $request->loan_id,
                                'transaction_type' => $request->transaction_type,
                                'transaction_status' => $request->transaction_status,
                                'transaction_reference' => $request->transaction_reference,
                                'transaction_date' => $request->transaction_date,
                                'transaction_code' => $request->transaction_code,
                                'transaction_amount' => $request->transaction_amount,
                                'user_id' => auth()->user()->id,
                            ]);
                            return redirect()->route('transaction.index')->with('success', 'Transaction created successfully');
                        }

                    }


                }
                //create the transaction
                $transaction = Transaction::create([
                    'customer_id' => $request->customer_id,
                    'total_amount' => $request->total_amount,
                    'payment_gateway_id' => $request->payment_gateway_id,
                    'loan_id' => $request->loan_id,
                    'transaction_type' => $request->transaction_type,
                    'transaction_status' => $request->transaction_status,
                    'transaction_reference' => $request->transaction_reference,
                    'transaction_date' => $request->transaction_date,
                    'transaction_code' => $request->transaction_code,
                    'transaction_amount' => $request->transaction_amount,
                    'user_id' => auth()->user()->id,
                ]);
            }
        }else{
            //if this is the first transaction for this loan, check if the loan is disbursed
            $loan = Loan::find($request->loan_id);
            if($loan->status == 'disbursed'){
                $loan->remaining_balance = $loan->remaining_balance - $request->transaction_amount;

                //if the loan is disbursed, check if the transaction amount is greater than the remaining balance
                if($request->transaction_amount > $loan->remaining_balance){
                    return redirect()->route('transaction.index')->with('error', 'Transaction amount is greater than the remaining balance');
                }
                else if ($request->transaction_amount == $loan->remaining_balance){
                    //if the transaction amount is equal to the remaining balance, update the loan status to fully repaid
                    $loan->status = 'closed';
                    $loan->payment_status = 'fully_repaid';
                    $loan->remaining_balance = $loan->remaining_balance - $request->transaction_amount;
                    $loan->save();
                    //save the transaction
                    $transaction = Transaction::create([
                        'customer_id' => $request->customer_id,
                        'total_amount' => $request->total_amount,
                        'payment_gateway_id' => $request->payment_gateway_id,
                        'loan_id' => $request->loan_id,
                        'transaction_type' => $request->transaction_type,
                        'transaction_status' => $request->transaction_status,
                        'transaction_reference' => $request->transaction_reference,
                        'transaction_date' => $request->transaction_date,
                        'transaction_code' => $request->transaction_code,
                        'transaction_amount' => $request->transaction_amount,
                        'user_id' => auth()->user()->id,
                    ]);
                    
                    return redirect()->route('transaction.index')->with('success', 'Transaction created successfully');
                }
                else{
                    //if the transaction amount is less than the remaining balance, update the loan status to active
                    $loan->status = 'active';
                    $loan->payment_status = 'in_repayment';
                    $loan->remaining_balance = $loan->remaining_balance - $request->transaction_amount;
                    $loan->save();
                    //create the transaction
                    $transaction = Transaction::create([
                        'customer_id' => $request->customer_id,
                        'total_amount' => $request->total_amount,
                        'payment_gateway_id' => $request->payment_gateway_id,
                        'loan_id' => $request->loan_id,
                        'transaction_type' => $request->transaction_type,
                        'transaction_status' => $request->transaction_status,
                        'transaction_reference' => $request->transaction_reference,
                        'transaction_date' => $request->transaction_date,
                        'transaction_code' => $request->transaction_code,
                        'transaction_amount' => $request->transaction_amount,
                        'user_id' => auth()->user()->id,
                       ]);
                    return redirect()->route('transaction.index')->with('success', 'Transaction created successfully'); 
                }
            }
        }

        // return redirect()->route('transaction.index')->with('success', 'Transaction created successfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    // jenga Api webhook
    //register the webhook that accepts equity paybill number, for  mpesa payments



    //getJengaTransaction using bank account number, but shortcode for mpesa is 247247






    public function registerWebhoo1k(Request $request)
    {
        $client = new Client();
        $response = $client->request('POST', 'https://sandbox.equitybankgroup.com/v1/webhooks', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'url' => 'https://yourdomain.com/webhook',
                'events' => [
                    'PAYMENT_RECEIVED',
                    'PAYMENT_FAILED',
                    'PAYMENT_REVERSED',
                    'PAYMENT_REVERSED_FAILED',
                    'PAYMENT_REVERSED_SUCCESSFUL',
                    'PAYMENT_SUCCESSFUL',
                    'PAYMENT_UPDATED',
                    'PAYMENT_UPDATED_FAILED',
                    'PAYMENT_UPDATED_SUCCESSFUL',
                    'PAYMENT_VOIDED',
                    'PAYMENT_VOIDED_FAILED',
                    'PAYMENT_VOIDED_SUCCESSFUL',
                ],
                'paybill' => $request->paybill,
            ],
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }
    public function registerWebhook(Request $request)
    {
        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
        $token = $this->generateAccessToken();
        $headers = ['Content-Type:application/json','Authorization:Bearer '.$token];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); //setting custom header

        $curl_post_data = array(
            //Fill in the request parameters with valid values
            'ShortCode' => '600000',
            'ResponseType' => 'Completed',
            'ConfirmationURL' => 'https://yourdomain.com/mpesa/confirmation',
            'ValidationURL' => 'https://yourdomain.com/mpesa/validation_url'
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);
        print_r($curl_response);

        echo $curl_response;
    }

    //generate access token
    public function generateAccessToken()
    {
        $consumer_key = 'your_consumer_key';
        $consumer_secret = 'your_consumer_secret';
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $curl_response = curl_exec($curl);

        $access_token = json_decode($curl_response)->access_token;

        return $access_token;
    }

    //simulate a transaction
    public function simulateTransaction(Request $request)
    {
        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
        $token = $this->generateAccessToken();
        $headers = ['Content-Type:application/json','Authorization:Bearer '.$token];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); //setting custom header

        $curl_post_data = array(
            //Fill in the request parameters with valid values
            'ShortCode' => '600000',
            'CommandID' => 'CustomerPayBillOnline',
            'Amount' => '1',
            'Msisdn' => '254708374149',
            'BillRefNumber' => '123456'
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);
        print_r($curl_response);

        echo $curl_response;
    }


    //validation url
    public function validation_url(Request $request)
    {
        $mpesaResponse = $request->all();
        $jsonMpesaResponse = json_encode($mpesaResponse);
        $logFile = 'validation_url.txt';
        $log = fopen($logFile, "a");
        fwrite($log, $jsonMpesaResponse);
        fclose($log);
    }

    //confirmation url
    public function confirmation(Request $request)
    {
        $mpesaResponse = $request->all();
        $jsonMpesaResponse = json_encode($mpesaResponse);
        $logFile = 'confirmation.txt';
        $log = fopen($logFile, "a");
        fwrite($log, $jsonMpesaResponse);
        fclose($log);
    }


    public function jengaWebhook()
    {
        // The webhook is called when a transaction is made
        $type =$_GET['phone'];

        Log::info($type);

        // Log::info($request->all());

        // //get the customer webhook data
        // $customer = Customer::where('customer_phone', $request->phone)->first();

        // //get the loan for the customer, where status is disbursed, active or overdue
        // $loan = Loan::where('customer_id', $customer->id)->where('status', 'disbursed')->orWhere('status', 'active')->orWhere('status', 'overdue')->first();

        // //update the loan status to active
        // $loan->status = 'active';
        // //update the loan payment status to in_repayment
        // $loan->payment_status = 'in_repayment';
        // //update the loan remaining balance
        // $loan->remaining_balance = $loan->remaining_balance - $request->amount;
        // $loan->save();

        // //create loan payment transaction
        // LoanPayment::create([
        //     'customer_id' => $customer->id,
        //     'loan_id' => $loan->id,
        //     'amount' => $request->amount,
        //     'payment_date' => $request->date,
        //     'payment_reference' => $request->reference,
        //     'payment_status' => $request->status,
        //     'payment_gateway' => $request->gateway,
        // ]);

        // //create transaction
        // Transaction::create([
        //     'customer_id' => $customer->id,
        //     'total_amount' => $request->amount,
        //     'payment_gateway_id' => 1,
        //     'loan_id' => $loan->id,
        //     'transaction_type' => 'loan',
        //     'transaction_status' => $request->status,
        //     'transaction_reference' => $request->reference,
        //     'transaction_date' => $request->date,
        //     'transaction_code' => $request->code,
        //     'transaction_amount' => $request->amount,
        // ]);

        // return response()->json(['success' => 'Transaction created successfully'], 200);
    }



}
