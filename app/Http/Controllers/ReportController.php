<?php

namespace App\Http\Controllers;

use App\Models\Disburse;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DatesValidator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Termwind\Components\Dd;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //transanction report


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
                $loans = Loan::with('user', 'customer')->where('created_by', $user->id)->paginate(100);
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


        $loans = Loan::with('creator', 'customer', 'disburse')->whereIn('status', ['approved', 'disbursed', 'paid'])->get();
        //role_id = 2 is the role of the ro or role_4 which is a mix of branch manager and ro so we use query builder
        $ros = User::where(function($query){
            $query->where('role_id', 2)->orWhere('role_id', 4);
        })->get();
 
        $performance = [];
        $total_loan_amount = [];


  
        if (isset($request->from_date) && isset($request->to_date)) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $res  = DatesValidator::validate($from_date, $to_date);
            
            if ($res != 'success') {
                # code...
                return redirect()->back()->with('error', $res);
            }
            //check if the ro_name is set
            if (isset($request->ro_name)) {
                $ro_name = $request->ro_name;
                //search by first_name or last_name
                $ros = User::where(function ($query){
                    $query->where('role_id', 2)->orWhere('role_id', 4);
                })->where('first_name', 'like', '%'.$ro_name.'%')->orWhere('last_name', 'like', '%'.$ro_name.'%')->get();
                // since its a collection, we need to get the first element

                $ro_id = $ros->first()->id;
                $total_amount = Loan::where('created_by', $ro_id)->whereBetween('created_at', [$from_date, $to_date])->sum('amount');
                $total_payable_amount = Loan::where('created_by', $ro_id)->whereBetween('created_at', [$from_date, $to_date])->sum('total_payable');
                $total_overdue_loans = Loan::where('created_by', $ro_id)->whereBetween('created_at', [$from_date, $to_date])->where('status', 'overdue')->count();
                if ($total_overdue_loans == 0) {
                    $performancey = 0;
                }
                else{
                    $performancey = ($total_payable_amount / $total_amount) * 100;
                }

                // $ro_id = (string)$ros->id;

                array_push($performance,[
                    'ro_id' => $ro_id,
                    'performance' => $performancey
                ]);

                $data = [
                    'ro_id' => $ro_id,
                    'total_loans' => Loan::where('created_by', $ro_id)->whereBetween('created_at', [$from_date, $to_date])->count(),
                    'total_amount' => $total_amount,
                    'total_payable' => $total_payable_amount,
                    'total_active_loans' => Loan::where('created_by', $ro_id)->where('status', 'active')->count(),
                    'total_overdue_loans' =>Loan::where('created_by', $ro_id)->where('status', 'overdue')->count(),
                    'total_overdue_amount' => Loan::where('created_by', $ro_id)->where('status', 'overdue')->sum('total_payable'),
                ];

                array_push($total_loan_amount, $data);

                
            }
            else{
                foreach ($ros as $ro) {
                    // performance is processing fee +loan_interest / amount
                    //  $processing_fee = Loan::where('created_by', $ro->id)->whereBetween('created_at', [$from_date, $to_date])->sum('processing_fee');
                    //  $loan_interest = Loan::where('created_by', $ro->id)->whereBetween('created_at', [$from_date, $to_date])->sum('interest');
                     $total_amount = Loan::where('created_by', $ro->id)->whereBetween('created_at', [$from_date, $to_date])->sum('amount');
                     $total_payable_amount = Loan::where('created_by', $ro->id)->whereBetween('created_at', [$from_date, $to_date])->sum('total_payable');
                     $total_overdue_loans = Loan::where('created_by', $ro->id)->where('status', 'overdue')->sum('total_payable');
     
                     
                     if ($total_overdue_loans == 0 ) {
                         $performancey = 0;
                     }else{
                         $performancey= ($total_overdue_loans / $total_payable_amount) * 100;
                     }
     
                     // $performancey = number_format($performancey, 2);
                     //turn $ro->id to string
                     $ro_id = (string)$ro->id;
     
                     array_push($performance, [
                         'ro_id' => $ro_id,
                         'performance' => $performancey
                     ]);
     
                     $data =[
                         'ro_id' => $ro_id,
                         'total_loans' => Loan::where('created_by', $ro->id)->whereBetween('created_at', [$from_date, $to_date])->count(),
                         'total_amount' => $total_amount,
                         'total_payable' => $total_payable_amount,
                         'total_active_loans' => Loan::where('created_by', $ro->id)->where('status', 'active')->count(),
                         'total_overdue_loans' => Loan::where('created_by', $ro->id)->where('status', 'overdue')->count(),
                         'total_overdue_amount' => Loan::where('created_by', $ro->id)->where('status', 'overdue')->sum('total_payable'),
                     ];
         
                     array_push($total_loan_amount, $data);
     
                     
                }
            }
           
            //in $performance, set ro_id as key and performance as value
            $performance = array_column($performance, 'performance', 'ro_id');
        
        }
        else{
            if (isset($request->ro_name)){
                
                $ro_name = $request->ro_name;
                
                //search by first_name or last_name
                $ros = User::where(function ($query){
                    $query->where('role_id', 2)->orWhere('role_id', 4);
                })->where('first_name', 'like', '%'.$ro_name.'%')->orWhere('last_name', 'like', '%'.$ro_name.'%')->get();
                //get ro_id from the collection
                $ro_id = $ros[0]->id;
                // dd($ro_id);
                $total_amount = Loan::where('created_by', $ro_id)->sum('amount');
                $total_payable_amount = Loan::where('created_by', $ro_id)->sum('total_payable');
                $total_overdue_loans = Loan::where('created_by', $ro_id)->where('status', 'overdue')->count();
                if ($total_overdue_loans == 0) {
                    $performancey = 0;
                }
                else{
                    $performancey = ($total_payable_amount / $total_amount) * 100;
                }
                $performancey = number_format($performancey, 2);


                array_push($performance,[
                    'ro_id' => $ro_id,
                    'performance' => $performancey
                ]);

                $data = [
                    'ro_id' => $ro_id,
                    'total_loans' => Loan::where('created_by', $ro_id)->count(),
                    'total_amount' => $total_amount,
                    'total_payable' => $total_payable_amount,
                    'total_active_loans' => Loan::where('created_by', $ro_id)->where('status', 'active')->count(),
                    'total_overdue_loans' =>Loan::where('created_by', $ro_id)->where('status', 'overdue')->count(),
                    'total_overdue_amount' => Loan::where('created_by', $ro_id)->where('status', 'overdue')->sum('total_payable'),
                ];

                array_push($total_loan_amount, $data);

                

            }
            
            else{
                //loop through ROS and get their total registered loans, total paid loans, total disbursed amount, total collected amount, total profit and performance
                foreach ($ros as $ro) {
                    //get loans for this RO, using the created_by field
                    $total_registered_loans = Loan::where('created_by', $ro->id)->count();
                    $total__loans = Loan::where('created_by', $ro->id)->where('status', 'paid')->count();
                
                    // performance is processing fee +loan_interest / amount
                    $processing_fee = Loan::where('created_by', $ro->id)->sum('processing_fee');
                    $loan_interest = Loan::where('created_by', $ro->id)->sum('interest');
                    $total_amount = Loan::where('created_by', $ro->id)->sum('amount');
                    $total_payable_amount = Loan::where('created_by', $ro->id)->sum('total_payable');

                    $total_overdue_loans = Loan::where('created_by', $ro->id)->where('status', 'overdue')->sum('total_payable');

                        
                        if ($total_overdue_loans == 0 ) {
                            $performancey = 0;
                        }else{
                            $performancey= ($total_overdue_loans / $total_payable_amount) * 100;
                        }
                    // $performancey= ($total_profit / $total_amount) * 100;

                    $performancey = number_format($performancey, 2);
                    //turn $ro->id to string
                    $ro_id = (string)$ro->id;


                    //array push
                    array_push($performance, [
                        'ro_id' => $ro_id,
                        'performance' => $performancey
                    ]);

                    $data =[
                        'ro_id' => $ro_id,
                        'total_loans' => $total_registered_loans,
                        'total_amount' => $total_amount,
                        'total_payable' => $total_payable_amount,
                        'total_active_loans' => Loan::where('created_by', $ro->id)->where('status', 'active')->count(),
                        'total_overdue_loans' => Loan::where('created_by', $ro->id)->where('status', 'overdue')->count(),
                        'total_overdue_amount' => Loan::where('created_by', $ro->id)->where('status', 'overdue')->sum('amount'),
                    ];

                    array_push($total_loan_amount, $data);


                }
            }
            
            //in $performance, set ro_id as key and performance as value
            $performance = array_column($performance, 'performance', 'ro_id');  
        }


    
           

      
        return view('report.performance', compact('ros', 'performance', 'loans', 'total_loan_amount'));
    
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
            



        return view('report.customer', compact( 'customers', 'total_customers'));
    }

    public function transactionReport(Request $request)
    {      
        $user = auth()->user();
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $res  = DatesValidator::validate($from_date, $to_date);
                   
        if ($res != 'success') {
            return redirect()->back()->with('error', $res);
        }
        // also check if $request->customer_phone is set
        $transactions = Transaction::select("*")->when($request->from_date, function ($query) use ($request) {
            return $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        })->when($request->customer_phone, function ($query) use ($request) {
            return $query->where('customer_phone', $request->customer_phone);
        })->with('customer', 'loan')->paginate(20);

        $total_transactions = Transaction::select("*")->when($request->from_date, function ($query) use ($request) {
            return $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        })->when($request->customer_phone, function ($query) use ($request) {
            return $query->where('customer_phone', $request->customer_phone);
        })->count();

        return view('report.transaction', compact('transactions', 'total_transactions'));

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
