<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Transaction;
use App\Models\customer;
use App\Models\Loan;
use Illuminate\Http\Request;

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
    public function jengaWebhook(Request $request)
    {
        //get the customer webhook data
        $customer = Customer::where('customer_phone', $request->phone)->first();
        

    }



}
