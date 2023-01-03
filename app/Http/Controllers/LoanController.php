<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Jobs\SendLoanApproval;
use App\Jobs\SendLoanRejection;
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
        //get loan paginated and order by id in ascending order
        $loans = Loan::select('*')->when(request()->type, function ($query) {
            // loan status, customer name, customer phone, customer national id
            // if (request()->type =='status') {
            //     $query->where('status', request()->status);
            // }
            if(request()->type =='name') {
                $query->whereHas('customer', function ($query) {
                    $name = explode(' ', request()->value);
                    if(count($name) == 2){
                        $query->where('first_name', 'like', '%'.$name[0].'%')
                            ->where('last_name', 'like', '%'.$name[1].'%');
                    }else{
                        $query->where('first_name', 'like', '%'.request()->value.'%')->orWhere('last_name', 'like', '%'.request()->value.'%');
                    }
                });
            }
            else if(request()->type =='phone') {
                $query->whereHas('customer', function ($query) {
                    $query->where('phone', 'like', '%' . request()->value . '%');
                });
            }
            else if(request()->type =='national_id') {
                $query->whereHas('customer', function ($query) {
                    $query->where('national_id', 'like', '%' . request()->value . '%');
                });
            }
        })->orderBy('id', 'desc')->paginate(10);
        // Loan::with('customer', 'loan_attachments', 'approver', 'creator')->orderBy('id', 'asc')->paginate(10);
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
        //get customers with no loans, or have payment_status as paid and those also status is not pending
        $customers = customer::whereDoesntHave('loans', function ($query) {
            $query->where('payment_status', '!=', 'paid')->orWhere('status', '!=', 'pending');
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
            if ($loan->payment_status != 'paid') {
                return redirect()->back()->with('error', 'Customer has a loan not yet paid');
            }
        }



        if($request->loan_payment_type == "one_time"){
            $total_amount = $request->loan_amount + $interest;
            $data = [
                'loan_id' => $loan_id,
                'amount' => $request->loan_amount,
                'interest' =>  $interest,
                'total_payable' => $total_amount,
                'duration' => $request->duration,
                'payment_type' => $request->loan_payment_type,
                'customer_id' => $request->customer_id,
                'created_by' => Auth::user()->id,
                'status' => 'pending',
                // 'late_payment_fee' => $request->late_fee,
                'loan_purpose' => $request->loan_purpose,
                'remaining_balance' => $total_amount,
                'processing_fee' => $request->processing_fee,
            ];
            Loan::create($data);

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
                'amount' => $request->loan_amount,
                'interest' => $interest,
                'total_payable' => $total_amount,
                'duration' => $request->duration,
                'payment_type' => $request->loan_payment_type,
                'customer_id' => $request->customer_id,
                'created_by' => Auth::user()->id,
                'first_payment_date' => $request->loan_first_payment_date,
                'installment_payment' => $installment,
                'number_of_installments' => $request->installments,
                'status' => 'pending',
                // 'late_payment_fee' => $request->late_fee,
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
                if(count(LoanAttachment::where('loan_id', $id)->get()) > 0){
                    $loan_attachments = LoanAttachment::where('loan_id', $id)->get();
                    foreach($loan_attachments as $attach){
                        //delete the file from the storage
                        Storage::delete('public/loan_attachments/'.$attach->attachment_name);
                        //delete the file from the database
                        LoanAttachment::where('loan_id', $loan->loan_id)->delete();
                    }    
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
            'amount' => $request->loan_amount,
            'interest' => $loan_interest,
            'duration' => $request->loan_duration,
            'status' => $request->loan_status,
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



        return view('loan.calculator', $data);

    }

    //loan is approved by branch manager
    public function approve(Request $request, $id) {
        $loan = Loan::find($id);
        if ($loan) {
            $loan->status = 'approved';
            $loan->approved_by = Auth::user()->id;
            $loan->approved_at = Carbon::now();
            $loan->save();

            $details = [
                'title' => 'Loan Approved',
                'body' => 'Your loan of ' . $loan->amount . ' has been approved, and will be disbursed soon.',
                'loan_amount' => $loan->amount,
                'loan_duration' => $loan->duration,
                'loan_total_payable' => $loan->total_payable,
                'customer_name' => $loan->customer->first_name . ' ' . $loan->customer->last_name,
            ];
            //send email using Job Queue to customer
            $customer = customer::find($loan->customer_id);
            if(!is_null($customer->email)){
                $job = (new SendLoanApproval($details, $customer->email))->delay(Carbon::now()->addSeconds(5));
                dispatch($job);
            }
            // if (config('system_settings.enable_email_notification')) {
            //     $this->dispatch(new SendLoanApprovedEmail($loan));
            // }
            // $loan->notify(new LoanApproved($loan));
            
            return redirect()->route('loan.index')->with('success', 'Loan approved successfully');
        } else {
            return redirect()->route('loan.index')->with('error', 'Loan not found');
        }
    }

    //loan is rejected by branch manager
    public function reject(Request $request, $id) {
        $loan = Loan::find($id);
        if ($loan) {
            $loan->status = 'rejected';
            $loan->rejected_by = Auth::user()->id;
            $loan->rejected_at = Carbon::now();
            $loan->rejected_reason = $request->rejection_reason;
            $loan->save();
            $details = [
                'title' => 'Loan Rejected',
                'loan_amount' => $loan->amount,
                'loan_duration' => $loan->duration,
                'loan_total_payable' => $loan->total_payable,
                'customer_name' => $loan->customer->first_name . ' ' . $loan->customer->last_name,
                'rejection_reason' => $request->rejection_reason,
            ];
            //send email using Job Queue to customer
            $customer = customer::find($loan->customer_id);
            if(!is_null($customer->email)){
                $job = (new SendLoanRejection($details, $customer->email))->delay(Carbon::now()->addSeconds(5));
                dispatch($job);
            }
            return redirect()->route('loan.index')->with('success', 'Loan rejected successfully');
        } else {
            return redirect()->route('loan.index')->with('error', 'Loan not found');
        }
    }


    // pending loans page
    public function pendingLoansPage() {
     
        $loans = Loan::where('status', 'pending')->get();
        return view('loan.pending', compact('loans'));
    }

    // approved loans page
    public function approvedLoansPage() {
        $loans = Loan::where('status', 'approved')->paginate(10);
        return view('loan.approved', compact('loans'));
    }

    // rejected loans page
    public function rejectedLoansPage() {
        $loans = Loan::where('status', 'rejected')->get();
        return view('loan.rejected', compact('loans'));
    }

    //completed loans page
    public function closedLoansPage() {
        $loans = Loan::where('status', 'closed')->get();
        return view('loan.closed', compact('loans'));
    }

    //active loans page
    public function activeLoansPage() {
        
        $loans = Loan::where('status', 'active')->get();
        return view('loan.active', compact('loans'));
    }

    //create prehistoric loan which is already paid
    public function createPrehistoricLoansPage() {
        $customers = customer::all();
        return view('loan.prehistoric', compact('customers'));
    }

    public function storePrehistoricLoans(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_amount' => 'required|numeric',
            'duration' => 'required',
            'due_date' => 'required',
            'loan_creation_date' => 'required',
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
        
        if($request->loan_payment_type == "one_time"){
            $total_amount = $request->loan_amount + $interest;
            $data = [
                'loan_id' => $loan_id,
                'amount' => $request->loan_amount,
                'interest' =>  $interest,
                'total_payable' => $total_amount,
                'duration' => $request->duration,
                'payment_type' => $request->loan_payment_type,
                'customer_id' => $request->customer_id,
                'created_by' => Auth::user()->id,
                'loan_purpose' => $request->loan_purpose,
                'remaining_balance' => $total_amount,
                'processing_fee' => $request->processing_fee,
                'start_date' => $request->loan_creation_date,
                'end_date' => $request->due_date,
                'status' => 'closed',
                'payment_status' => 'paid',
                'total_paid' => $total_amount,
                'remaining_balance' => 0,
            ];
            Loan::create($data);

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
            return redirect()->route('loan.index')->with('success', 'Prehistoric Loan created successfully');
        }
        elseif($request->loan_payment_type == "installment"){
            $total_amount = $request->loan_amount + $interest;
            \Log:: info($total_amount);
            #divide installments equally from duration and number of installments
            $installment = $total_amount / $request->installments;
           // $installment = round($installment, 2);
           //we get the last payment date from the first payment date and the duration
            $last_payment_date = date('Y-m-d', strtotime($request->loan_first_payment_date. ' + '.$request->duration.' days'));
            
            $data = [
                'loan_id' => $loan_id,
                'amount' => $request->loan_amount,
                'interest' => $interest,
                'total_payable' => $total_amount,
                'duration' => $request->duration,
                'payment_type' => $request->loan_payment_type,
                'customer_id' => $request->customer_id,
                'created_by' => Auth::user()->id,
                'first_payment_date' => $request->first_installment_date,
                'last_payment_date' => $request->due_date,
                'installment_payment' => $installment,
                'number_of_installments' => $request->installments,
                'loan_purpose' => $request->loan_purpose,
                'remaining_balance' => $total_amount,
                'processing_fee' => $request->processing_fee,
                'start_date' => $request->loan_creation_date,
                'end_date' => $request->due_date,
                'status' => 'closed',
                'payment_status' => 'paid',
                'total_payment' => $total_amount,
                'remaining_balance' => 0,
            ];
            Loan::create($data);

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
            return redirect()->route('loan.index')->with('success', 'Prehistoric Loan created successfully');
        }
        else{
            return redirect()->back()->with('error', 'Loan payment type can only be one time payment or installment');
        }
    }


    //overdue loans page
    public function overdueLoansPage() {
        $loans = Loan::where('status', 'overdue')->get();
        return view('loan.overdue', compact('loans'));
    }
    //loans expected to be paid tomorrow
    public function dueTomorrowLoansPage() {
        $loans = Loan::where('end_date', Carbon::tomorrow())->get();
        return view('loan.expected_tomorrow', compact('loans'));
    }

    //loans expected to be paid today
    public function dueTodayLoansPage() {
        $loans = Loan::where('end_date', Carbon::today())->get();
        return view('loan.due_today', compact('loans'));
    }

    public function getPendingLoans(Request $request){
        if ($request->ajax()) {
            if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3){
                $data = Loan::with('customer', 'creator')->where('status', 'pending')->latest()->get();
            }else if (Auth::user()->role_id == 2) {
                $data = Loan::with('customer', 'creator')->where('status', 'pending')->where('created_by', Auth::user()->id)->latest()->get();
            }
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
            if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3){
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'approved')->latest()->get();
            }else if (Auth::user()->role_id == 2) {
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'approved')->where('created_by', Auth::user()->id)->latest()->get();
            } 
           
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
            if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3){
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'rejected')->latest()->get();
            }else if (Auth::user()->role_id == 2) {
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'rejected')->where('created_by', Auth::user()->id)->latest()->get();
            }
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
            if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3){
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'closed')->latest()->get();
            }else if (Auth::user()->role_id == 2) {
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'closed')->where('created_by', Auth::user()->id)->latest()->get();
            }
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
            if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3){
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'disbursed')->latest()->get();
            }else if (Auth::user()->role_id == 2) {
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'disbursed')->where('created_by', Auth::user()->id)->latest()->get();
            }
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
            if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3){
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'overdue')->latest()->get();
            }else if (Auth::user()->role_id == 2) {
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'overdue')->where('created_by', Auth::user()->id)->latest()->get();
            }
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
            if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3){
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'active')->latest()->get();
            }else if (Auth::user()->role_id == 2) {
                $data = Loan::with('customer', 'creator', 'approver')->where('status', 'active')->where('created_by', Auth::user()->id)->latest()->get();
            }
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


    







//the cron is mweguni.co.ke/App/Console/Commands/LoanExpiry.php
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Loan::where('loan_id', $id)->delete();
        return redirect()->route('loan.index')->with('success', 'Loan deleted successfully');
    }



}
