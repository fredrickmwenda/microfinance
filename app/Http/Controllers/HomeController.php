<?php

namespace App\Http\Controllers;

use App\Helpers\Charts;
use App\Models\customer;
use App\Models\Disburse;
use App\Models\Loan;
use App\Models\User;
use App\Models\Transaction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Termwind\Components\Dd;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth()->user()->can('dashboard.index')) {
            return abort(401);
         }


        $total_users = User::count();
         //pending loans
        $total_customers = customer::count();
        //total applied loans
        $total_loans = Loan::count();
        

        //loans
        //paginate Loans where status is pending, while order by created_at desc
        $all_pending_loans = Loan::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(10);
        $all_rejected_loans = Loan::where('status', 'rejected')->orderBy('created_at', 'desc')->paginate(10);
        $all_approved_loans = Loan::where('status', 'approved')->orderBy('created_at', 'desc')->paginate(10);
        $all_disbursed_loans = Loan::where('status', 'disbursed')->orderBy('created_at', 'desc')->paginate(10);
        $all_closed_loans = Loan::where('status', 'closed')->orderBy('created_at', 'desc')->paginate(10);
        $all_written_off_loans = Loan::where('status', 'written_off')->orderBy('created_at', 'desc')->paginate(10);
        $all_recovered_loans = Loan::where('status', 'recovered')->orderBy('created_at', 'desc')->paginate(10);
        $all_overdue_loans = Loan::where('status', 'overdue')->orderBy('created_at', 'desc')->paginate(10);
        $all_defaulted_loans = Loan::where('status', 'defaulted')->orderBy('created_at', 'desc')->paginate(10);

        //get Total interest earned from closed loans
        $total_interest_earned = Loan::where(function ($query) {
            $query->where('status', 'closed')
                ->orWhere('status', 'active');
        })->sum('interest');
        $profit = $total_interest_earned;

        //total disbursed amount
        $total_disbursed_amount = Disburse::sum('disbursement_amount');
        $expenditure = $total_disbursed_amount;
        


        $total_disbursed = Disburse::count();



        
        $total_pending_loans = Loan::where('status', 'pending')->count();
        $total_approved_loans = Loan::where('status', 'approved')->count();

    
        
        $total_rejected_loans = Loan::where('status', 'rejected')->count();
        $total_disbursed_loans = Loan::where('status', 'disbursed')->count();
        $total_closed_loans = Loan::where('status', 'closed')->count();
        $total_written_off_loans = Loan::where('status', 'written_off')->count();
        $total_recovered_loans = Loan::where('status', 'recovered')->count();
        $total_overdue_loans = Loan::where('status', 'overdue')->count();
        $total_defaulted_loans = Loan::where('status', 'defaulted')->count();


        $total_amount_loans = Loan::sum('amount');
        $total_amount_pending_loans = Loan::where('status', 'pending')->sum('amount');
        $total_amount_approved_loans = Loan::where('status', 'approved')->sum('amount');
        $total_amount_rejected_loans = Loan::where('status', 'rejected')->sum('amount');
        $total_amount_disbursed_loans = Loan::where('status', 'disbursed')->sum('amount');
        $total_amount_closed_loans = Loan::where('status', 'closed')->sum('total_payable');
        $total_amount_recovered_loans = Loan::where('status', 'recovered')->sum('amount');
        $total_amount_written_off_loans = Loan::where('status', 'written_off')->sum('amount');
        $total_amount_overdue_loans = Loan::where('status', 'overdue')->sum('amount');
        $total_amount_defaulted_loans = Loan::where('status', 'defaulted')->sum('amount');

        //performance of ro officers according to the number of loans they have created which are approved, disbursed, closed or written off
        // $ro_officers = User::where('role', 'ro')->get();
        $ro_officers = User::where(function($query){$query->where('role_id', 2)->orWhere('role_id', 4);})->get();

        
        $months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        //per
        // $roData = DB::table('users')
        //     ->select('users.first_name', DB::raw('YEAR(loans.updated_at) as year'), DB::raw('MONTH(loans.updated_at) as month'),
        //              DB::raw('SUM(loans.total_payable) as total_payable_amount'),
        //              DB::raw('SUM(CASE WHEN loans.status = "overdue" THEN loans.total_payable ELSE 0 END) as total_overdue_loans'))
        //     ->join('loans', 'users.id', '=', 'loans.created_by')
        //     ->whereIn('users.role_id', [2, 4])
        //     ->whereIn('loans.status', ['disbursed', 'approved', 'closed', 'written_off', 'overdue'])
        //     ->groupBy('users.first_name', 'year', 'month')
        //     ->orderBy('year')
        //     ->orderBy('month')
        //     ->get();

        // $roData = DB::table('users')
        //     ->select('users.first_name', DB::raw('YEAR(loans.updated_at) as year'), DB::raw('MONTHNAME(loans.updated_at) as month'),
        //             DB::raw('SUM(loans.total_payable) as total_payable_amount'),
        //             DB::raw('SUM(CASE WHEN loans.status = "overdue" THEN loans.total_payable ELSE 0 END) as total_overdue_loans'))
        //     ->join('loans', 'users.id', '=', 'loans.created_by')
        //     ->whereIn('users.role_id', [2, 4])
        //     ->whereIn('loans.status', ['disbursed', 'approved', 'closed', 'written_off', 'overdue'])
        //     ->groupBy('users.first_name', 'year', 'month')
        //     ->orderBy('year')
        //     ->orderBy(DB::raw('MONTH(loans.updated_at)'))
        //     ->get();

        // $roData = DB::table('users')
        //     ->select('users.first_name', DB::raw('YEAR(loans.updated_at) as year'), DB::raw('DATE_FORMAT(loans.updated_at, "%b") as month'),
        //             DB::raw('SUM(loans.total_payable) as total_payable_amount'),
        //             DB::raw('SUM(CASE WHEN loans.status = "overdue" THEN loans.total_payable ELSE 0 END) as total_overdue_loans'))
        //     ->join('loans', 'users.id', '=', 'loans.created_by')
        //     ->whereIn('users.role_id', [2, 4])
        //     ->whereIn('loans.status', ['disbursed', 'approved', 'closed', 'written_off', 'overdue'])
        //     ->groupBy('users.first_name', 'year', 'month')
        //     ->orderBy('year')
        //     ->orderBy(DB::raw('MONTH(loans.updated_at)'))
        //     ->get();
        
        $currentYear = date('Y');      
        $roData = DB::table('users')
            ->select('users.first_name', DB::raw('DATE_FORMAT(loans.updated_at, "%b") as month'),
                    DB::raw('SUM(loans.total_payable) as total_payable_amount'),
                    DB::raw('SUM(CASE WHEN loans.status = "overdue" THEN loans.total_payable ELSE 0 END) as total_overdue_loans'))
            ->join('loans', 'users.id', '=', 'loans.created_by')
            ->whereIn('users.role_id', [2, 4])
            ->whereIn('loans.status', ['disbursed', 'approved', 'closed', 'written_off', 'overdue'])
            ->whereYear('loans.updated_at', $currentYear)
            ->groupBy('users.first_name', 'month')
            ->orderBy(DB::raw('MONTH(loans.updated_at)'))
            ->get();
        $roDataByRO = collect($roData)->groupBy('first_name');
        $roPerformanceByMonth = collect();

        foreach ($roDataByRO as $roName => $roData) {
            $roPerformance = collect();

            foreach ($roData as $data) {
                $performance = ($data->total_overdue_loans / $data->total_payable_amount) * 100;
                $roPerformance->put($data->month, $performance);
                // $roPerformance->put($data->year . '-' . $data->month, $performance);
            }

            $roPerformanceByMonth->put($roName, $roPerformance);
        }

        // dd($roPerformanceByMonth);

   




        $ro_officers_performance = [];
        $performance = [];
        
        foreach($ro_officers as $ro_officer){
            $loans = Loan::where('created_by', $ro_officer->id)->whereIn('status', ['approved', 'disbursed', 'closed', 'written_off'])->get();
            
           
            if(count($loans) > 0){
                
                $loansRO = Loan::where('created_by', $ro_officer->id)->whereIn('status', ['approved', 'disbursed', 'closed', 'written_off'])->get()->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('M');  
                });            
                foreach($months as $month){
                    //get the loans created by the ro officer in the month
                    if(array_key_exists($month, $loansRO->toArray())){
                    // dd( $loansRO[$month]);
                        
                        foreach ($loansRO  as $loan => $value) {
                        
                            if($loan == $month){
                                
                                $monthlyUniqueLoans  = $value;
                                $total_monthly_loans= count($monthlyUniqueLoans);
                                $total_processing_fee = $monthlyUniqueLoans->sum('processing_fee');
                                $total_loan_interest = $monthlyUniqueLoans->sum('interest');
                                $total_total_payable = $monthlyUniqueLoans->sum('total_payable');
                                $ro_username = $ro_officer->first_name;
                                // performance is processing fee +loan_interest / amount
                                $performance = ($total_processing_fee + $total_loan_interest) / $total_total_payable;

                                $performancePerc = $performance * 100;

                                $performancePerc = number_format($performancePerc, 2, '.', '');

                                

                                array_push($ro_officers_performance, [
                                    'usernames' => $ro_username,
                                    'month' => $month,
                                    'performance' => $performancePerc
                                ]);

                            }
                        }
                    }
            
                }
            
            }
        }
        //check for duplicates in $ro_officers_performance
        $ro_officers_performance = array_map("unserialize", array_unique(array_map("serialize", $ro_officers_performance)));
        //encode to json
        
       


        $ROPerformanceData  = json_encode($ro_officers_performance);
        // $performanceData  = json_encode($performance);
        // dd($performanceData);
        // dd($ROPerformanceData);


  

       $loanData   = $this->getLoanData();
        $loanData = json_encode($loanData);
     
        //get into the loan data and get the total amount of loans
        // after encoding it becomes a string, so we have to decode it
        $loanSet = json_decode($loanData);
        
       
        $loanData = $loanSet->original->loans;
        //convert to json
        $loanData = json_encode($loanData);


        $activeLoanData = $loanSet->original->activeLoans;
        $pendingLoanData = $loanSet->original->pendingLoans;
        $overDueLoanData = $loanSet->original->overDueLoans;
        $activeLoanData = json_encode($activeLoanData);
        $pendingLoanData = json_encode($pendingLoanData);
        $overDueLoanData = json_encode($overDueLoanData);


        $transactionData = $this->getTransactionsAndDisbursements();
        // dd($transactionData);
        $transactionData = json_encode($transactionData);

        $transactionSet = json_decode($transactionData);
        $transactionData = $transactionSet->original->transactions;

        $transactionData = json_encode($transactionData);


        $disbursementData = $transactionSet->original->disbursements;
        $disbursementData = json_encode($disbursementData);
        

        // get loans created between 7 days ago and today
        $loansCreatedWithin7Days = Loan::where('created_at', '>=',  Carbon::now()->subDays(7))->get();
        // dd($loansCreatedWithin7Days);



 


        

         return view('dashboard',compact('total_users','total_customers','all_pending_loans','all_approved_loans','all_rejected_loans','all_disbursed_loans','all_closed_loans','all_written_off_loans','all_recovered_loans','all_overdue_loans','all_defaulted_loans','total_loans','total_pending_loans','total_approved_loans','total_rejected_loans','total_disbursed_loans','total_closed_loans','total_written_off_loans','total_recovered_loans','total_overdue_loans','total_defaulted_loans','total_amount_pending_loans','total_amount_approved_loans','total_amount_rejected_loans','total_amount_closed_loans','total_interest_earned','profit','total_amount_pending_loans','total_amount_approved_loans','total_amount_rejected_loans','total_amount_disbursed_loans','total_amount_closed_loans','total_amount_written_off_loans','total_amount_recovered_loans','total_amount_overdue_loans','total_amount_defaulted_loans', 'expenditure', 'profit', 'total_loans', 'total_disbursed', 'total_disbursed_amount', 'total_amount_loans', 'loanData', 'activeLoanData', 'pendingLoanData', 'overDueLoanData','transactionData', 'disbursementData', 'ROPerformanceData', 'roPerformanceByMonth' ));
    }


     //function to get DashboardStatistics that passes start and end date to the function, returns the statistics for the period
    public function getDashboardStatistics($start_date, $end_date){
        // if start date matches end date, then it is a single day
        if($start_date == $end_date){
            $total_users = User::whereDate('created_at', $start_date)->count();
            $total_customers = Customer::whereDate('created_at', $start_date)->count();
            $total_loans = Loan::whereDate('created_at', $start_date)->count();
            $total_amount_loans = Loan::whereDate('created_at', $start_date)->sum('amount');

            //get Total disbursed loans
            $expenditure = Disburse::whereDate('created_at', $start_date)->sum('disbursement_amount');

            //get Total interest earned from closed loans
            $profit = Loan::where(function($query){
                $query->where('status', 'closed')
                ->orWhere('status', 'active');
            })->whereDate('updated_at', $start_date )->sum('interest');

            
            $total_pending_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'pending')->count();
            $total_approved_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'approved')->count();
            $total_rejected_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'rejected')->count();
            $total_disbursed_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'disbursed')->count();
            $total_active_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'active')->count();
            $total_closed_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'closed')->count();
            $total_written_off_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'written_off')->count();
            $total_recovered_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'recovered')->count();
            $total_overdue_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'overdue')->count();
            $total_defaulted_loans = Loan::whereDate('updated_at', $start_date)->where('status', 'defaulted')->count();
            

            $total_amount_pending_loans = Loan::where('status', 'pending')->whereDate('updated_at', $start_date)->sum('amount');
            $total_amount_approved_loans = Loan::where('status', 'approved')->whereDate('updated_at', $start_date)->sum('amount');
            $total_amount_rejected_loans = Loan::where('status', 'rejected')->whereDate('updated_at', $start_date)->sum('amount');
            $total_amount_disbursed_loans = Loan::where('status', 'disbursed')->whereDate('updated_at', $start_date)->sum('amount');
            $total_amount_active_loans = Loan::where('status', 'active')->whereDate('updated_at', $start_date)->sum('amount');
            $total_amount_closed_loans = Loan::where('status', 'closed')->whereDate('updated_at', $start_date)->sum('amount');
            $total_amount_written_off_loans = Loan::where('status', 'written_off')->whereDate('updated_at', $start_date)->sum('amount');
            $total_amount_recovered_loans = Loan::where('status', 'recovered')->whereDate('updated_at', $start_date)->sum('amount');
            $total_amount_overdue_loans = Loan::where('status', 'overdue')->whereDate('updated_at', $start_date)->sum('amount');
            $total_amount_defaulted_loans = Loan::where('status', 'defaulted')->whereDate('updated_at', $start_date)->sum('amount');
        }
        // The end date and start_date are  strings, check if the difference between them is 7 days
        else if(strtotime($end_date) - strtotime($start_date) == 604800){
            Log::info('7 days');
            $total_users = User::where('created_at', '>=', Carbon::parse($start_date)->startOfDay())->where('created_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_customers = Customer::where('created_at', '>=', Carbon::parse($start_date)->startOfDay())->where('created_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_loans = Loan::where('created_at', '>=', Carbon::parse($start_date)->startOfDay())->where('created_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_amount_loans = Loan::where('created_at', '>=', Carbon::parse($start_date)->startOfDay())->where('created_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');

            //get Total disbursed loans
            $expenditure = Disburse::where('created_at', '>=', Carbon::parse($start_date)->startOfDay())->where('created_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('disbursement_amount');

            //get Total interest earned from closed loans
            $profit = Loan::where(function($query){
                $query->where('status', 'closed')
                ->orWhere('status', 'active');
            })->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('interest');

            
            $total_pending_loans = Loan::where('status', 'pending')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_approved_loans = Loan::where('status', 'approved')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_rejected_loans = Loan::where('status', 'rejected')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_disbursed_loans = Loan::where('status', 'disbursed')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_active_loans = Loan::where('status', 'active')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_closed_loans = Loan::where('status', 'closed')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_written_off_loans = Loan::where('status', 'written_off')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_recovered_loans = Loan::where('status', 'recovered')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_overdue_loans = Loan::where('status', 'overdue')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();
            $total_defaulted_loans = Loan::where('status', 'defaulted')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->count();

            $total_amount_pending_loans = Loan::where('status', 'pending')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');
            $total_amount_approved_loans = Loan::where('status', 'approved')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');
            $total_amount_rejected_loans = Loan::where('status', 'rejected')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');
            $total_amount_disbursed_loans = Loan::where('status', 'disbursed')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');
            $total_amount_active_loans = Loan::where('status', 'active')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');
            $total_amount_closed_loans = Loan::where('status', 'closed')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');
            $total_amount_written_off_loans = Loan::where('status', 'written_off')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');
            $total_amount_recovered_loans = Loan::where('status', 'recovered')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');
            $total_amount_overdue_loans = Loan::where('status', 'overdue')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');
            $total_amount_defaulted_loans = Loan::where('status', 'defaulted')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('amount');

        }

        else{
   
        $total_users = User::whereBetween('created_at', [$start_date, $end_date])->count();
        $total_customers = Customer::whereBetween('created_at', [$start_date, $end_date])->count();
        $total_loans = Loan::whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_amount_loans = Loan::whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        //get Total disbursed loans
        $expenditure = Disburse::whereBetween('created_at', [$start_date, $end_date])->sum('disbursement_amount');

        //get Total interest earned from closed loans
        $profit = Loan::where(function($query){
            $query->where('status', 'closed')
            ->orWhere('status', 'active');
        })->whereBetween('updated_at', [$start_date, $end_date])->sum('interest');
        $total_pending_loans = Loan::where('status', 'pending')->whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_approved_loans = Loan::where('status', 'approved')->whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_rejected_loans = Loan::where('status', 'rejected')->whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_disbursed_loans = Loan::where('status', 'disbursed')->whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_active_loans = Loan::where('status', 'active')->whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_closed_loans = Loan::where('status', 'closed')->whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_written_off_loans = Loan::where('status', 'written_off')->whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_recovered_loans = Loan::where('status', 'recovered')->whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_overdue_loans = Loan::where('status', 'overdue')->whereBetween('updated_at', [$start_date, $end_date])->count();
        $total_defaulted_loans = Loan::where('status', 'defaulted')->whereBetween('updated_at', [$start_date, $end_date])->count();


        
        $total_amount_pending_loans = Loan::where('status', 'pending')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        $total_amount_approved_loans = Loan::where('status', 'approved')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        $total_amount_rejected_loans = Loan::where('status', 'rejected')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        $total_amount_disbursed_loans = Loan::where('status', 'disbursed')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        $total_amount_active_loans = Loan::where('status', 'active')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        $total_amount_closed_loans = Loan::where('status', 'closed')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        $total_amount_written_off_loans = Loan::where('status', 'written_off')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        $total_amount_recovered_loans = Loan::where('status', 'recovered')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        $total_amount_overdue_loans = Loan::where('status', 'overdue')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');
        $total_amount_defaulted_loans = Loan::where('status', 'defaulted')->whereBetween('updated_at', [$start_date, $end_date])->sum('amount');



    }


        $data = [
            'total_users' => $total_users,
            'total_customers' => $total_customers,
            'total_loans' => $total_loans,
            'total_pending_loans' => $total_pending_loans,
            'total_approved_loans' => $total_approved_loans,
            'total_rejected_loans' => $total_rejected_loans,
            'total_disbursed_loans' => $total_disbursed_loans,
            'total_active_loans' => $total_active_loans,
            'total_closed_loans' => $total_closed_loans,
            'total_written_off_loans' => $total_written_off_loans,
            'total_recovered_loans' => $total_recovered_loans,
            'total_overdue_loans' => $total_overdue_loans,
            'total_defaulted_loans' => $total_defaulted_loans,
            'total_amount_pending_loans' => $total_amount_pending_loans,
            'total_amount_approved_loans' => $total_amount_approved_loans,
            'total_amount_rejected_loans' => $total_amount_rejected_loans,
            'total_amount_disbursed_loans' => $total_amount_disbursed_loans,
            'total_amount_active_loans' => $total_amount_active_loans,
            'total_amount_closed_loans' => $total_amount_closed_loans,
            'total_amount_written_off_loans' => $total_amount_written_off_loans,
            'total_amount_recovered_loans' => $total_amount_recovered_loans,
            'total_amount_overdue_loans' => $total_amount_overdue_loans,
            'total_amount_defaulted_loans' => $total_amount_defaulted_loans,
            'expenditure' => $expenditure,
            'profit' => $profit,
            'total_amount_loans' => $total_amount_loans,
        ];

        return response()->json($data);

        // return $data;
    }


    public function getLoanData(){
        $user = Auth::user();
        // dd($user);
        $sortedData = []; //array to hold the sorted data


        $activeLoanData = [];
        $overDueLoanData = [];
        $pendingLoanData = [];
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
                          #  array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);
                          #array push By setting the key as the month and the loan count as the value
                            $sortedData[$monthh] = $monthlyLoansCount;


                            break;
                        }
                      
                    }
                }
                else{
                    $monthh = $month;
                    $type ='all';
                    $sortedData[$monthh] = 0;
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
                            #array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);
                            $activeLoanData[$monthh] = $monthlyLoansCount;

                            break;
                        }
                      
                    }
                }
                else{
                    $monthh = $month;
                    $type = 'active';
                    $activeLoanData[$monthh] = 0;
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
                            #array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);
                            $overDueLoanData[$monthh] = $monthlyLoansCount;

                            break;
                        }
                      
                    }
                }
                else{
                    $monthh = $month;
                    $type = 'overdue';
                    $overDueLoanData[$monthh] = 0;
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
                            #array_push($sortedData, ['month' => $monthh, 'loans' => $monthlyLoansCount, 'amount' => $monthlyLoansAmount, 'type' => $type]);
                            $pendingLoanData[$monthh] = $monthlyLoansCount;

                            break;
                        }
                      
                    }
                }
                else{
                    $monthh = $month;
                    $type = 'pending';
                    $pendingLoanData[$monthh] = 0;
                }

            }

        }
 

        $loanData =  $sortedData;
        $activeLoanData = Charts::getData($activeLoanData);
        $overDueLoanData = Charts::getData($overDueLoanData);
        $pendingLoanData = Charts::getData($pendingLoanData);
        $loanData = Charts::getData($loanData);

        

        #endregionreturn response()->json($loanData);
        // return loandata, activeLoanData, overDueLoanData, pendingLoanDataa as an array



        return response()->json(['loans' => $loanData, 'activeLoans' => $activeLoanData, 'overDueLoans' => $overDueLoanData, 'pendingLoans' => $pendingLoanData]);


       



        // return response()->json([
        //     'loans' => $loanData,
        //     'activeLoans' => $activeLoanData,
        //     'overDueLoans' => $overDueLoanData,
        //     'pendingLoans' => $pendingLoanData,
        // ], 200);

    
    }

    //get transactions and disbursements
    public function getTransactionsAndDisbursements(){
        $user = Auth::user();
        // $filter = $request->filter;
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
        $transactions = [];
        $disbursements = [];

        $transactionsByMonth = Transaction::all()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('M'); // grouping by months
        });

        $disbursementsByMonth =  Disburse::all()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('M'); // grouping by months
        });

        // get the current month
        foreach($months as $month){
            // transactions by month
            if(array_key_exists($month, $transactionsByMonth->toArray())){
                foreach($transactionsByMonth as $transaction => $value){
                    if($transaction == $month){
                        $monthlyUniqueTransactions = $value;
                        $monthlyTransactionsCount = count($monthlyUniqueTransactions->toArray());
                        $monthlyTransactionsAmount = $monthlyUniqueTransactions->pluck('amount')->sum();
                        $monthh = $month;
                        // $type = 'all';

                        $transactions[$monthh] = [$monthlyTransactionsAmount];
                       
                        // array_push($sortedData, ['month' => $monthh, 'transactions' => $monthlyTransactionsCount, 'amount' => $monthlyTransactionsAmount, 'type' => $type]);

                        break;
                    }
                  
                }
            }
            else{
                $monthh = $month;
                // $type = 'all';
                $transactions[$monthh] = 0;
            }

            // disbursements by month
            if(array_key_exists($month, $disbursementsByMonth->toArray())){
                foreach($disbursementsByMonth as $disbursement => $value){
                    if($disbursement == $month){
                        $monthlyUniqueDisbursements = $value;
                        $monthlyDisbursementsCount = count($monthlyUniqueDisbursements->toArray());
                        $monthlyDisbursementsAmount = $monthlyUniqueDisbursements->pluck('disbursement_amount')->sum();
                        $monthh = $month;

                        $disbursements[$monthh] = [$monthlyDisbursementsAmount];
                        
                        // array_push($sortedData, ['month' => $monthh, 'disbursements' => $monthlyDisbursementsCount, 'amount' => $monthlyDisbursementsAmount, 'type' => $type]);

                        break;
                    }
                  
                }
            }
            else{
                $monthh = $month;
                // $type = 'all';
                $disbursements[$monthh] = 0;
            }

        }
        $transactions = Charts::getData($transactions);
        $disbursements = Charts::getData($disbursements);
       // dd($transactions, $disbursements);

        return response()->json(['transactions' => $transactions, 'disbursements' => $disbursements], 200);

    }

    //filter performance of users, the filtration is done by user selecting a dropdown option
    //in the frontend, the option selected is passed to the backend as a parameter
    //the parameter is then used to filter the data
    //the parameter are this week, this month, 3 months, 6 months and 1 year
    public function filterPerformanceOfUsers(Request $request){
        $user = Auth::user();
        $users = User::where(function($query){$query->where('role_id', 2)->orWhere('role_id', 4);})->get();
        
        $userPerformance = [];
        $userPerformanceData = [];
        $filter = $request->filter;

        //get the current date
        $current = Carbon::now();
        $currentDate = $current->toDateString();
        $currentMonth = $current->month;
        $currentYear = $current->year;

        //get the first day of the current month
        $firstDayOfCurrentMonth = Carbon::now()->startOfMonth()->toDateString();

        //get the first day of the current year
        $firstDayOfCurrentYear = Carbon::now()->startOfYear()->toDateString();

        //get the first day of the current week
        $firstDayOfCurrentWeek = Carbon::now()->startOfWeek()->toDateString();

        //get the first day of the current quarter
        $firstDayOfCurrentQuarter = Carbon::now()->startOfQuarter()->toDateString();

        //get the first day of the current semester
        $firstDayOfCurrentSemester = Carbon::now()->startOfSemester()->toDateString();

        //check if the filter is this week
        if($filter == 'this week'){
            //get the users performance for this week and add it to the userPerformance array
            foreach($users as $user){
                //check if the user has a loan
                if($user->loans->count() > 0){
                    //get the user's loans
                    $loans = $user->loans;
                    //get the user's loans for this week
                    $loansForThisWeek = $loans->where('created_at', '>=', $firstDayOfCurrentWeek);
                    //get the user's loans for this week that are either active, paid or overdue
                    $loansForThisWeek = $loansForThisWeek->where('status', 'active')->orWhere('status', 'paid')->orWhere('status', 'overdue');

                    //get the user's loans for this week that are active and add it to the userPerformance array
                    if($loansForThisWeek->count() > 0){
                        // performance is $total_processing_fee + $total_loan_interest / $total_total_payable * 100
                        $total_processing_fee = $loansForThisWeek->pluck('processing_fee')->sum();
                        $total_loan_interest = $loansForThisWeek->pluck('loan_interest')->sum();
                        $total_total_payable = $loansForThisWeek->pluck('total_payable')->sum();
                        $performance = ($total_processing_fee + $total_loan_interest) / $total_total_payable * 100;
                        $userPerformance[$user->name] = $performance;

                    }               
                }
            }
        }
        else if($filter == 'this month'){
            //get the users performance for this month and add it to the userPerformance array
            foreach($users as $user){
                //check if the user has a loan
                if($user->loans->count() > 0){
                    //get the user's loans
                    $loans = $user->loans;
                    //get the user's loans for this month
                    $loansForThisMonth = $loans->where('created_at', '>=', $firstDayOfCurrentMonth);
                    //get the user's loans for this month that are either active, paid or overdue
                    $loansForThisMonth = $loansForThisMonth->where('status', 'active')->orWhere('status', 'paid')->orWhere('status', 'overdue');

                    //get the user's loans for this month that are active and add it to the userPerformance array
                    if($loansForThisMonth->count() > 0){
                        // performance is $total_processing_fee + $total_loan_interest / $total_total_payable * 100
                        $total_processing_fee = $loansForThisMonth->pluck('processing_fee')->sum();
                        $total_loan_interest = $loansForThisMonth->pluck('loan_interest')->sum();
                        $total_total_payable = $loansForThisMonth->pluck('total_payable')->sum();
                        $performance = ($total_processing_fee + $total_loan_interest) / $total_total_payable * 100;
                        $userPerformance[$user->name] = $performance;

                    }
                }
            }
        }
        else if($filter == '3 months'){
            //get the users performance for this month and add it to the userPerformance array
            foreach($users as $user){
                //check if the user has a loan
                if($user->loans->count() > 0){
                    //get the user's loans
                    $loans = $user->loans;
                    //get the user's loans for this quarter
                    $loansForThisQuarter = $loans->where('created_at', '>=', $firstDayOfCurrentQuarter);
                    //get the user's loans for this quarter that are either active, paid or overdue
                    $loansForThisQuarter = $loansForThisQuarter->where('status', 'active')->orWhere('status', 'paid')->orWhere('status', 'overdue');

                    //get the user's loans for this quarter that are active and add it to the userPerformance array
                    if($loansForThisQuarter->count() > 0){
                        // performance is $total_processing_fee + $total_loan_interest / $total_total_payable * 100
                        $total_processing_fee = $loansForThisQuarter->pluck('processing_fee')->sum();
                        $total_loan_interest = $loansForThisQuarter->pluck('loan_interest')->sum();
                        $total_total_payable = $loansForThisQuarter->pluck('total_payable')->sum();
                        $performance = ($total_processing_fee + $total_loan_interest) / $total_total_payable * 100;
                        $userPerformance[$user->name] = $performance;

                    }

                }
            }
        }
        
                    






    }

    

 

}
