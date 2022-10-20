<?php

namespace App\Http\Controllers;

use App\Helpers\Jenga;
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

    // get accountNumber from config
    private $accountNumber;


    public function __construct()
    {
        $this->accountNumber = config('app.equity_api_account_number') ?? '';
    }

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
            $query->where('status', 'approved');
        })->get();

        return view('disburse.create', compact('customer'));
    }




    //disburse loan
    public function disburseLoanStore(Request $request, $id)
    {
        //validate request
        $request->validate([
            'amount' => 'required',
            'payment_method' => 'required',
            'loan_amount' => 'required',
            'customer_id' => 'required',
            'customer_phone' => 'required',
        ]);

        //check if amount to be disbursed is less than or equal to loan amount
        if($request->amount <= $request->loan_amount){
            //check if payment method is mpesa
            if($request->payment_method == 'mpesa'){
                //  combine customer first name and last name
                $current_loan = Loan::where('loan_id', $id)->first();
                $customer_name = $current_loan->customer->first_name . ' ' . $current_loan->customer->last_name;
                $params = [
                    'country_code' => 'KE',
                    'source_name' => 'Fredrick Mwenda',
                    'source_accountNumber' => $this->account_number,
                    'customer_name' => $customer_name,
                    'customer_mobileNumber' => $request->customer_phone,
                    'wallet_name' => 'Mpesa',
                    'currencyCode' => 'KES',
                    'amount' => $request->amount,
                    'type' => 'MobileWallet', 
                    'reference' => rand(100000000000, 999999999999),
                    'date' => date('Y-m-d'),
                    'description' => 'Test',
                ];
               
                $mpesa = Jenga::sendMobileMoney();
                if($mpesa->status == false){
                    return redirect()->route('disbursement.index')->with('error', $mpesa->message);
                }
                else if ($mpesa->status == true){
                    //read payment from jenga api                
                    //update customer loan status
                    
                    $current_loan->status = "disbursed";
                    $current_loan->disbursed_at = now();
                    $current_loan->disbursed_by = auth()->user()->id;
                    $current_loan->start_date = now();
                    // get the loan duration which is in days  and add that to start date to get the date that the loan has to end
                    $current_loan->end_date = now()->addDays($current_loan->duration);
                    $current_loan->save();

                    //store to customer disbursements table
                    $disb = new Disburse();
                    $disb->loan_id = $current_loan->id;
                    // create a unique disbursement id, with the loan id and the current time and customer 2 first letters of first name
                    $disb->disbursement_id = $current_loan->loan_id.'-'.time().'-'.substr($current_loan->customer->first_name, 0, 2);
                    $disb->disbursement_amount = $request->amount;
                    $disb->transaction_code = $mpesa->transactionId;
                    $disb->payment_method = $request->payment_method;
                    $disb->disbursed_to = $current_loan->customer->id;
                    $disb->disbursed_by = auth()->user()->id;
                    $disb->phone = $request->customer_phone;                  
                    $disb->created_at = now();
                    $disb->save();

                    //notify customer of transaction
                    // check if customer has an email address before sending notification
                    $disbursement = [
                        'amount' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'customer_name' => $request->customer_first_name.' '.$request->customer_last_name,
                        'customer_phone' => $request->customer_phone,
                        'transaction_id' => $mpesa->transactionId,
                        // get loan amount from Loan model
                        'loan_amount' => $current_loan->amount,
                        'time' => \Carbon\Carbon::now(),
                    ];
                    // if($current_loan->customer->email != null){
                    //     $current_loan->customer->notify(new DisbursementNotification($disbursement));
                    // }
                    // else{
                    //     $message = ("Dear ".$current_loan->customer->first_name. " " .$current_loan->customer->last_name.", 
                    //     your disbursement is USD ".$request->amount.". Your loan has been approved and disbursed. This
                    //     money will be deducted from your next salary");
                    //     // send sms notification to customer
                    //     Jenga::sendSMS($current_loan->customer->phone_number,$message);
                    //     return redirect('disbursement')->with('success', 'Amount disbursed to ' .$current_loan->customer->first_name.' '.$current_loan->customer->last_name. 'successfully');
                    // }
                    return redirect()->route('disbursement.index')->with('success', 'Amount disbursed to ' .$current_loan->customer->first_name.' '.$current_loan->customer->last_name. 'successfully');
                }
            }       
        }
        else{
            return redirect()->back()->with('error', 'Amount to be disbursed is greater than loan amount');
        }

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
            'customer_id' => 'required',
            'customer_phone' => 'required',
        ]);


        //check if amount to be disbursed is less than or equal to loan amount
        if($request->amount <= $request->loan_amount){
            //check if payment method is mpesa
            if($request->payment_method == 'mpesa'){
                $current_loan = Loan::with('customer')->where('customer_id', $request->customer_id)->first();
                $customer_name = $current_loan->customer->first_name.' '.$current_loan->customer->last_name;
                $params = [
                    'country_code' => 'KE',
                    'source_name' => 'Fredrick Mwenda',
                    'source_accountNumber' => $this->account_number,
                    'customer_name' => $customer_name,
                    'customer_mobileNumber' => $request->customer_phone,
                    'wallet_name' => 'Mpesa',
                    'currencyCode' => 'KES',
                    'amount' => $request->amount,
                    'type' => 'MobileWallet', 
                    'reference' => rand(100000000000, 999999999999),
                    'date' => date('Y-m-d'),
                    'description' => 'Test',
                ];

                $mpesa = Jenga::sendMobileMoney();
                if($mpesa->status == false){
                    return redirect()->route('disbursement.index')->with('error', $mpesa->message);
                }
                else if ($mpesa->status == true){
                    //store to loans table
                   
                    $current_loan->disbursed = 1;
                    $current_loan->disbursed_by = auth()->user()->id;
                    $current_loan->start_date = now();
                    // get the loan duration which is in days  and add that to start date to get the date that the loan has to end
                    $current_loan->end_date = now()->addDays($current_loan->duration);
                    $current_loan->save();

                    //store to customer disbursements table
                    $disb = new Disburse();
                    $disb->loan_id = $current_loan->id;
                    // create a unique disbursement id, with the loan id and the current time and customer 2 first letters of first name
                    $disb->disbursement_id = $current_loan->loan_id.'-'.time().'-'.substr($current_loan->customer->first_name, 0, 2);
                    $disb->disbursement_amount = $request->amount;
                    $disb->transaction_code = $mpesa->transactionId;
                    $disb->payment_method = $request->payment_method;
                    $disb->disbursed_to = $current_loan->customer->id;
                    $disb->disbursed_by = auth()->user()->id;
                    $disb->phone = $request->customer_phone;                  
                    $disb->created_at = now();
                    $disb->save();

                    //notify customer of transaction
                    // check if customer has an email address before sending notification
                    $disbursement = [
                        'amount' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'customer_name' => $request->customer_first_name.' '.$request->customer_last_name,
                        'customer_phone' => $request->customer_phone,
                        'transaction_id' => $mpesa->transactionId,
                        // get loan amount from Loan model
                        'loan_amount' => $current_loan->amount,
                        'time' => \Carbon\Carbon::now(),
                    ];
                    // if($current_loan->customer->email != null){
                    //     $current_loan->customer->notify(new DisbursementNotification($disbursement));
                    // }
                    // else{
                    //     $message = ("Dear ".$current_loan->customer->first_name.", your loan of KES ".$current_loan->amount." has been disbursed.
                    //     Transaction ID: ".$mpesa->transactionId." Amount: KES ".$request->amount." Payment Method: ".$request->payment_method." Time: ".\Carbon\Carbon::now());
                    //     $this->sendSMS($current_loan->customer->phone, $message);
                    // }
                    return redirect()->route('disbursement.index')->with('success', 'Amount disbursed to ' .$current_loan->customer->first_name.' '.$current_loan->customer->last_name. 'successfully');

                }

            }
            
        }
        else{
            return redirect()->back()->with('error', 'Amount to be disbursed is greater than loan amount');
        }  
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
