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
        //get customers with loan_status as disbursed or in_repayment
        $customers = customer::with('loans')->whereHas('loans', function($query){
            $query->where('loan_status', 'disbursed')->orWhere('loan_status', 'in_repayment');
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
                //if it is a repayment, check if the loan is in_repayment
               
                if($loan->loan_status == 'in_repayment'){
                    //if the loan is in_repayment, check if the loan is fully repaid
                    $loan_amount = $loan->loan_amount;
                    $total_amount = $loan->total_amount;
                    $total_paid = $loan->total_paid;
                    $balance = $loan_amount - $total_paid;
                    if($balance == 0){
                        //warn the user that the loan is fully repaid
                        return redirect()->back()->with('error', 'The loan is fully repaid');
                    }
                    //if the loan is not fully repaid, check if the amount paid is greater than the balance
                    if($request->transaction_amount > $balance){
                        //if the amount paid is greater than the balance, warn the user
                        return redirect()->back()->with('error', 'The amount paid is greater than the balance');
                    }
                    //if the amount paid is less than the balance, or equal to the balance, update the loan
                    $loan->total_paid = $loan->total_paid + $request->transaction_amount;
                    

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
            if($loan->loan_status == 'disbursed'){
                $loan->remaining_balance = $loan->remaining_balance - $request->transaction_amount;
                //if the loan is disbursed, check if the loan is fully repaid
                $loan_amount = $loan->loan_amount;
                $total_payable = $loan->total_payable;
                $total_paid = $loan->total_paid;
                $balance = $total_payable - $total_paid;
                //if the loan is fully repaid, update the loan_status to fully repaid and remaining_balance to 0
                if($balance == 0){
                    $loan->loan_status = 'fully repaid';
                    $loan->remaining_balance = 0;
                    $loan->save();
                    return 
                }else{
                    //check if this is the last transaction for this loan
                    $loan_amount = $loan->loan_amount;
                    $total_payable = $loan->total_payable;
                    $total_paid = $loan->total_paid;
                    $balance = $total_payable - $total_paid;
                    if($balance == $request->transaction_amount){
                        //if this is the last transaction for this loan, update the loan_status to  fully repaid and remaining_balance to 0
                        $loan->loan_status = 'fully repaid';
                        $loan->remaining_balance = 0;
                        $loan->save();
                    }else{
                        //if this is not the last transaction for this loan, update the loan_status to in_repayment
                        $loan->loan_status = 'in_repayment';
                        $loan->remaining_balance = $loan->remaining_balance - $request->transaction_amount;
                        $loan->total_paid = $loan->total_paid + $request->transaction_amount;
                        $loan->save();
                    }
                }
            }




                //if the loan is disbursed, update the loan_status to in_repayment
                $loan->loan_status = 'in_repayment';
                $loan->save();
            }
        }

        //check if the transaction amount is equal to the total amount
        //if it is update the loan status to paid  and the remaining balance to 0
        //if it is not update the loan status to in_repayment and update the remaining balance
        $loan = Loan::find($request->loan_id);
        $loan->remaining_balance = $loan->remaining_balance - $request->transaction_amount;
        if($loan->remaining_balance == 0){
            $loan->loan_status = 'closed';
            $loan->loan_payment_status = 'paid';
        }else{
            $loan->loan_status = 'in_repayment';
            $loan->loan_payment_status = 'partially_paid';
        }
        $loan->save();


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
}
