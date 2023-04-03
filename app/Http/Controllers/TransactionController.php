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
use App\Models\PaymentGateway;
use App\Models\User;
use App\Notifications\LoanPaymentNotification;
use Carbon\Carbon;
use Dompdf\Dompdf;

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
        // dd($transactions);
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
            'remaining_balance' => 'required',
            // 'payment_gateway_id' => 'required',
            'loan_id' => 'required',
            'transaction_reference' => 'required',
            'transaction_date' => 'required',
            // transaction_code is unique for each transaction
            'transaction_code' => 'required|unique:transactions',
            'transaction_amount' => 'required',
        ]);
        //check if this is the first transaction for this loan
        $loan = Loan::where('loan_id', $request->loan_id)->first();
        //  dd($loan);
        $transaction = Transaction::where('transaction_code', $request->transaction_code)->first();
        $customer_data = customer::find($request->customer_id);
        //dd($customer_data);
        $payment_gateway = PaymentGateway::find($request->payment_gateway_id);
        //  dd($payment_gateway, $request->payment_gateway_id);
        if(!$transaction){
            //check if the transaction is the first transaction for this loan
            $first_transaction = Transaction::where('loan_id', $request->loan_id)->first();
            if(!$first_transaction){
                //if this is the first transaction for this loan, then update the loan status to active
               // $loan_fist_pay = $loan->first_payment_date;
                if (empty($loan->first_payment_date)) {
                    $loan->first_payment_date = $request->transaction_date;
                    $loan->save();
                }
                //check if the transaction_amount is equal to the loan total_payable
                if($request->transaction_amount == $loan->remaining_balance){
                    //if the transaction_amount is equal to the loan total_payable, then update the loan status to paid
                    $loan->status = 'closed';
                    $loan->payment_status = 'paid';
                    $loan->remaining_balance = 0;
                    $loan->save();
                    $loan_payment_status = 'paid';

                }
                else{
                    //if the transaction_amount is not equal to the loan total_payable, then update the loan status to active
                    $loan->status = 'active';
                    $loan->payment_status = 'partially_paid';
                    $loan->remaining_balance = $loan->remaining_balance - $request->transaction_amount;
                    $loan->save();
                    $loan_payment_status = 'in_repayment';


                }
                LoanPayment::create([
                    'paid_by' => $request->customer_id,
                    'loan_id' => $loan->id,
                    'amount' => $request->transaction_amount,
                    'payment_date' => $request->transaction_date,
                    'payment_method' => 'mpesa',
                    'payment_reference' => $request->transaction_reference,
                    'payment_status' => $loan_payment_status,
                    'balance' => $loan->remaining_balance
                ]);

                Transaction::create([
                    'customer_id' => $request->customer_id,
                    'customer_name' => $customer_data->first_name.' '.$customer_data->last_name,
                    'customer_phone' => $customer_data->phone,
                    'loan_id' => $loan->id,
                    'transaction_type' => 'first_payment',
                    'transaction_code' => $request->transaction_code,
                    'transaction_status' => 'success',
                    'remaining_balance' => $loan->remaining_balance,
                    'transaction_reference' => $request->transaction_reference,
                    'transaction_date' => $request->transaction_date,
                    'transaction_amount' => $request->transaction_amount,
                ]);

                return redirect()->route('transaction.index')->with('success', 'Transaction created successfully');

            }
            else{
                $loan_remaining_balance = $loan->remaining_balance - $request->transaction_amount;
                //check if loan remaining balance is less or equal to zero
                if($loan_remaining_balance == 0){

                    //if the transaction_amount is equal to the loan total_payable, then update the loan status to paid
                    $loan->status = 'closed';
                    $loan->payment_status = 'paid';
                    $loan->remaining_balance = 0;
                    $loan->save();
                    $loan_payment_status = 'paid';
                }
                else if($loan_remaining_balance < 0){
                    if ( $request->transaction_amount == 600) {
                        return redirect()->back()->with('message', 'paymentis successful');
                    }
                    return redirect()->back()->with('error', 'Transaction amount is greater than the remaining balance');
                }
                else{
                    //if the transaction_amount is not equal to the loan total_payable, then update the loan status to active
                    $loan->status = 'active';
                    $loan->payment_status = 'partially_paid';
                    $loan->remaining_balance = $loan_remaining_balance;
                    $loan->save();
                    $loan_payment_status = 'in_repayment';
                }
                    
                LoanPayment::create([
                    'paid_by' => $request->customer_id,
                    'loan_id' => $loan->id,
                    'amount' => $request->transaction_amount,
                    'payment_date' => $request->transaction_date,
                    'payment_method' => 'mpesa',
                    'payment_reference' => $request->transaction_reference,
                    'payment_status' => $loan_payment_status,
                    'balance' => $loan->remaining_balance
                ]);

                Transaction::create([
                    'customer_id' => $request->customer_id,
                    'customer_name' => $customer_data->first_name.' '.$customer_data->last_name,
                    'customer_phone' => $customer_data->phone,
                    'loan_id' => $loan->id,
                    'transaction_type' => 'repayment',
                    'transaction_code' => $request->transaction_code,
                    'transaction_status' => 'success',
                    'remaining_balance' => $loan->remaining_balance,
                    'transaction_reference' => $request->transaction_reference,
                    'transaction_date' => $request->transaction_date,
                    'transaction_amount' => $request->transaction_amount,
                ]);

                return redirect()->route('transaction.index')->with('success', 'Transaction created successfully');


            }
        
        }

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
        Log::info($request->all());
        // dd($request->all());
        //fields customer.name, customer.mobileNumber, customer.reference, transaction.date, transaction.reference, transaction.paymentMode, transaction.amount, transaction.billNumber, transaction.orderAmount, transaction.serviceCharge, transaction.servedBy, transaction.additionalInfo, transaction.status, transaction.remarks, bank.reference, bill.transactionType, bill.account
        //get the transaction details from the webhook
        $transaction = $request->transaction;
        $transaction_reference = explode(' ', $transaction['remarks'])[2];
        Log::info($transaction_reference);
        $transaction_code = $transaction['reference'];
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
            Log::info('Transaction is successful');
            //get customerphone number from the webhook, and check if the customer exists
            $customer = Customer::where('phone', $customer_phone)->first();
            //or check in the remarks field which is a a string if 254 is present if its present, get the phone number
            //a sample remarks field is MPS 254707450710 RAH1LQ1TQD 900460 KATERINA/100208
            if(!$customer){
                //if the customer does not exist, check if the remarks field has the phone number
                //get the phone number from the remarks field and remove the 254 and replace it with 0
                $customer_phone = substr($transaction_remarks, strpos($transaction_remarks, "254"), 12);
                $customer_phone = str_replace('254', '0', $customer_phone);
                $customer = Customer::where('phone', $customer_phone)->first();
            }
            Log::info($customer);
            if($customer){
                Log::info($customer->id);
                //if the customer exists, check if the loan exists
                $loan = Loan::where('customer_id', $customer->id)->where(function ($query) {
                    $query->where('status', 'disbursed')
                          ->orWhere('status', 'active');
                })->first();
                if($loan){
                    Log::info('loan_details');
                    Log::info($loan);
                    //if the loan exists, check if the transaction exists
                    $transaction = Transaction::where('transaction_reference', $transaction_reference)->first();
                    if(!$transaction){
                        $loan_remaining_balance = $loan->remaining_balance - $transaction_amount;
                        
                        if ($loan->first_payment_date == null) {
                            //traansaction date is of string type like this date: "2023-01-19 13:26:45", convert it to date type withou the time
                            $transaction_date = Carbon::parse($transaction_date)->format('Y-m-d');
                            $loan->first_payment_date = $transaction_date;
                            $loan->save();
                            $loan_fist_payment_date = $loan->first_payment_date;
                        }
                        else{
                            //check if there is another loan payment in the loan payment table first the first one has installment date
                            if (LoanPayment::where('loan_id', $loan->id)->count() > 0) {
                                Log::info('There is another loan payment in the loan payment table');
                                Log::info($loan->id);
                                //check if there is installment date in the loan payment table
                                if(LoanPayment::where('loan_id', $loan->id)->whereNotNull('installment_date')->count() > 0)
                                {
                                    Log::info('There is installment date in the loan payment table');
                                    //if there is, get the last installment date
                                    $last_installment_date = LoanPayment::where('loan_id', $loan->id)->max('installment_date');
                                    //max returns the maximum value from the query result set, which is likely a date string, not a date object.
                                    //convert the date string to a date object
                                    $last_installment_date = Carbon::parse($last_installment_date);
                                    Log::info($last_installment_date);
                                    //Call to a member function addWeek()
                                    $loan_fist_payment_date = $last_installment_date->addWeek();
                                } else {
                                    Log::info('There is no other loan payment in the loan payment table');
                                    //if there is no other loan payment in the loan payment table, add a week to the first installment payment date
                                    $loan_fist_payment_date = $loan->first_payment_date;
                                }
                            } else {
                                Log::info('There is no other loan payment in the loan payment table');
                                //if there is no other loan payment in the loan payment table, add a week to the first installment payment date
                                $loan_fist_payment_date = $loan->first_payment_date;
                            }
                        }

                        //if the remaining balance is 0 or has dropped below 0, set the loan status to closed
                        if($loan_remaining_balance <= 0){
                            Log::info('Loan is paid');
                            $loan->status = 'closed';
                            $loan->payment_status = 'paid';
                            $loan->remaining_balance = 0;
                            //$loan last payment date is the transaction date since the loan is paid
                            // if($loan->last_payment_date == null){
                            //     //check on the week we are on, since the loan was disbursed
                            //     $week = Carbon::parse($loan->)->diffInWeeks($transaction_date);
                            //     //add the week to the last payment date
                            //     $loan->last_payment_date = $loan_fist_payment_date->addWeeks($week);
                                
                            // }
                        
                            $loan->last_payment_date = $transaction_date;
                            $loan->save();

                            LoanPayment::create([
                                'paid_by' => $customer->id,
                                'loan_id' => $loan->id,
                                'amount' => $transaction_amount,
                                'payment_date' => $transaction_date,
                                'balance' => $loan_remaining_balance,
                                //since the loan is paid, set the installment date to the current date
                                'installment_date' => $transaction_date,
                                'payment_method' => 'mpesa',
                                'payment_reference' => $transaction_reference,
                                'payment_status' => 'paid',
                            ]);

                            //if the loan is paid, create a transaction
                            Transaction::create([
                                'customer_id' => $customer->id,
                                'customer_name' => $customer->first_name . ' ' . $customer->last_name,
                                'customer_phone' => $customer_phone,
                                'customer_reference' => $customer_reference,
                                'loan_id' => $loan->id,
                                'transaction_type' => $transaction_type,
                                'transaction_status' => $transaction_status,
                                'remaining_balance' => $loan_remaining_balance,
                                'transaction_details' => $transaction_details,
                                'transaction_reference' => $transaction_reference,
                                'transaction_code' => $transaction_code,
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
                            Log::info('Loan is partially paid');
                            $loan->status = 'active';
                            $loan->payment_status = 'partially_paid';
                            $loan->remaining_balance = $loan->remaining_balance;
                            $loan->save();

                            LoanPayment::create([
                                'paid_by' => $customer->id,
                                'loan_id' => $loan->id,
                                'amount' => $transaction_amount,
                                'balance' => $loan_remaining_balance,
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
                                'transaction_type' => $transaction_type,
                                'transaction_status' => $transaction_status,
                                'remaining_balance' => $loan_remaining_balance,
                                'transaction_details' => $transaction_details,
                                'transaction_reference' => $transaction_reference,
                                'transaction_code' => $transaction_reference,
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
                            Log::info('Loan is not paid');
                            return response()->json(['error' => 'Transaction failed']);
                        }
                        //send in-app notification to the admin that a loan has been paid, or is in repayment

                        $dataToSend = [
                        // customer first_name and last_name 
                            'name' => $customer->first_name . ' ' . $customer->last_name,
                            'loan_id' => $loan->id,
                            'amount' => $transaction_amount,
                            'transaction_reference' => $transaction_reference,
                            'transaction_date' => $transaction_date,
                            'transaction_code' => $transaction_reference,
                            'balance' => $loan_remaining_balance,
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


    public function getTransactionCustomerDetails(Request $request)
    {       
        //this is an ajax request to get the customer details from the loan table and display them on the disburse page
        Log::info($request->all());
        if ($request->ajax()) {
            Log::info($request->customer_id);
            $customer = Loan::with('customer')->where('customer_id', $request->customer_id)->whereIn('status', ['disbursed', 'active'])->first();
            Log::info($customer);
            return response()->json(['success' => true, 'data'=> $customer]);
        }

    }

    public function getLoanTransactions(Request $request, $id){
        $loan = Loan::where('loan_id', $id)->first();
        // dd($loan);
        if ($loan->status === 'active' || $loan->status === 'closed') {
    
            // Get all the transactions for the loan
            $transactions = $loan->transactions;
            // dd($transactions);
            // Generate the receipt HTML
            $customer = $loan->customer;
            // $html = '<h1>Loan Transaction Receipt</h1>';
            // $html .= '<p>Customer: '.$customer->first_name.' '.$customer->last_name.'</p>';
            // $html .= '<p>Loan ID: '.$loan->id.'</p>';
            // $html .= '<table>';
            // $html .= '<thead><tr><th>Transaction Code</th><th>Date</th><th>Amount</th></tr></thead>';
            // $html .= '<tbody>';
            // foreach ($transactions as $transaction) {
            //     $date_string = $transaction->transaction_date;
            //     if (strlen($date_string) == 19) {
            //         $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $date_string);
            //     } elseif (strlen($date_string) == 16) {
            //         $datetime = Carbon::createFromFormat('Y-m-d\TH:i', $date_string);
            //     } elseif (strlen($date_string) == 10) {
            //         $datetime = Carbon::createFromFormat('Y-m-d', $date_string);
            //     } else {
            //         return "Error: Unknown date format: ".$date_string;
            //     }
                
            //     $html .= '<tr>';
            //     $html .= '<td>'.$transaction->transaction_code.'</td>';
            //     $html .= '<td>'.$datetime->format('Y-m-d H:i').'</td>';
            //     $html .= '<td>'.number_format($transaction->transaction_amount, 2).'</td>';
            //     $html .= '</tr>';
            // }
            // $html .= '</tbody>';
            // $html .= '</table>';

            
            // use html to calculate the total  transaction_amount of  all the transactions
        // $total_transaction_amount = $transactions->sum('transaction_amount');
        // $html .= '<p>Total transaction amount: '.number_format($total_transaction_amount, 2).'</p>';

        // Show the loan total payable
        // $html .= '<p>Loan total payable: '.number_format($loan->total_payable, 2).'</p>';

            // Check if there is a balance for the loan
            // if ($loan->remaining_balance > 0) {
            //     $html .= '<p>Balance: '.number_format($loan->remaining_balance, 2).'</p>';
            // }
            
            // Instantiate the PDF generator
            // $pdf = new Dompdf();
            $html = view('transaction.receipt', compact('loan', 'customer', 'transactions'))->render();
    
            // Instantiate the PDF generator
            $pdf = new Dompdf();
        
            // Load the receipt HTML into the PDF generator
            $pdf->loadHtml($html);
        
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
        
            // Render the PDF and output to the browser
            $pdf->render();
            $pdf->stream('Loan_Transaction_Receipt_' . $customer->first_name . '.pdf');

            
            // Set the HTML content
            //$pdf->loadHtml($html);
            
            // Render the PDF
            // $pdf->render();
            
            // Output the generated PDF for download
            // $pdf->stream('loan_transaction_receipt.pdf');
            
        } else {
            // Handle the case where the loan is not active or closed
            return back()->with('error', 'Loan isnt Active or Closed');
        }
        

    }


}
