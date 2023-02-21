<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Jobs\SendEmailJob;
use App\Models\branch;
use App\Models\customer;
use App\Models\User;
use Illuminate\Http\Request;
#mail
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionMail;
use App\Helpers\LoanHelper;
use App\Models\Loan;
use Termwind\Components\Dd;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    
    public function index(Request $request)
    {

        // dd($request->all());

        //use selected type to search the input called src
        $customers = customer::select('*')->when($request->type, function ($query) use ($request) {
            if ($request->type == 'phone') {
                return $query->where('phone', 'like', '%' . $request->src . '%');
            } elseif ($request->type == 'national_id') {           
                return $query->where('national_id', 'like', '%' . $request->src . '%');
            } elseif ($request->type == 'email') {
                return $query->where('email', 'like', '%' . $request->src . '%');
            }})->paginate(20);
        //branches that are active
        // $branches = branch::where('status', 'active')->get();
        
        return view('customer.index', compact('customers'));
    }

    //active customers

    public function active()
    {
        //customers paginated to 20
        $customers = customer::where('status', 1)->paginate(20);
        //branches that are active
        // $branches = branch::where('status', 'active')->get();

        
        return view('customer.active', compact('customers'));
    }

    //inactive customers

    public function inactive()
    {
        //customers paginated to 20
        $customers = customer::with('branch')->where('status', 0)->paginate(20);
        //branches that are active
        // $branches = branch::where('status', 'active')->get();
        
        return view('customer.inactive', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = branch::where('status', 'active')->get();
        return view('customer.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'first_name'   => 'required',
            'last_name'    => 'required',
            'phone'  => 'required',
            'email_address' => 'email|unique:customers',
           // 'status'        => 'required',
            'national_id'        => 'required|unique:customers',
            'branch_id'        => 'required',
            #guarantor
            'guarantor_first_name'        => 'required',
            'guarantor_last_name'        => 'required',
            'guarantor_phone'        => 'required',
            'guarantor_national_id'        => 'required|unique:customers',
            'guarantor_email'        => 'email|unique:customers',
            'guarantor_address'        => 'required',
            #referee
            'referee_first_name'        => 'required',
            'referee_last_name'        => 'required',
            'referee_phone'        => 'required',
            'referee_relationship'        => 'required',
            'next_of_kin_first_name'        => 'required',
            'next_of_kin_last_name'        => 'required',
            'next_of_kin_phone'            => 'required',
            'next_of_kin_relationship'        => 'required',
        ]);
        //get the user who has created the customer

        //check that the phone number follows the kenyan format according to the LoanHelper class in the Helpers folder
        //if it returns false, return back with an error message
        if(!LoanHelper::checkPhoneNumber($request->phone)){
            return back()->with('error', 'Phone number must be in the format 07xx xxx xxx or 011 xxx xxx');
        }


        $request->merge([
            'created_by' => auth()->user()->id,
            'status' => 'active',
        ]);


        $customer = customer::create($request->all());
        return redirect()->route('customer.index')->with('success', 'Customer Created Successfully')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)  {

        
        
        $customer = customer::find($id);

        $loans = Loan::where('customer_id', $id)->get();

        // dd($user_transactions);


        return view('customer.show', compact('customer', 'loans'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         if (!Auth()->user()->can('user.edit')) {
            return abort(401);
         }
         $customer = customer::findorfail($id);
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, customer $customer)
    {
        //
        $request->validate([
            'first_name'   => 'required',
            'last_name'    => 'required',
            // 'phone_nmuber'  => 'required',
            'email_address' => 'email|unique:customers,email_address,'.$customer->id,
            'status'        => 'required',
            #national id
            'national_id'        => 'required',
            'branch_id'        => 'required',
            #guarantor
            'guarantor_first_name'        => 'required',
            'guarantor_last_name'        => 'required',
            'guarantor_phone_nmuber'        => 'required',
            // 'guarantor_email_address'        => 'email|unique:customers,email_address,'.$customer->id,
            'guarantor_national_id'        => 'required',
            #referee
            'referee_first_name'        => 'required',
            'referee_last_name'        => 'required',
            'referee_phone_nmuber'        => 'required',
            #emai not required for referee but must be unique
            'referee_email_address'        => 'email|unique:customers,email_address,'.$customer->id,
            'referee_national_id'        => 'required',
            #next of kin
            'next_of_kin_first_name'        => 'required',
            'next_of_kin_last_name'        => 'required',
            'next_of_kin_phone_nmuber'        => 'required',
            'next_of_kin_email_address'        => 'email|unique:customers,email_address,'.$customer->id,
            'next_of_kin_national_id'        => 'required',
            #next of kin relationship
            'next_of_kin_relationship'        => 'required',
        ]);



        $customer->update($request->all());
        return redirect()->route('customer.index')->with('success', 'Customer Updated Successfully');
    }
    //search customers by national id, branch_name
    public function search(Request $request){
        $search = $request->get('search');
        $customers = customer::with('branch')
        ->where('national_id', 'like', '%'.$search.'%')
        ->orWhere('phone', 'like', '%'.$search.'%')
        ->orWhereHas('branch', function($query) use ($search){
            $query->where('name', 'like', '%'.$search.'%');
        })->get();


        
        return view('customer.index', compact('customers'));
    }
    public function customerTransactionMail(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required',
            'msg'     => 'required',
        ]);
        $customer      = customer::where('id', $id)->first();
        //check if email exists for customer
        if (!is_null($customer->email)) {
            $customer_email = $customer->email;
            $data      = [
                'email' => $customer_email,
                'subject' => $request->subject,
                'msg'     => $request->msg,
                'type'    => 'user_transaction_mail',
            ];
            if(env('QUEUE_MAIL') == 'on'){
                dispatch(new SendEmailJob($data));
            }else{
                Mail::to($customer_email)->send(new TransactionMail($data));
            }
        } else {
            return redirect()->back()->with('error', 'Customer does not have an email address');
        }


        

        return response()->json('Mail Send Successfully');
    }

    public function transactionReport($type, $id){
        $customer = customer::findorfail($id);

        // if ($type == 'withdraw') {
        //     $data = Transaction::where('user_id',$id)
        //     ->where(function ($query){
        //     $query->where('type','ecurrency_transfer')
        //     ->orWhere('type','otherbank_transfer')
        //     ->orWhere('type','debit')
        //     ->orWhere('type','ownbank_transfer_debit')
        //     ->orWhere('type','bill_debit');
        //     })
        // ->latest()->get();
        // }elseif ($type == 'deposit') {
        //     $data = Transaction::where('user_id',$id)
        //     ->where(function ($query){
        //         $query->where('type','edeposit')
        //         ->orWhere('type','credit')
        //         ->orWhere('type','ownbank_transfer_credit')
        //         ->orWhere('type','bill_credit');
        //     })
        //     ->latest()->get();
        // }else{
        //     $data = Transaction::where('user_id',$id)
        //     ->latest()->get();
        // }
        return view('customer.report', compact('customers'))->with('i', 1);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $customer = customer::find($id);
        $customer->delete();
        return redirect()->route('customer.index')->with('success', 'Customer Deleted Successfully');
    }




 
}
