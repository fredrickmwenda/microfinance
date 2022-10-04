<?php

namespace App\Http\Controllers;

use App\Models\CallbackResponse;
use App\Models\customer;
use App\Models\Disburse;
use App\Models\Loan;
use App\Models\Transaction;
use App\Notifications\DisbursementNotification;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class DisburseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $disbursements = Disburse::paginate();
        
        return view('disburse.index', compact('disbursements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $customer = customer::with('loans')->whereHas('loans', function($query){
            $query->where('loan_status', 'approved');
        })->get();

        return view('disburse.create', compact('customer'));
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
    public function b2cMpesa($phone, $amount, $description, $loan_id, $customer_id)
    {

        //check if phone number is in the format 2547xxxxxxxx
        if (!(substr($phone, 0, 4) == "2547")) {
            //if not, add 2547 to the beginning of the phone number
            //check if the phone number is in the format 07xxxxxxxx
            if (substr($phone, 0, 2) == "07") {
                //remove the 0 from the beginning of the phone number
                $phone = substr($phone, 1);
                //add 254 to the beginning of the phone number
                $phone = "254" . $phone;
            }
        }
        
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
        // $mpesa_amount = $amount;
        $mpesa_amount = 1;
        $mpesa_party_b = $phone;
        $mpesa_initiator_name = env('MPESA_INITIATOR_NAME');
        $mpesa_transaction_desc = $description;

        $mpesa_access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $mpesa_access_token_credentials = base64_encode($mpesa_consumer_key . ':' . $mpesa_consumer_secret);
        
        $mpesa_access_token_headers = array(
            'Authorization: Basic ' . $mpesa_access_token_credentials,
            'Content-Type: application/json'
        );
        //start curl for access token, this is the first step. the access token is required for the next step, which is the actual transaction BY THE INITIATOR
        $curl_outh =curl_init($mpesa_access_token_url);
        curl_setopt($curl_outh, CURLOPT_HTTPHEADER, $mpesa_access_token_headers);
        curl_setopt($curl_outh, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl_outh, CURLOPT_HEADER, FALSE);
        curl_setopt($curl_outh, CURLOPT_SSL_VERIFYPEER, FALSE);
        $mpesa_access_token_response = curl_exec($curl_outh);   
        $mpesa_access_token_result = json_decode($mpesa_access_token_response, true);
        $mpesa_access_token = $mpesa_access_token_result['access_token'];
        // \Log::info($mpesa_access_token);
        //use bearer token to get access token
        $mpesa_timestamp = date('YmdHis');
        $mpesa_password = base64_encode($mpesa_shortcode . $mpesa_passkey . $mpesa_timestamp);



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
            //quueue timeout url is in apis on local host
            'QueueTimeOutURL' => 'https://3001-197-156-191-14.eu.ngrok.io/api/mpesa/b2c/timeout',
            //in the result url, we will be passing the loan id and customer id
            'ResultURL' =>'https://3001-197-156-191-14.eu.ngrok.io/api/mpesa/b2c/result?loan_id='.$loan_id.'&customer_id='.$customer_id,
            // 'ResultURL' => $mpesa_result_url . '/' . $loan_id . '/' . $customer_id,
            'Occasion' => ''
        );
        $curl_b2c = curl_init($mpesa_b2c_url);
        // \Log::info($mpesa_b2c_data);
        $data_string = json_encode($mpesa_b2c_data);
        // \Log::info($data_string);
        curl_setopt($curl_b2c, CURLOPT_HTTPHEADER, $mpesa_b2c_headers);
        curl_setopt($curl_b2c, CURLOPT_POST, 1);
        curl_setopt($curl_b2c, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl_b2c, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl_b2c, CURLOPT_SSL_VERIFYPEER, FALSE);
        $mpesa_b2c_response = curl_exec($curl_b2c);
        $mpesa_b2c_result = json_decode($mpesa_b2c_response, true);
        // $mpesa_b2c_result = json_decode($mpesa_b2c_response, true);
        \Log::info('mpesa_b2c_result');
        \Log::info($mpesa_b2c_result);
        return $mpesa_b2c_result;

    }

    //disburse loan
    public function disburseLoanStore(Request $request, $id)
    {
        //validate request
        $request->validate([
            'amount' => 'required',
            'payment_method' => 'required',
            'loan_amount' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
        ]);

        //check if amount to be disbursed is less than or equal to loan amount
        if($request->amount <= $request->loan_amount){
            //check if payment method is mpesa
            if($request->payment_method == 'mpesa'){
                //B2C Mpesa API
                $phone = $request->customer_phone;
                $amount = $request->amount;
                $description = $request->description;
                //call mpesa function from this controller
                //get customer id from loan id
                $check = Loan::with('customer')->where('loan_id', $id)->first();
                $customer_id = $check->customer_id;
                $loan_id = $check->loan_id;
                
                
                $mpesa = $this->b2cMpesa($phone, $amount, $description, $customer_id, $loan_id);
                //CHECK IF RETURNED aRRAY HAS ERROR
                if(isset($mpesa['errorCode'])){
                    return redirect()->back()->with('error', $mpesa['errorMessage']);
                }
                else if(isset($mpesa['ResponseCode'])){
                     //check if response code is 0
                    if($mpesa['ResponseCode'] == 0){
                        //wait 
                        //check the last created Disbursement
                        $transaction = Disburse::latest()->first();
                        //check if ConversationID from mpesa is the same as transactionStatusQuery
                        if($mpesa['ConversationID'] == $transaction->conversation_id){
                            //check if transaction Result Code is 0 
                            // if it is 0 then update the transaction status to success and update the loan status to disbursed
                            if($transaction->result_code == 0){
                                //update transaction status to success
                                $transaction->status = 'success';
                                $transaction->save();
                                //update loan status to disbursed
                                $loan = Loan::where('loan_id', $id)->first();
                                $loan->status = 'disbursed';
                                $loan->save();
                                return redirect()->back()->with('success', 'Loan disbursed successfully.');
                            }
                            else{
                                return redirect()->back()->with('error', 'Loan disbursed failed.');
                            }
                        }
                        else{
                            return redirect()->back()->with('error', 'Transaction doesnt match the one in the database.');
                        }



                    }
                    else{
                        return redirect()->back()->with('error', 'Loan disbursed failed.');
                    }
                    

        
                    return redirect()->route('loans.index')->with('success', 'Loan disbursed successfully.');
                } else {
                    // update transaction status table with failed
                    $transaction = new Transaction();
                    $transaction->result_type = $mpesa['Result']['ResultType'];
                    $transaction->result_code = $mpesa['Result']['ResultCode'];
                    $transaction->result_desc = $mpesa['Result']['ResultDesc'];
                    $transaction->originator_conversation_id = $mpesa['Result']['OriginatorConversationID'];
                    $transaction->conversation_id = $mpesa['Result']['ConversationID'];
                    $transaction->transaction_id = $mpesa['Result']['TransactionID'];
                    $transaction->transaction_receipt = $mpesa['Result']['ResultParameters']['ResultParameter'][0]['Value'];
                    $transaction->transaction_amount = $mpesa['Result']['ResultParameters']['ResultParameter'][1]['Value'];
                    $transaction->b2c_working_account_available_funds = $mpesa['Result']['ResultParameters']['ResultParameter'][2]['Value'];
                    $transaction->b2c_utility_account_available_funds = $mpesa['Result']['ResultParameters']['ResultParameter'][3]['Value'];
                    $transaction->transaction_completed_date_time = $mpesa['Result']['ResultParameters']['ResultParameter'][4]['Value'];
                    $transaction->receiver_party_public_name = $mpesa['Result']['ResultParameters']['ResultParameter'][5]['Value'];
                    $transaction->b2c_charges_paid_account_available_funds = $mpesa['Result']['ResultParameters']['ResultParameter'][6]['Value'];
                    $transaction->b2c_receiver_party_is_registered_customer = $mpesa['Result']['ResultParameters']['ResultParameter'][7]['Value'];
                    $transaction ->queue_timeout_url = $mpesa['Result']['ResultParameters']['ResultParameter'][8]['Value'];
                    $transaction->result_url = $mpesa['Result']['ResultParameters']['ResultParameter'][9]['Value'];
                    $transaction->loan_id = $id;
                    $transaction->status = 'failed';
                    $transaction->save();
                    return redirect()->route('loans.index')->with('error', 'Loan disbursment with mpesa failed, please try again.');

                }


            }
            
        }
        else{
            return redirect()->back()->with('error', 'Amount to be disbursed is greater than loan amount');
        }
        
        // $loan = Loan::with('customer')->where('loan_id', $id)->first();
        // $loan->loan_status = 'disbursed';
        // $loan->save();


    }


        //handle QueueTimeOutURL callback
        //This is the URL to be specified in your request that will be used by API Proxy to send notification incase the payment request is timed out while awaiting processing in the queue.
    public function queueTimeOutURL(Request $request)
    {
        
        //warning log
        \Log::warning('QueueTimeOutURL callback');
        \Log::info($request->all());
        //set

    }



        //handle ResultURL callback
        //This is the URL to be specified in your request that will be used by API Proxy to send notification once the payment request has been processed.
        public function resultURL(Request $request)
        {

            \Log::info('resultURL');
            \Log::info($request->all());
            $mpesa = $request->all();


        }

        public function transactionStatusQueryURL(Request $request)
        {
            \Log::info('transactionStatusQueryURL');
            \Log::info($request->all());
            $mpesa = $request->all();
            //save Details to disburse table
            $disburse = new Disburse();
            // $disburse->loan_id = $mpesa['LoanReferenceNumber'];
            // $disburse->customer_id = $mpesa['ThirdPartyReference'];
            $disburse->result_type = $mpesa['Result']['ResultType'];    
            $disburse->result_code = $mpesa['Result']['ResultCode'];
            $disburse->result_desc = $mpesa['Result']['ResultDesc'];
            $disburse->originator_conversation_id = $mpesa['Result']['OriginatorConversationID'];
            $disburse->conversation_id = $mpesa['Result']['ConversationID'];
            $disburse->transaction_id = $mpesa['Result']['TransactionID'];
            if (isset($mpesa['Result']['ResultParameters'])) {
                $disburse->transaction_receipt = $mpesa['Result']['ResultParameters']['ResultParameter'][0]['Value'];
                $disburse->transaction_amount = $mpesa['Result']['ResultParameters']['ResultParameter'][1]['Value'];
                $disburse->b2c_working_account_available_funds = $mpesa['Result']['ResultParameters']['ResultParameter'][2]['Value'];
                $disburse->b2c_utility_account_available_funds = $mpesa['Result']['ResultParameters']['ResultParameter'][3]['Value'];
                $disburse->transaction_completed_date_time = $mpesa['Result']['ResultParameters']['ResultParameter'][4]['Value'];
                $disburse->receiver_party_public_name = $mpesa['Result']['ResultParameters']['ResultParameter'][5]['Value'];
                $disburse->b2c_charges_paid_account_available_funds = $mpesa['Result']['ResultParameters']['ResultParameter'][6]['Value'];
                $disburse->b2c_receiver_party_is_registered_customer = $mpesa['Result']['ResultParameters']['ResultParameter'][7]['Value'];
                $disburse->queue_timeout_url = $mpesa['Result']['ResultParameters']['ResultParameter'][8]['Value'];
                $disburse->result_url = $mpesa['Result']['ResultParameters']['ResultParameter'][9]['Value'];

            }
            $disburse->reference_data = $mpesa['Result']['ReferenceData']['ReferenceItem']['Value'];
            $disburse->reference_key = $mpesa['Result']['ReferenceData']['ReferenceItem']['Key'];
            //check if transaction is successful from the result code
            if ($mpesa['Result']['ResultCode'] == 0) {
                $disburse->status = 'success';
            } else {
                $disburse->status = 'failed';
            }

            //check if request has loan id and customer id
            if (isset($request->loan_id) && isset($request->customer_id)) {
                $disburse->loan_id = $request->loan_id;
                $disburse->customer_id = $request->customer_id;
            }
            $disburse->save();



        }







    //disburse loan page view from loan show page
    public function disburseLoan(Request $request, $id)
    {
        $loan = Loan::with('customer')->where('loan_id', $id)->first();
        // dd($loan);
        
        return view('disburse.disburse', compact('loan'));
    }

    //getCustomerDetails
    public function getCustomerDetails(Request $request)
    { 
       
        //this is an ajax request to get the customer details from the loan table and display them on the disburse page
        if ($request->ajax()) {
            $customer = Loan::with('customer')->where('customer_id', $request->customer_id)->first();
            return response()->json(['success' => true, 'data'=> $customer]);
        }

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
            'amount' => 'required',
            'payment_method' => 'required',
            'loan_amount' => 'required',
            'customer_id ' => 'required',
            'customer_phone' => 'required',
        ]);

        //check if amount to be disbursed is less than or equal to loan amount
        if($request->amount <= $request->loan_amount){
            //check if payment method is mpesa
            if($request->payment_method == 'mpesa'){
                //B2C Mpesa API
                $phone = $request->customer_phone;
                $amount = $request->amount;
                $description = $request->description;
                //call mpesa function from this controller
                //get customer id from loan id
                $check = Loan::with('customer')->where('customer_id', $request->customer_id)->first();
                $customer_id = $request->customer_id;
                $loan_id = $check->loan_id;
                
                
                $mpesa = $this->b2cMpesa($phone, $amount, $description, $customer_id, $loan_id);
                //CHECK IF RETURNED aRRAY HAS ERROR
                if(isset($mpesa['errorCode'])){
                    return redirect()->back()->with('error', $mpesa['errorMessage']);
                }
                else if(isset($mpesa['ResponseCode'])){
                     //check if response code is 0
                    if($mpesa['ResponseCode'] == 0){
                        //wait 
                        //check the last created Disbursement
                        $transaction = Disburse::latest()->first();
                        //check if ConversationID from mpesa is the same as transactionStatusQuery
                        if($mpesa['ConversationID'] == $transaction->conversation_id){
                            //check if transaction Result Code is 0 
                            // if it is 0 then update the transaction status to success and update the loan status to disbursed
                            if($transaction->result_code == 0){
                                //update transaction status to success
                                $transaction->status = 'success';
                                $transaction->save();
                                //update loan status to disbursed
                                $loan = Loan::where('loan_id', $id)->first();
                                $loan->status = 'disbursed';
                                $loan->save();
                                return redirect()->back()->with('success', 'Loan disbursed successfully.');
                            }
                            else{
                                return redirect()->back()->with('error', 'Loan disbursed failed.');
                            }
                        }
                        else{
                            return redirect()->back()->with('error', 'Transaction doesnt match the one in the database.');
                        }



                    }
                    else{
                        return redirect()->back()->with('error', 'Loan disbursed failed.');
                    }
                    

        
                    return redirect()->route('loans.index')->with('success', 'Loan disbursed successfully.');
                } else {
                    // update transaction status table with failed
                    $transaction = new Transaction();
                    $transaction->result_type = $mpesa['Result']['ResultType'];
                    $transaction->result_code = $mpesa['Result']['ResultCode'];
                    $transaction->result_desc = $mpesa['Result']['ResultDesc'];
                    $transaction->originator_conversation_id = $mpesa['Result']['OriginatorConversationID'];
                    $transaction->conversation_id = $mpesa['Result']['ConversationID'];
                    $transaction->transaction_id = $mpesa['Result']['TransactionID'];
                    $transaction->transaction_receipt = $mpesa['Result']['ResultParameters']['ResultParameter'][0]['Value'];
                    $transaction->transaction_amount = $mpesa['Result']['ResultParameters']['ResultParameter'][1]['Value'];
                    $transaction->b2c_working_account_available_funds = $mpesa['Result']['ResultParameters']['ResultParameter'][2]['Value'];
                    $transaction->b2c_utility_account_available_funds = $mpesa['Result']['ResultParameters']['ResultParameter'][3]['Value'];
                    $transaction->transaction_completed_date_time = $mpesa['Result']['ResultParameters']['ResultParameter'][4]['Value'];
                    $transaction->receiver_party_public_name = $mpesa['Result']['ResultParameters']['ResultParameter'][5]['Value'];
                    $transaction->b2c_charges_paid_account_available_funds = $mpesa['Result']['ResultParameters']['ResultParameter'][6]['Value'];
                    $transaction->b2c_receiver_party_is_registered_customer = $mpesa['Result']['ResultParameters']['ResultParameter'][7]['Value'];
                    $transaction ->queue_timeout_url = $mpesa['Result']['ResultParameters']['ResultParameter'][8]['Value'];
                    $transaction->result_url = $mpesa['Result']['ResultParameters']['ResultParameter'][9]['Value'];
                    $transaction->loan_id = $id;
                    $transaction->status = 'failed';
                    $transaction->save();
                    return redirect()->route('loans.index')->with('error', 'Loan disbursment with mpesa failed, please try again.');

                }


            }
            
        }
        else{
            return redirect()->back()->with('error', 'Amount to be disbursed is greater than loan amount');
        }

        //check on the payment method
    
        //check if disbursed_to  request has an email
        // if(!is_null($request->disbursed_to->email)){
 
        //    $disbursement->notify(new DisbursementNotification($disbursement));
           
        // }

    


        
    
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Disburse  $disburse
     * @return \Illuminate\Http\Response
     */
    public function show(Disburse $disburse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Disburse  $disburse
     * @return \Illuminate\Http\Response
     */
    public function edit(Disburse $disburse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Disburse  $disburse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disburse $disburse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Disburse  $disburse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disburse $disburse)
    {
        //
    }

}
