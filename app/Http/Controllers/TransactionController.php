<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Transaction;
use App\Models\customer;
use App\Models\Loan;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Helpers\Jenga;
use App\Models\User;
use App\Notifications\LoanPaymentNotification;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $transactions = Transaction::select('*')->when($request->type, function ($query) use ($request){
            if($request->type == 'trxid'){
                $query->where('transaction_code', 'like', '%'.$request->value.'%');
            }elseif($request->type == 'name'){
                $query->whereHas('customer', function($query) use ($request){
                    // split the name into first and last name by space
                    $name = explode(' ', $request->value);
                    
                    //query to be where first name and last name are like the search value
                    $query->where('first_name', 'like', '%'.$name[0].'%')->where('last_name', 'like', '%'.$name[1].'%');
                });
            }elseif($request->type == 'national_id'){
                $query->whereHas('customer', function($query) use ($request){
                    $query->where('national_id', 'like', '%'.$request->value.'%');
                });
            }
        })->with('customer', 'user', 'loan')->orderBy('id', 'desc')->paginate(10);
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
        $transaction = Transaction::with('customer', 'payment_gateway', 'loan', 'user')->find($id);

        return view('transaction.show', compact('transaction'));

        
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
    //register the webhook that listens to mpesa events, entering equity account number 1450160649886, via 247247 mpesa paybill number
    //Instant Payment Notifications (IPN)
    // {
    //     "callbackType": "IPN",
    //     "customer": {
    //        "name": "A N Other",
    //        "mobileNumber": "",
    //        "reference": null
    //     },
    //     "transaction": {
    //        "date": "2018-11-27 00:00:00.0",
    //        "reference": " S2596405",
    //        "paymentMode": "TPG",
    //        "amount": "10",
    //        "billNumber": "A N Other",
    //        "orderAmount": "",
    //        "serviceCharge": "",
    //        "servedBy": "EQ",
    //        "additionalInfo": "MPS 254723000000 MKR35QEKV7 A N Other/537620",
    //        "status": "SUCCESS",
    //        "remarks": "?"
    //     },
    //     "bank": {
    //        "reference": " S2596405",
    //        "transactionType": "C",
    //        "account": "0111234241028"
    //     }
    //  }

    //callback url
    public function callback(Request $request){
        $data = $request->all();
        Log::info($data);
        // pass this data to transactionstatus in jenga helper
        $transaction = new Jenga();
        $transaction->transactionStatus($data);
    }
    //above is the sample webhook response from jenga api
    public function webhook(Request $request)
    {
        //the data is protectected with username and password
        //set the username and password in the .env file

        // $username = env('EQUITY_ALERT_USERNAME');
        // $password = env('EQUITY_ALERT_PASSWORD');

        // //use the username and password to authenticate the webhook
        // if($request->username != $username || $request->password != $password){
        //     return response()->json(['message' => 'Invalid username or password'], 401);
        // }



        //check if the username and password are correct


        Log::info($request->all());

        $transaction = $request->transaction;
        $transaction_reference = $transaction['reference'];
        $transaction_amount = $transaction['amount'];
        $transaction_status = $transaction['status'];
        $transaction_date = $transaction['date'];
        $transaction_details = $transaction['additionalInfo'];
        $transaction_type = $transaction['paymentMode'];
        $transaction_bill_number = $transaction['billNumber'];
        $transaction_order_amount = $transaction['orderAmount'];
        $transaction_service_charge = $transaction['serviceCharge'];
        $transaction_served_by = $transaction['servedBy'];
        $transaction_remarks = $transaction['remarks'];

        //get the customer details from the webhook
        $customer = $request->customer;
        $customer_name = $customer['name'];
        $customer_phone = $customer['mobileNumber'];
        $customer_reference = $customer['reference'];

        //get the bank details from the webhook
        $bank = $request->bank;
        $bank_reference = $bank['reference'];
        $bank_transaction_type = $bank['transactionType'];
        $bank_account = $bank['account'];


        //get the callback type from the webhook
        $callback_type = $request->callbackType;


        if($transaction_status == 'SUCCESS'){
            //get customerphone number from the webhook, and check if the customer exists
            $customer = Customer::where('phone', $customer_phone)->first();
            if($customer){
                //if the customer exists, check if the loan exists
                $loan = Loan::where('customer_id', $customer->id)->where('status', 'disbursed')->orWhere('status', 'active')->first();
                if($loan){
                    //if the loan exists, check if the transaction exists
                    $transaction = Transaction::where('transaction_reference', $request->transaction->reference)->first();
                    if(!$transaction){
                        $loan->remaining_balance = $loan->remaining_balance - $transaction_amount;
                        if ($loan->first_payment_date == null) {
                            //if the first installment payment date is null, set the first installment payment date to the current date
                            $loan->first_payment_date = $transaction_date;
                            $loan->save();
                            $loan_fist_payment_date = $loan->first_payment_date;
                        }
                        else{
                            //check if there is another loan payment in the loan payment table first the first one has installment date
                            if (LoanPayment::where('loan_id', $loan->id)->exists()) {
                                //if there is, get the last installment date
                                $last_installment_date = LoanPayment::where('loan_id', $loan->id)->max('installment_date');
                                //add a week to the last installment date
                                $loan_fist_payment_date = $last_installment_date->addWeek();
                            } else {
                                //if there is no other loan payment in the loan payment table, add a week to the first installment payment date
                                $loan_fist_payment_date = $loan->first_payment_date;
                            }
                        }

                        //if the remaining balance is 0 or has dropped below 0, set the loan status to closed
                        if($loan->remaining_balance <= 0){
                            $loan->status = 'closed';
                            $loan->payment_status = 'paid';
                            $loan->remaining_balance = 0;
                            $loan->save();

                            LoanPayment::create([
                                'paid_by' => $customer->id,
                                'loan_id' => $loan->id,
                                'amount' => $transaction_amount,
                                'payment_date' => $transaction_date,
                                // 'installment_date' => $loan_fist_payment_date,
                                'payment_method' => 'mpesa',
                                'payment_reference' => $transaction_reference,
                                'payment_status' => 'paid',
                            ]);

                            //if the loan is paid, create a transaction
                            Transaction::create([
                                'customer_id' => $customer->id,
                                'customer_name' => $customer_name,
                                'customer_phone' => $customer_phone,
                                'customer_reference' => $customer_reference,
                                'loan_id' => $loan->id,
                                'transaction_code' =>  'TMEL'.time(),
                                'transaction_type' => $transaction_type,
                                'transaction_status' => $transaction_status,
                                'remaining_balance' => $loan->remaining_balance,
                                'transaction_details' => $transaction_details,
                                'transaction_reference' => $transaction_reference,
                                'transaction_date' => $transaction_date,
                                'transaction_amount' => $transaction_amount,
                                'transaction_bill_number' => $transaction_bill_number,
                                'transaction_order_amount' => $transaction_order_amount,
                                'transaction_service_charge' => $transaction_service_charge,
                                'transaction_served_by' => $transaction_served_by,
                                'transaction_remarks' => $transaction_remarks,
                                //bank details
                                'bank_reference' => $bank_reference,
                                'bank_transaction_type' => $bank_transaction_type,
                                'bank_account' => $bank_account,
                                'callback_type' => $callback_type,
                            ]);
                        }
                        else if ($loan->remaining_balance > 0){
                            $loan->status = 'active';
                            $loan->payment_status = 'in_repayment';
                            $loan->remaining_balance = $loan->remaining_balance;
                            $loan->save();

                            LoanPayment::create([
                                'paid_by' => $customer->id,
                                'loan_id' => $loan->id,
                                'amount' => $transaction_amount,
                                'payment_date' => $transaction_date,
                                'payment_method' => 'mpesa',
                                'installment_date' => $loan_fist_payment_date,
                                'payment_reference' => $transaction_reference,
                                'payment_status' => 'in_repayment',
                            ]);

                            //create the transaction
                            $transaction = Transaction::create([
                                'customer_id' => $customer->id,
                                'customer_name' => $customer_name,
                                'customer_phone' => $customer_phone,
                                'customer_reference' => $customer_reference,
                                'loan_id' => $loan->id,
                                'transaction_code' =>  'TMEL'.time(),
                                'transaction_type' => $transaction_type,
                                'transaction_status' => $transaction_status,
                                'remaining_balance' => $loan->remaining_balance,
                                'transaction_details' => $transaction_details,
                                'transaction_reference' => $transaction_reference,
                                'transaction_date' => $transaction_date,
                                'transaction_amount' => $transaction_amount,
                                'transaction_bill_number' => $transaction_bill_number,
                                'transaction_order_amount' => $transaction_order_amount,
                                'transaction_service_charge' => $transaction_service_charge,
                                'transaction_served_by' => $transaction_served_by,
                                'transaction_remarks' => $transaction_remarks,
                                //bank details
                                'bank_reference' => $bank_reference,
                                'bank_transaction_type' => $bank_transaction_type,
                                'bank_account' => $bank_account,
                                'callback_type' => $callback_type,
                            ]);


                            //send in-app notification to the admin that a loan has been paid, or is in repayment
                        }
                        else{
                            return response()->json(['error' => 'Transaction failed']);
                        }
                        //send in-app notification to the admin that a loan has been paid, or is in repayment

                        $dataToSend = [
                            'name' => $customer->name,
                            'loan_id' => $loan->id,
                            'amount' => $transaction_amount,
                            'transaction_reference' => $transaction_reference,
                            'transaction_date' => $transaction_date,
                            'transaction_code' => $transaction_reference,
                            'balance' => $loan->remaining_balance,
                        ];

                        $admins = User::where('role_id', 1)->get();
                        foreach($admins as $admin){
                            $admin->notify(new LoanPaymentNotification($dataToSend));
                        }


                        return response()->json(['success' => 'Transaction created successfully']);

                    }
                }
            }
        }
    }



}
