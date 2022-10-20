<?php

namespace App\Http\Controllers;

use App\Models\Disburse;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DatesValidator;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
           
        $disburses = Disburse::with('loan', 'disburser', 'disbursedTo')->paginate(20);
        $total_disburses = Disburse::count();
        
        if (isset($request->from_date) && isset($request->to_date)) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $res  = DatesValidator::validate($from_date, $to_date);
                
            if ($res != 'success') {
                # code...
                return redirect()->back()->with('error', $res);
            }
            $disburses = Disburse::with('loan', 'disburser', 'disbursedTo')->whereBetween('created_at', [$from_date, $to_date])->paginate(100);
        }
           
        return view('report.disburse', compact('disburses', 'total_disburses'));
         
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
        // if($user->role_id == 1){


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
            })->with('user')->paginate(20);

            // dd($customers);

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
            


            
        // }
        // else{
            
        
        //     $customers = customer::select("*")->where('created_by', $user->id)
        //     ->when($request->status, function ($query) use ($request) {
        //         //check if the request is active or inactive, if it is active search status = 1, if it is inactive search status = 0
        //         if ($request->status == 'active'){
        //             $status = 1;
        //         }
        //         else{
        //             $status = 0;
        //         }
        //         return $query->where('status', $status);
        //     })
            
        //     ->when($request->from_date, function ($query) use ($request) {
        //         return $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        //     })->with('user')->paginate(100);

        //     $total_customers = customer::select("*")->where('created_by', $user->id)->when($request->status, function ($query) use ($request) {
        //         //check if the request is active or inactive, if it is active search status = 1, if it is inactive search status = 0
        //         if ($request->status == 'active'){
        //             $status = 1;
        //         }
        //         else{
        //             $status = 0;
        //         }
        //         return $query->where('status', $status);
        //     })
        //     ->when($request->from_date, function ($query) use ($request) {
        //         return $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        //     })->count();
        //     //get all customers


            
        // }
        return view('report.customer', compact( 'customers', 'total_customers'));
    }


    public function graphs(Request $request){
        $loanData = $this->getLoanData($request);

        $loanData = json_encode($loanData);
        //get into the loan data and get the total amount of loans
        // after encoding it becomes a string, so we have to decode it
        $loanData = json_decode($loanData);
        // go to original and get loans
        $loanData = $loanData->original->loans;
        //convert to json
        $loanData = json_encode($loanData);
        // dd($loanData);
        
        
      
        // $transactionData = $this->getTransactionData();
        // $transactionData = json_decode($transactionData);

        // $performanceData = $this->getPerformanceData();
        // $performanceData = json_decode($performanceData);
    }


    public function getLoanData($request){
        $user = Auth::user();
        // dd($user);
        $sortedData = []; //array to hold the sorted data
        $months = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ];
        if($user->role_id == 1){
            
            //get all loans created at different months for Graphs
            // set months in short form
            $loansByMonth = Loan::all()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('M'); // grouping by months
            });
            // dd($loansByMonth);
            $activeLoansByMonth = Loan::where('status', 'active')->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('M'); // grouping by months
            });
            

            $overDueLoansByMonth = Loan::where('status', 'overdue')->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('M'); // grouping by months
            });
            
            // pending loans
            $pendingLoansByMonth = Loan::where('status', 'pending')->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('M'); // grouping by months
            });

            // dd($loansByMonth, $activeLoansByMonth, $overDueLoansByMonth, $pendingLoansByMonth);


            foreach($months as $month){

                if(array_key_exists($month, $loansByMonth->toArray())){
                    foreach($loansByMonth as $loan => $value){
                        if($loan == $month){

                            $monthlyUniqueLoans = $value;
                            $monthlyLoansCount = count($monthlyUniqueLoans->toArray());
                            $monthlyLoansAmount = $monthlyUniqueLoans->pluck('total_payable')->sum();
                            $monthh = $month;
                            $type ='all';                         
                            array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);

                            break;
                        }
                      
                    }
                }
                // active loans by month
                if(array_key_exists($month, $activeLoansByMonth->toArray())){
                    foreach($activeLoansByMonth as $loan => $value){
                        if($loan == $month){
                            $monthlyUniqueLoans = $value;
                            $monthlyLoansCount = count($monthlyUniqueLoans->toArray());
                            $monthlyLoansAmount = $monthlyUniqueLoans->pluck('total_payable')->sum();
                            $monthh = $month;
                            $type = 'active';
                            array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);

                            break;
                        }
                      
                    }
                }

                // overdue loans by month
                if(array_key_exists($month, $overDueLoansByMonth->toArray())){
                    foreach($overDueLoansByMonth as $loan => $value){
                        if($loan == $month){
                            $monthlyUniqueLoans = $value;
                            $monthlyLoansCount = count($monthlyUniqueLoans->toArray());
                            $monthlyLoansAmount = $monthlyUniqueLoans->pluck('total_payable')->sum();
                            $monthh = $month;
                            $type = 'overdue';
                            array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);

                            break;
                        }
                      
                    }
                }

                // pending loans by month
                if(array_key_exists($month, $pendingLoansByMonth->toArray())){
                    foreach($pendingLoansByMonth as $loan => $value){
                        if($loan == $month){
                            $monthlyUniqueLoans = $value;
                            $monthlyLoansCount = count($monthlyUniqueLoans->toArray());
                            $monthlyLoansAmount = $monthlyUniqueLoans->pluck('total_payable')->sum();
                            $monthh = $month;
                            $type = 'pending';
                            array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);

                            break;
                        }
                      
                    }
                }

            }

            // if its filtered by 3 months, 6 months or 1 year
            // if($request->has('filter')){
            //     $filter = $request->filter;
            //     // check if the filter is 3 months
            //     if($filter == '3months'){
            //         // get the current month
            //         $currentMonth = Carbon::now()->format('F');
            //         // get the previous 3 months
            //         $previousMonths = Carbon::now()->subMonths(3)->format('F');

            //         $loans = Loan::whereBetween('created_at', [$previousMonths, $currentMonth])->get();
            //         $activeLoans = Loan::where('status', 'active')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $overDueLoans = Loan::where('status', 'overdue')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $pendingLoans = Loan::where('status', 'pending')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //     }

            //     // check if the filter is 6 months
            //     if($filter == '6months'){
            //         // get the current month
            //         $currentMonth = Carbon::now()->format('F');
            //         // get the previous 6 months
            //         $previousMonths = Carbon::now()->subMonths(6)->format('F');

            //         $loans = Loan::whereBetween('created_at', [$previousMonths, $currentMonth])->get();
            //         $activeLoans = Loan::where('status', 'active')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $overDueLoans = Loan::where('status', 'overdue')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $pendingLoans = Loan::where('status', 'pending')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //     }

            //     // check if the filter is 1 year
            //     if($filter == '1year'){
            //         // get the current month
            //         $currentMonth = Carbon::now()->format('F');
            //         // get the previous 1 year
            //         $previousMonths = Carbon::now()->subMonths(12)->format('F');

            //         $loans = Loan::whereBetween('created_at', [$previousMonths, $currentMonth])->get();
            //         $activeLoans = Loan::where('status', 'active')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $overDueLoans = Loan::where('status', 'overdue')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $pendingLoans = Loan::where('status', 'pending')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //     }
            // }


        }
        else if($user->role_id == 3){
            $loansByMonth = Loan::where('created_by', $user->id)->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('F'); // grouping by months
            });

            $activeLoansByMonth = Loan::where('status', 'active')->where('created_by', $user->id)->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('F'); // grouping by months
            });

            $overDueLoansByMonth = Loan::where('status', 'overdue')->where('created_by', $user->id)->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('F'); // grouping by months
            });

            $pendingLoansByMonth = Loan::where('status', 'pending')->where('created_by', $user->id)->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('F'); // grouping by months
            });

            // get the current month
            foreach($months as $month){
                // loans by month
                if(array_key_exists($month, $loansByMonth->toArray())){
                    foreach($loansByMonth as $loan => $value){
                        if($loan == $month){
                            $monthlyUniqueLoans = $value;
                            $monthlyLoansCount = count($monthlyUniqueLoans->toArray());
                            $monthlyLoansAmount = $monthlyUniqueLoans->pluck('total_payable')->sum();
                            $monthh = $month;
                            $type = 'all';
                           
                            array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);

                            break;
                        }
                      
                    }
                }

                // active loans by month
                if(array_key_exists($month, $activeLoansByMonth->toArray())){
                    foreach($activeLoansByMonth as $loan => $value){
                        if($loan == $month){
                            $monthlyUniqueLoans = $value;
                            $monthlyLoansCount = count($monthlyUniqueLoans->toArray());
                            $monthlyLoansAmount = $monthlyUniqueLoans->pluck('total_payable')->sum();
                            $monthh = $month;
                            $type = 'active';
                            array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);

                            break;
                        }
                      
                    }
                }

                // overdue loans by month
                if(array_key_exists($month, $overDueLoansByMonth->toArray())){
                    // $sortedData['loans'][$month] = count($loansByMonth[$month]);
                    foreach($overDueLoansByMonth as $loan => $value){
                        if($loan == $month){
                            $monthlyUniqueLoans = $value;
                            $monthlyLoansCount = count($monthlyUniqueLoans->toArray());
                            $monthlyLoansAmount = $monthlyUniqueLoans->pluck('total_payable')->sum();
                            $monthh = $month;
                            $type = 'overdue';
            
                            array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);

                            break;
                        }

                    }
                }
            }

        }

        $loanData = [];
        $activeLoanData = [];
        $overDueLoanData = [];
        $pendingLoanData = [];

        foreach($sortedData as $data){
            if($data['type'] == 'all'){
                // array_push($loanData, $data);
                $loanData[] = $data;
            }
            if($data['type'] == 'active'){
                // array_push($activeLoanData, $data);
                $activeLoanData[] = $data;
            }

            if($data['type'] == 'overdue'){
                // array_push($overDueLoanData, $data);
                $overDueLoanData[] = $data;
            }

            if($data['type'] == 'pending'){
                // array_push($pendingLoanData, $data);
                $pendingLoanData[] = $data;
            }
            // if($data['type'] == 'active'){
            //     array_push($activeLoanData, $data);
            // }
            // if($data['type'] == 'overdue'){
            //     array_push($overDueLoanData, $data);
            // }
            // if($data['type'] == 'pending'){
            //     array_push($pendingLoanData, $data);
            // }
        }


        return response()->json([
            'loans' => $loanData,
            'activeLoans' => $activeLoanData,
            'overDueLoans' => $overDueLoanData,
            'pendingLoans' => $pendingLoanData,
        ], 200);

        // return response()->json([
        //     'loans' => $loanData,
        //     'activeLoans' => $activeLoanData,
        //     'overDueLoans' => $overDueLoanData,
        //     'pendingLoans' => $pendingLoanData,
        // ], 200);

    
    }
    

      
}
