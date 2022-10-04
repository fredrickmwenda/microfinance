<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\customer;
use App\Models\Loan;
use App\Models\LoanAttachment;
use App\Models\LoanCalculator;
use App\Models\LoanPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\DataTables;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get loan paginated
        $loans = Loan::with('customer', 'loan_attachments', 'approver', 'creator')->paginate(10);
        return view('loan.index', compact('loans'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get customers with no loans, or have loan_payment_status as paid and those also loan_status is not pending
        $customers = customer::whereDoesntHave('loans', function ($query) {
            $query->where('loan_payment_status', '!=', 'paid')->orWhere('loan_status', '!=', 'pending');
        })->get();
 


        return view('loan.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_amount' => 'required|numeric',
            'duration' => 'required',
            'loan_payment_type' => 'required',
            'customer_id' => 'required',
            'loan_interest' => 'required|numeric',
            'processing_fee' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
  
        $loan_id = $request->customer_id . rand(1000, 9999);


        //Calculate the interest from the loan amount and interest rate
        $interest = ($request->loan_amount * $request->loan_interest) / 100;

        //incase interest has a decimal point, round it up to the nearest integer
        $interest = round($interest);
        // $interest = (float) $interest;
        
  
     //check if the customer has a  loan not yet paid
        $customer = customer::with('loans')->where('id', $request->customer_id)->first();
        if ($customer->loans->count() > 0) {
          
            $loan = $customer->loans->last();
            if ($loan->loan_payment_status != 'paid') {
                return redirect()->back()->with('error', 'Customer has a loan not yet paid');
            }
        }



        if($request->loan_payment_type == "one_time"){
            $total_amount = $request->loan_amount + $interest;
            $data = [
                'loan_id' => $loan_id,
                'loan_amount' => $request->loan_amount,
                'loan_interest' =>  $interest,
                'total_payable' => $total_amount,
                'loan_duration' => $request->duration,
                'loan_payment_type' => $request->loan_payment_type,
                'customer_id' => $request->customer_id,
                'created_by' => Auth::user()->id,
                'loan_status' => 'pending',
                'late_payment_fee' => $request->late_fee,
                'loan_purpose' => $request->loan_purpose,
                'remaining_balance' => $total_amount,
                'processing_fee' => $request->processing_fee,
            ];
            Loan::create($data);

            if($request->hasFile('attachments')){
                $loan = Loan::latest()->first();
                foreach($request->loan_attachments as $attachment){
                    $attachment_name = $attachment->getClientOriginalName();
                    $attachment->storeAs('public/loan_attachments', $attachment_name);
                    //store the loan id and the attachment name in the loan_attachments table
                    $loan->loan_attachments()->create([
                        'loan_id' => $loan->id,
                        'attachment_name' => $attachment_name,
                    ]); 
                }
            }
            //redirect to the loan index page
            return redirect()->route('loan.index')->with('success', 'Loan created successfully');
        }
        elseif($request->loan_payment_type == "installment"){
            $total_amount = $request->loan_amount + $interest;
            \Log:: info($total_amount);
            #divide installments equally from duration and number of installments
            $installment = $total_amount / $request->installments;
           // $installment = round($installment, 2);

            $data = [
                'loan_id' => $loan_id,
                'loan_amount' => $request->loan_amount,
                'loan_interest' => $interest,
                'total_payable' => $total_amount,
                'loan_duration' => $request->duration,
                'loan_payment_type' => $request->loan_payment_type,
                'customer_id' => $request->customer_id,
                'created_by' => Auth::user()->id,
                'loan_first_payment_date' => $request->loan_first_payment_date,
                'loan_installment_payment' => $installment,
                #number of installments 
                'number_of_installments' => $request->installments,
                'loan_status' => 'pending',
                'late_payment_fee' => $request->late_fee,
                'loan_purpose' => $request->loan_purpose,
                'remaining_balance' => $total_amount,
                'processing_fee' => $request->processing_fee,
            
            ];
            
            Loan::create($data);
            //check if the loan has attachments
            if($request->has('loan_attachments')){
                $loan = Loan::latest()->first();
                foreach($request->loan_attachments as $attachment)
                { 
                    $attachment_name = $attachment->getClientOriginalName();
                    $attachment->storeAs('public/loan_attachments', $attachment_name);
                    //store the loan id and the attachment name in the loan_attachments table
                    $loan->loan_attachments()->create([
                        'loan_id' => $loan->loan_id,
                        'attachment_name' => $attachment_name,
                    ]); 
                }
            }
            //redirect to the loan index page
            // return redirect('loan');
            return redirect()->route('loan.index')->with('success', 'Loan created successfully');
        }
        else{
            return redirect()->back()->with('error', 'Loan payment type can only be one time payment or installment');
        }






        // $loan = Loan::create($request->all());
        // $loan_calculator = LoanCalculator::create($request->all());
        // $loan->loan_calculator()->save($loan_calculator);

        return redirect()->route('loan.create')->with('success', 'Loan created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::with('customer', 'loan_attachments')->where('loan_id', $id)->first();
        $loan_attachments = LoanAttachment::where('loan_id', $id)->get();
      
        $loan_payments = LoanPayment::where('loan_id', $id)->get();
        $loancollaterals = LoanPayment::where('loan_id', $id)->get();
        return view('loan.show', compact('loan', 'loan_payments', 'loancollaterals', 'loan_attachments'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loan_edit = Loan::with('customer', 'creator', 'loan_attachments')->where('loan_id', $id)->first();
        //get the  customer id from the loan
        $customer_id = $loan_edit->customer_id;
        //get the customer details using the customer id
        $customer = Customer::find($customer_id);

        return view('loan.edit', compact('loan_edit', 'customer'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $loan = Loan::where('loan_id', $id)->first();
        $loan_interest = $request->loan_amount * $request->loan_interest / 100;
        // dd($loan_interest, $request->all());

        //check if request has loan_attachments as files
        if($request->has('loan_attachments')){
            //loop through the files
            foreach($request->loan_attachments as $attachment){
                if(LoanAttachment::where('loan_id', $loan->loan_id)->first() != null){
                    //delete the file from the storage
                    $loan_attachment_name = LoanAttachment::where('loan_id', $loan->id)->first()->attachment_name;
                   
                    Storage::delete('public/loan_attachments/'.$loan_attachment_name);
                }
           
                $attachment->storeAs('public/loan_attachments', $attachment->getClientOriginalName());
                //store the loan id and the attachment name in the loan_attachments table
                LoanAttachment::create([
                    'loan_id' => $loan->loan_id,
                    'attachment_name' => $attachment->getClientOriginalName(),
                ]); 
            }
        }
        // dd($request->all());

        $loan->update([
            'loan_amount' => $request->loan_amount,
            'loan_interest' => $loan_interest,
            'loan_duration' => $request->loan_duration,
            'loan_status' => $request->loan_status,
            'loan_purpose' => $request->loan_purpose,
            'processing_fee' => $request->processing_fee,
        ]);

        return redirect()->route('loan.index')->with('success', 'Loan updated successfully');
        //
    }



    public function calculator(Request $request) {

        $data                           = array();
        $data['first_payment_date']     = '';
        $data['apply_amount']           = '';
        $data['interest_rate']          = '';
        $data['interest_type']          = '';
        $data['term']                   = '';
        $data['term_period']            = '';
        $data['late_payment_penalties'] = 0;
        return view('loan.calculator', $data);
    }

    public function calculate(Request $request) {
        $validator = Validator::make($request->all(), [
            'apply_amount'           => 'required|numeric',
            'interest_rate'          => 'required',
            'interest_type'          => 'required',
            'term'                   => 'required|integer|max:100',
            'term_period'            => $request->interest_type == 'one_time' ? '' : 'required',
            'late_payment_penalties' => 'required',
            'first_payment_date'     => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('loans.admin_calculator')->withErrors($validator)->withInput();
            }
        }

        $first_payment_date     = $request->first_payment_date;
        $apply_amount           = $request->apply_amount;
        $interest_rate          = $request->interest_rate;
        $interest_type          = $request->interest_type;
        $term                   = $request->term;
        $term_period            = $request->term_period;
        $late_payment_penalties = $request->late_payment_penalties;

        $data       = array();
        $table_data = array();

        switch ($interest_type) {
            case 'one_time':
                $loan_calculator = new LoanCalculator($apply_amount, $interest_rate, $term, $term_period, $late_payment_penalties, $first_payment_date);
                $table_data      = $loan_calculator->oneTimeInterest();
                break;
            case 'monthly':
                $loan_calculator = new LoanCalculator($apply_amount, $interest_rate, $term, $term_period, $late_payment_penalties, $first_payment_date);
                $table_data      = $loan_calculator->monthlyInterest();
                break;
            case 'daily':
                $loan_calculator = new LoanCalculator($apply_amount, $interest_rate, $term, $term_period, $late_payment_penalties, $first_payment_date);
                $table_data      = $loan_calculator->dailyInterest();
                break;
            case 'yearly':
                $loan_calculator = new LoanCalculator($apply_amount, $interest_rate, $term, $term_period, $late_payment_penalties, $first_payment_date);
                $table_data      = $loan_calculator->yearlyInterest();
                break;
        }

        // if ($interest_type == 'flat_rate') {

        //     $calculator             = new LoanCalculator($apply_amount, $first_payment_date, $interest_rate, $term, $term_period, $late_payment_penalties);
        //     $table_data             = $calculator->get_flat_rate();
        //     $data['payable_amount'] = $calculator->payable_amount;

        // } else if ($interest_type == 'fixed_rate') {

        //     $calculator             = new Calculator($apply_amount, $first_payment_date, $interest_rate, $term, $term_period, $late_payment_penalties);
        //     $table_data             = $calculator->get_fixed_rate();
        //     $data['payable_amount'] = $calculator->payable_amount;

        // } else if ($interest_type == 'mortgage') {

        //     $calculator             = new Calculator($apply_amount, $first_payment_date, $interest_rate, $term, $term_period, $late_payment_penalties);
        //     $table_data             = $calculator->get_mortgage();
        //     $data['payable_amount'] = $calculator->payable_amount;

        // } else if ($interest_type == 'one_time') {

        //     $calculator             = new Calculator($apply_amount, $first_payment_date, $interest_rate, 1, $term_period, $late_payment_penalties);
        //     $table_data             = $calculator->get_one_time();
        //     $data['payable_amount'] = $calculator->payable_amount;

        // }

        // $data['table_data']             = $table_data;
        // $data['first_payment_date']     = $request->first_payment_date;
        // $data['apply_amount']           = $request->apply_amount;
        // $data['interest_rate']          = $request->interest_rate;
        // $data['interest_type']          = $request->interest_type;
        // $data['term']                   = $request->term;
        // $data['term_period']            = $request->term_period;
        // $data['late_payment_penalties'] = $request->late_payment_penalties;

        return view('loan.calculator', $data);

    }

    //loan is approved by branch manager
    public function approve(Request $request, $id) {
        $loan = Loan::find($id);
        if ($loan) {
            $loan->loan_status = 'approved';
            $loan->approved_by = Auth::user()->id;
            $loan->approved_at = Carbon::now();
            // $loan->loan_start_date = Carbon::now();
            //get the loan duration
            // $loan_duration = $loan->duration;
            // $loan->loan_end_date = Carbon::now()->addDays($loan_duration);
            $loan->save();
            //send email using Job Queue to customer
            $customer = customer::find($loan->customer_id);
            if(!is_null($customer->email)){
                $job = (new SendEmailJob($customer->email, 'Loan Approved', 'Your loan has been approved'))->delay(Carbon::now()->addSeconds(5));
                dispatch($job);
            }
            // if (config('system_settings.enable_email_notification')) {
            //     $this->dispatch(new SendLoanApprovedEmail($loan));
            // }
            // $loan->notify(new LoanApproved($loan));
            
            return redirect()->route('loans.index')->with('success', 'Loan approved successfully');
        } else {
            return redirect()->route('loans.index')->with('error', 'Loan not found');
        }
    }

    //loan is rejected by branch manager
    public function reject(Request $request, $id) {
        $loan = Loan::find($id);
        if ($loan) {
            $loan->loan_status = 'rejected';
            $loan->rejected_by = Auth::user()->id;
            $loan->rejected_at = Carbon::now();
            $loan->rejected_reason = $request->rejection_reason;
            $loan->save();
            //if the customer has an email address, send him an email about the rejection
            // if ($loan->customer->email) {
            //     $data = array(
            //         'name' => $loan->customer->name,
            //         'rejection_reason' => $request->rejection_reason,
            //     );
            //     Mail::send('emails.loan_rejection', $data, function ($message) use ($loan) {
            //         $message->to($loan->customer->email, $loan->customer->name)->subject('Loan Rejection');
            //     });
            // }
            return redirect()->route('loan.index')->with('success', 'Loan rejected successfully');
        } else {
            return redirect()->route('loan.index')->with('error', 'Loan not found');
        }
    }

    // .route('loan.show', $row->id).
    public function getPendingLoans(Request $request){
        if ($request->ajax()) {
            //get 
            $data = Loan::with('customer', 'creator')->where('loan_status', 'pending')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    // ->editColumn('customer', function($row){
                    //     //return customer first name and last name
                    //     return $row->customer->first_name.' '.$row->customer->last_name;
                    // })
                    ->addColumn('action', function($row){
                        $btn_view = '<a href="'.route('loan.show', $row->loan_id).'" class="edit btn btn-primary btn-sm">View</a>';
                        $btn = '<a href="'.route('loan.edit', $row->loan_id).'" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
                        // $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
                        return $btn_view.' '.$btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        
  
    }

    public function getApprovedLoans(Request $request){
        if ($request->ajax()) {
            //get 
            $data = Loan::with('customer', 'creator', 'approver')->where('loan_status', 'approved', )->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn_view = '<a href="'.route('loan.show', $row->id).'" class="edit btn btn-primary btn-sm viewLoan mb-2">View</a>';
                        $btn = '<a href="'.route('loan.edit', $row->loan_id).'" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
                        // $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
                        return $btn_view.' '.$btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
  
    }

    public function getRejectedLoans(Request $request){
        if ($request->ajax()) {
            //get 
            $data = Loan::where('loan_status', 'rejected')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="'.route('loan.show', $row->id).'" class="edit btn btn-primary btn-sm">View</a>';
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
  
    }

    public function getClosedLoans(Request $request){
        if ($request->ajax()) {
            //get 
            $data = Loan::where('loan_status', 'closed')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="'.route('loan.show', $row->id).'" class="edit btn btn-primary btn-sm">View</a>';
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
  
    }

    public function getDisbursedLoans(Request $request){
        if ($request->ajax()) {
            //get 
            $data = Loan::with('customer', 'creator', 'approver')->where('loan_status', 'disbursed')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn_view = '<a href="'.route('loan.show', $row->id).'" class="edit btn btn-primary btn-sm">View</a>';
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
                        // $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
                        return $btn_view.' '.$btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
  
    }
    //get overdue loans
    public function getOverdueLoans(Request $request){
        if ($request->ajax()) {
            //get 
            $data = Loan::where('loan_status', 'overdue')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="'.route('loan.show', $row->id).'" class="edit btn btn-primary btn-sm">View</a>';
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
  
    }

    //get active loans
    public function getActiveLoans(Request $request){
        if ($request->ajax()) {
            //get 
            $data = Loan::where('loan_status', 'active')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="'.route('loan.show', $row->id).'" class="edit btn btn-primary btn-sm">View</a>';
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
  
    }


    // public function getLoan(Request $request){
    //     if ($request->ajax()) {
    //         //get 
    //         $data = Loan::latest()->get();
    //         return Datatables::of($data)
    //                 ->addIndexColumn()
    //                 ->addColumn('action', function($row){
    //                     $btn = '<a href="'.route('loan.show', $row->id).'" class="edit btn btn-primary btn-sm">View</a>';
    //                     $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
    //                     $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
    //                     return $btn;
    //                 })
    //                 ->rawColumns(['action'])
    //                 ->make(true);
    //     }
  
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        //
    }



}
