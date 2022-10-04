<?php

namespace App\Http\Controllers;

use App\Models\Disburse;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DatesValidator;

class ReportController extends Controller
{
    //transanction report
    public function transactionReport(Request $request)
    {
        try{
            $user = auth()->user();
            if($user->hasRole('admin')){
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = $request->from_date;
                    $to_date = $request->to_date;
                    $res  = DatesValidator::validate($from_date, $to_date);
                    
                    if ($res != 'success') {
                        # code...
                        return redirect()->back()->with('error', $res);
                    }
                    $transactions = Transaction::with('payment_gateway', 'user', 'customer', 'loan')->whereBetween('created_at', [$from_date, $to_date])->paginate(100);
                }
                else{
                    $transactions = Transaction::with('payment_gateway', 'user', 'customer', 'loan')->paginate(100);
                }
                // $transactions = Transaction::with('payment_gateway', 'user', 'customer', 'loan')->paginate(100);

            }else{
                $transactions = Transaction::with('payment_gateway', 'user', 'customer', 'loan')->where('user_id', $user->id)->paginate(100);
            }
            return view('report.transaction.index', compact('transactions'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //disburse report
    public function disburseReport(Request $request)
    {
       // dd('here');
       
        $user = auth()->user();
        if($user->role_id == 1){
            if (isset($request->from_date) && isset($request->to_date)) {
                $from_date = $request->from_date;
                $to_date = $request->to_date;
                $res  = DatesValidator::validate($from_date, $to_date);
                    
                if ($res != 'success') {
                    # code...
                    return redirect()->back()->with('error', $res);
                }
                $disburses = Disburse::with('user', 'customer', 'loan')->whereBetween('created_at', [$from_date, $to_date])->paginate(100);
            }
            else{
                $disburses = Disburse::with('user', 'customer', 'loan')->paginate(100);
                dd($disburses);
            }
            // $transactions = Transaction::with('payment_gateway', 'user', 'customer', 'loan')->paginate(100);

        }else{
            $disburses = Disburse::with('user', 'customer', 'loan')->where('user_id', $user->id)->paginate(100);
        }
        return view('report.disburse', compact('disburses'));
        
  
    }

    //loan report
    public function loanReport(Request $request)
    {
        try{
            $user = auth()->user();
            if($user->hasRole('admin')){
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = $request->from_date;
                    $to_date = $request->to_date;
                    $res  = DatesValidator::validate($from_date, $to_date);
                    
                    if ($res != 'success') {
                        # code...
                        return redirect()->back()->with('error', $res);
                    }
                    $loans = Loan::with('user', 'customer')->whereBetween('created_at', [$from_date, $to_date])->paginate(100);
                }
                else{
                    $loans = Loan::with('user', 'customer')->paginate(100);
                }
                // $transactions = Transaction::with('payment_gateway', 'user', 'customer', 'loan')->paginate(100);

            }else{
                $loans = Loan::with('user', 'customer')->where('user_id', $user->id)->paginate(100);
            }
            return view('report.loan.index', compact('loans'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //performance report
    public function performanceReport(Request $request)
    {
        try{
            $user = auth()->user();
            if($user->hasRole('admin')){
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = $request->from_date;
                    $to_date = $request->to_date;
                    $res  = DatesValidator::validate($from_date, $to_date);
                    
                    if ($res != 'success') {
                        # code...
                        return redirect()->back()->with('error', $res);
                    }
                    //calculate performance of a lender user according to the date range
                    //the performance is calculated from all loans, the lender user has created and how much money he has disbursed and how much money he has collected, from the loans paid

                    //get all loans created by the lender users
                    $loans = Loan::with('user', 'customer', 'disburse', 'transaction')->whereBetween('created_at', [$from_date, $to_date])->get();
                    $total_registered_loans = 0;
                    $total_paid_loans = 0;
                    $total_disbursed_amount = 0;
                    $total_collected_amount = 0;
                    $total_profit = 0;
                    $performance = 0;
                    foreach($loans as $loan){
                        $total_registered_loans += 1;
                        if($loan->status == 'paid'){
                            $total_paid_loans += 1;
                            $total_disbursed_amount += $loan->disburse->amount;
                            $total_collected_amount += $loan->transaction->amount;
                            $total_profit += $loan->transaction->amount - $loan->disburse->amount;


                        }
                    }
                    $total_registered_loans = number_format($total_registered_loans, 2);
                    $total_paid_loans = number_format($total_paid_loans, 2);
                    $total_disbursed_amount = number_format($total_disbursed_amount, 2);
                    $total_collected_amount = number_format($total_collected_amount, 2);
                    $total_profit = number_format($total_profit, 2);

                    if($total_disbursed_amount > 0){
                        $performance = ($total_collected_amount / $total_disbursed_amount) * 100;
                    }
                }

                $loans = Loan::with('user', 'customer', 'disburse', 'transaction')->paginate(100);
                $total_registered_loans = 0;
                $total_paid_loans = 0;
                $total_disbursed_amount = 0;
                $total_collected_amount = 0;
                $total_profit = 0;
                $performance = 0;
                foreach($loans as $loan){
                    $total_registered_loans += 1;
                    if($loan->status == 'paid'){
                        $total_paid_loans += 1;
                        $total_disbursed_amount += $loan->disburse->amount;
                        $total_collected_amount += $loan->transaction->amount;
                        $total_profit += $loan->transaction->amount - $loan->disburse->amount;

                    }
                }
                $total_registered_loans = number_format($total_registered_loans, 2);
                $total_paid_loans = number_format($total_paid_loans, 2);
                $total_disbursed_amount = number_format($total_disbursed_amount, 2);
                $total_collected_amount = number_format($total_collected_amount, 2);
                $total_profit = number_format($total_profit, 2);

                if($total_disbursed_amount > 0){
                    $performance = ($total_collected_amount / $total_disbursed_amount) * 100;
                }

            }
            else{
                //calculate performance of a lender user according to the date range
                //the performance is calculated from all loans, the lender user has created and how much money he has disbursed and how much money he has collected, from the loans paid

                //get all loans created by the lender users
                $loans = Loan::with('user', 'customer', 'disburse', 'transaction')->where('user_id', $user->id)->paginate(100);
                $total_registered_loans = 0;
                $total_paid_loans = 0;
                $total_disbursed_amount = 0;
                $total_collected_amount = 0;
                $total_profit = 0;
                $performance = 0;
                foreach($loans as $loan){
                    $total_registered_loans += 1;
                    if($loan->status == 'paid'){
                        $total_paid_loans += 1;
                        $total_disbursed_amount += $loan->disburse->amount;
                        $total_collected_amount += $loan->transaction->amount;
                        $total_profit += $loan->transaction->amount - $loan->disburse->amount;

                    }
                }
                $total_registered_loans = number_format($total_registered_loans, 2);
                $total_paid_loans = number_format($total_paid_loans, 2);
                $total_disbursed_amount = number_format($total_disbursed_amount, 2);
                $total_collected_amount = number_format($total_collected_amount, 2);
                $total_profit = number_format($total_profit, 2);

                if($total_disbursed_amount > 0){
                    $performance = ($total_collected_amount / $total_disbursed_amount) * 100;
                }

                //check if theuser is searching for a specific date range
                if (isset($request->from_date) && isset($request->to_date)) {
                    $from_date = $request->from_date;
                    $to_date = $request->to_date;
                    //calculate performance of a lender user according to the date range
                    //the performance is calculated from all loans, the lender user has created and how much money he has disbursed and how much money he has collected, from the loans paid

                    //get all loans created by the lender users
                    $loans = Loan::with('user', 'customer', 'disburse', 'transaction')->where('user_id', $user->id)->whereBetween('created_at', [$from_date, $to_date])->get();
                    $total_registered_loans = 0;
                    $total_paid_loans = 0;
                    $total_disbursed_amount = 0;
                    $total_collected_amount = 0;
                    $total_profit = 0;
                    $performance = 0;
                    foreach($loans as $loan){
                        $total_registered_loans += 1;
                        if($loan->status == 'paid'){
                            $total_paid_loans += 1;
                            $total_disbursed_amount += $loan->disburse->amount;
                            $total_collected_amount += $loan->transaction->amount;
                            $total_profit += $loan->transaction->amount - $loan->disburse->amount;

                        }
                    }
                    $total_registered_loans = number_format($total_registered_loans, 2);
                    $total_paid_loans = number_format($total_paid_loans, 2);
                    $total_disbursed_amount = number_format($total_disbursed_amount, 2);
                    $total_collected_amount = number_format($total_collected_amount, 2);
                    $total_profit = number_format($total_profit, 2);

                    if($total_disbursed_amount > 0){
                        $performance = ($total_collected_amount / $total_disbursed_amount) * 100;
                    }
                }
            }
            return view('report.performance', compact('user', 'loans', 'total_registered_loans', 'total_paid_loans', 'total_disbursed_amount', 'total_collected_amount', 'total_profit', 'performance'));

                // $loans = Loan::where('user_id', $user->id)->whereBetween('created_at', [$from_date, $to_date])->get();
                // $total_loan_amount = 0;
                // $total_disburse_amount = 0;
                // //look at this keenly
                // $total_collection_amount = 0;
                // $performance = 0;
                // foreach($loans as $loan){
                //     $total_loan_amount += $loan->amount;
                //     $total_disburse_amount += $loan->disburse->amount;
                //     $total_collection_amount += $loan->transaction->amount;
                // }
                // $performance = ($total_collection_amount - $total_disburse_amount) / $total_loan_amount * 100;
              

       
            // return view('report.performance.index', compact('loans'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //customer report
    public function customerReport(Request $request){
        // dd('here');
       
        $user = Auth::user();
        
        $res  = DatesValidator::validate($request->from_date, $request->to_date);
        //dd($request->from_date, $request->to_date, $res);

        if ($res != 'success') {
            # code...
            return redirect()->back()->with('error', $res);
        }
            if($user->role_id == 1){
   

                $customers = customer::select("*")->when($request->status, function ($query) use ($request) {
                    //check if the request is active or inactive, if it is active search status = 1, if it is inactive search status = 0
                    if ($request->status == 'active'){
                        $status = 1;
                    }
                    else{
                        $status = 0;
                    }
                    return $query->where('status', $status);
                })
                ->when($request->from_date, function ($query) use ($request) {
                    return $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
                })->with('user')->paginate(100);

                $total_customers = customer::select("*")->when($request->status, function ($query) use ($request) {
                    //check if the request is active or inactive, if it is active search status = 1, if it is inactive search status = 0
                    if ($request->status == 'active'){
                        $status = 1;
                    }
                    else{
                        $status = 0;
                    }
                    return $query->where('status', $status);
                })
                ->when($request->from_date, function ($query) use ($request) {
                    return $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
                })->count();
                

                // ->when($request->has('status') && isset($request->status), function ($query) use ($request) {
                //     $query->where('contents.status', 'LIKE', '%' . $request->status . '%');
                // })

                // ->when($request->has('start_date') && isset($request->start_date), function ($query) use ($request) {
                //     $query->whereBetween('created_at', [Carbon::create($request->start_date), Carbon::create($request->end_date)]);
                // })
                // ->with(['platform', 'user', 'calendar', 'cover'])->get();

                //check if the user is searching for a specific date range
                // if (isset($request->from_date) && isset($request->to_date)) {
                //     $from_date = $request->from_date;
                //     $to_date = $request->to_date;
                //     $res  = DatesValidator::validate($from_date, $to_date);
                    
                //     if ($res != 'success') {
                //         # code...
                //         return redirect()->back()->with('error', $res);
                //     }

                    
                //      //check if status is set
                //     if(isset($request->status)){
                //         $request->status;
                //         if ($request->status == 'active'){
                //             $status = 1;
                //         }
                //         else{
                //             $status = 0;
                //         }
                //         // get customers within the date range and with the status
                //         $customers = customer::with('user')->whereBetween('created_at', [$from_date, $to_date])->where('status', $status)->paginate(100);
                //         dd($customers);
                //         $total_customers = customer::whereBetween('created_at', [$from_date, $to_date])->where('status', $status)->count();
                //         $total_active_customers = customer::whereBetween('created_at', [$from_date, $to_date])->where('status', $status)->where('status', 'active')->count();
                //         $total_inactive_customers = customer::whereBetween('created_at', [$from_date, $to_date])->where('status', $status)->where('status', 'inactive')->count();
                //     }
                    // $customers = customer::with('user')->whereBetween('created_at', [$from_date, $to_date])->paginate(100);
                    
                    // $total_customers = customer::whereBetween('created_at', [$from_date, $to_date])->count();
                    // $total_active_customers = customer::where('status', 'active')->whereBetween('created_at', [$from_date, $to_date])->count();
                    // $total_inactive_customers = customer::where('status', 'inactive')->whereBetween('created_at', [$from_date, $to_date])->count();
                
            }
            else{
                
           
                $customers = customer::select("*")->where('created_by', $user->id)
                ->when($request->status, function ($query) use ($request) {
                    //check if the request is active or inactive, if it is active search status = 1, if it is inactive search status = 0
                    if ($request->status == 'active'){
                        $status = 1;
                    }
                    else{
                        $status = 0;
                    }
                    return $query->where('status', $status);
                })
                
                ->when($request->from_date, function ($query) use ($request) {
                    return $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
                })->with('user')->paginate(100);

                $total_customers = customer::select("*")->where('created_by', $user->id)->when($request->status, function ($query) use ($request) {
                    //check if the request is active or inactive, if it is active search status = 1, if it is inactive search status = 0
                    if ($request->status == 'active'){
                        $status = 1;
                    }
                    else{
                        $status = 0;
                    }
                    return $query->where('status', $status);
                })
                ->when($request->from_date, function ($query) use ($request) {
                    return $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
                })->count();
                //get all customers


                
            }
            return view('report.customer', compact( 'customers', 'total_customers'));
        }
    

      
}
