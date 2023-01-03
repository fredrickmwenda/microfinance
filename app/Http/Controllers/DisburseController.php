<?php

namespace App\Http\Controllers;

use App\Helpers\DatesValidator;
use App\Helpers\Jenga;
use App\Models\CallbackResponse;
use App\Models\customer;
use App\Models\Disburse;
use App\Models\Loan;
use App\Models\Transaction;
use App\Notifications\DisbursementNotification;
use Illuminate\Http\Request;
use Termwind\Components\Dd;
//log
use Illuminate\Support\Facades\Log;

class DisburseController extends Controller
{

    // get accountNumber from config
   


    public function __construct()
    {
        $this->middleware('auth');
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index( Request $request)
    {

        
        $disbursements = Disburse::select("*")->when($request->type, function ($query) use ($request){
            if($request->type == 'trxid'){
                $query->where('disbursement_code', 'like', '%'.$request->value.'%');
            }elseif($request->type == 'name'){
                //check if name is one full name

                $name = explode(' ', $request->value);
                if(count($name) == 2){
                    $query->whereHas('customer', function($q) use ($name){
                        $q->where('first_name', 'like', '%'.$name[0].'%')->where('last_name', 'like', '%'.$name[1].'%');
                    });
                }else{
                    $query->whereHas('customer', function($q) use ($name){
                        $q->where('first_name', 'like', '%'.$name[0].'%')->orWhere('last_name', 'like', '%'.$name[0].'%');
                    });
                }
            }elseif($request->type == 'national_id'){
                $query->whereHas('disbursedTo', function ($query) use ($request){
                    $query->where('national_id', 'like', '%'.$request->value.'%');
                });
            }
        })->orderBy('id', 'desc')->paginate(10);

       



        
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
        // dd($customer);

        return view('disburse.create', compact('customer'));
    }




    //disburse loan
    public function disburseLoanStore(Request $request, $id)
    {
        // dd($request->all());
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
                $current_loan = Loan::with('customer')->where('customer_id', $request->customer_id)->where('status', 'approved')->first();
               
                $customer_name = $current_loan->customer->first_name.' '.$current_loan->customer->last_name;
                $params = [
                    'country_code' => 'KE',
                    'source_name' =>  config('app.equity_source_name'),
                    // 'source_name' => 'Fredrick Mwenda',
                    'source_accountNumber' => config('app.equity_api_account_number'),
                    'customer_name' => $customer_name,
                    'customer_mobileNumber' => $request->customer_phone,
                    //'customer_mobileNumber' => '254713723353',
                    'wallet_name' => 'Mpesa',
                    'currencyCode' => 'KES',
                    'amount' => $request->amount,
                    //'amount' => 10,
                    'type' => 'MobileWallet', 
                    'reference' => rand(100000000000, 999999999999),
                    'date' => date('Y-m-d'),
                    'description' => 'Mweguni Loan Disburse',
                ];
                // $current_loan->end_date = now()->addDays($current_loan->duration);
                // dd($current_loan->end_date);

                $mpesa = new Jenga();
                $mpesa = $mpesa->sendMobileMoney($params);
                // dd($mpesa);
                //first check if a json response message of Token not found is returned

                if($mpesa->status == 'false'){
                    return redirect()->route('admin.disburse.index')->with('error', $mpesa->message);
                }
                else if ($mpesa->status == 'true'){
                    $data = json_decode(json_encode($mpesa), true);

                    $disbursement_code = $data['transactionId '];
                    Log::info($disbursement_code);
                   
                    // $current_loan->disbursed_
                    $current_loan->disbursed_by = auth()->user()->id;
                    $current_loan->start_date = now();
                    $current_loan->status = 'disbursed';
                    // get the loan duration which is in days  and add that to start date to get the date that the loan has to end
                    $current_loan->end_date = now()->addDays($current_loan->duration);
                    $current_loan->save();

                    //store to customer disbursements table
                    $disb = new Disburse();
                    $disb->loan_id = $current_loan->id;
                    // create a unique disbursement id, with the loan id and the current time and customer 2 first letters of first name
                    $disb->disbursement_code = $disbursement_code;
                    $disb->disbursement_amount = $request->amount;
                    // $disb->transaction_code = $mpesa['transactionId'];
                    $disb->payment_method = $request->payment_method;
                    $disb->disbursed_to = $current_loan->customer->id;
                    $disb->disbursed_by = auth()->user()->id;
                    $disb->phone = $request->customer_phone;
                    $disb->status = 'success';               
                    $disb->save();

                    //notify customer of transaction
                    // check if customer has an email address before sending notification
                    $disbursement = [
                        'amount' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'customer_name' => $request->customer_first_name.' '.$request->customer_last_name,
                        'customer_phone' => $request->customer_phone,
                        'transaction_id' => $disbursement_code,
                        // get loan amount from Loan model
                        'loan_amount' => $current_loan->amount,
                        'loan_duration' => $current_loan->duration,
                        'loan_start_date' => $current_loan->start_date,
                        'loan_end_date' => $current_loan->end_date,
                        'loan_total_payable' => $current_loan->total_payable,
                        'time' => \Carbon\Carbon::now(),
                    ];
                    //check if customer has an email address before sending email using job
                    if($current_loan->customer->email != null){
                        $current_loan->customer->notify(new DisbursementNotification($disbursement));
                    }
                    // else{
                    //     $message = ("Dear ".$current_loan->customer->first_name.", your loan of KES ".$current_loan->amount." has been disbursed.
                    //     Transaction ID: ".$mpesa->transactionId." Amount: KES ".$request->amount." Payment Method: ".$request->payment_method." Time: ".\Carbon\Carbon::now());
                    //     $this->sendSMS($current_loan->customer->phone, $message);
                    // }
                    return redirect()->route('admin.disburse.index')->with('success', 'Amount disbursed to ' .$current_loan->customer->first_name.' '.$current_loan->customer->last_name. 'successfully');

                }
                else if($mpesa->status == 401){
                    return redirect()->route('admin.disburse.index')->with('error', 'Jenga Token Expired');
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
        // dd($request->all());

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
                $current_loan = Loan::with('customer')->where('customer_id', $request->customer_id)->where('status', 'approved')->first();
                $customer_name = $current_loan->customer->first_name.' '.$current_loan->customer->last_name;
                $params = [
                    'country_code' => 'KE',
                    'source_name' => 'Fredrick Mwenda',
                    'source_accountNumber' => config('app.equity_api_account_number'),
                    'customer_name' => $customer_name,
                    // 'customer_mobileNumber' => $request->customer_phone,
                    'customer_mobileNumber' => '254713723353',
                    'wallet_name' => 'Mpesa',
                    'currencyCode' => 'KES',
                    // 'amount' => $request->amount,
                    'amount' => 10,
                    'type' => 'MobileWallet', 
                    'reference' => rand(100000000000, 999999999999),
                    'date' => date('Y-m-d'),
                    'description' => 'Test',
                ];
                $current_loan->end_date = now()->addDays($current_loan->duration);
                // dd($current_loan->end_date);

                $mpesa = new Jenga();
                $mpesa = $mpesa->sendMobileMoney($params);
                if($mpesa->status == false){
                    return redirect()->route('admin.disburse.index')->with('error', $mpesa->message);
                }
                else if ($mpesa->status == true){

                    $data = json_decode(json_encode($mpesa), true);

                    $disbursement_code = $data['transactionId '];
                    Log::info($disbursement_code);
                   
                    // $current_loan->disbursed_
                    $current_loan->disbursed_by = auth()->user()->id;
                    $current_loan->start_date = now();
                    $current_loan->status = 'disbursed';
                    // get the loan duration which is in days  and add that to start date to get the date that the loan has to end
                    $current_loan->end_date = now()->addDays($current_loan->duration);
                    $current_loan->save();

                    //store to customer disbursements table
                    $disb = new Disburse();
                    $disb->loan_id = $current_loan->id;
                    // create a unique disbursement id, with the loan id and the current time and customer 2 first letters of first name
                    $disb->disbursement_code = $disbursement_code;
                    $disb->disbursement_amount = $request->amount;
                    // $disb->transaction_code = $mpesa['transactionId'];
                    $disb->payment_method = $request->payment_method;
                    $disb->disbursed_to = $current_loan->customer->id;
                    $disb->disbursed_by = auth()->user()->id;
                    $disb->phone = $request->customer_phone;
                    $disb->status = 'success';               
                    $disb->save();

                    //notify customer of transaction
                    // check if customer has an email address before sending notification
                    $disbursement = [
                        'amount' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'customer_name' => $request->customer_first_name.' '.$request->customer_last_name,
                        'customer_phone' => $request->customer_phone,
                        'transaction_id' => $disbursement_code,
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
                    return redirect()->route('admin.disburse.index')->with('success', 'Amount disbursed to ' .$current_loan->customer->first_name.' '.$current_loan->customer->last_name. 'successfully');

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
    public function show($id)
    {
        $disbursement = Disburse::with('loan', 'disburser', 'disbursedTo')->where('id', $id)->first();
        dd($disbursement);
        return view('disbursements.show', compact('disbursement'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Disburse  $disburse
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $disbursement = Disburse::with('loan', 'disburser', 'disbursedTo')->where('id', $id)->first();
        return view('disburse.edit', compact('disbursement'));
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
    public function destroy($id)
    {
        $disbursement = Disburse::find($id);
        $disbursement->delete();
        return redirect()->route('admin.disburse.index')->with('success', 'Disbursement deleted successfully');
    }
        //
    

}
