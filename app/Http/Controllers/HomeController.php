<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\Disburse;
use App\Models\Loan;
use App\Models\User;
use App\Models\Transaction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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
        $total_interest_earned = Loan::where('status', 'closed')->sum('interest');
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
        $ro_officers = User::all();
        //dd($ro_officers);
        
        $months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        //per

        $ro_officers_performance = [];
        $performance = [];
        // loans for september
        // $loans = Loan::where('created_by', 7)->whereIn('status', ['approved', 'disbursed', 'closed', 'written_off'])->whereMonth('created_at', 9)->get();
        // dd($loans, $loans->count());
        
        foreach($ro_officers as $ro_officer){
            // $ro_officers_performance[$ro_officer->id] = [
            //     'name' => $ro_officer->name,
            //     'approved' => 0,
            //     'disbursed' => 0,
            //     'closed' => 0,
            //     'written_off' => 0,
            //     'total' => 0,
            // ];
            $loans = Loan::where('created_by', $ro_officer->id)->whereIn('status', ['approved', 'disbursed', 'closed', 'written_off'])->get();
           
            if(count($loans) > 0){
                
                $loansRO = Loan::where('created_by', $ro_officer->id)->whereIn('status', ['approved', 'disbursed', 'closed', 'written_off'])->get()->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('M'); // grouping by months
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
                                'ro_username' => $ro_username,
                                'month' => $month,
                                'total_loans' => $total_monthly_loans,
                                'total_processing_fee' => $total_processing_fee,
                                'total_loan_interest' => $total_loan_interest,
                                'total_total_payable' => $total_total_payable,
                                'performance' => $performance,
                                'performancePerc' => $performancePerc,
                            ]);

                        }
                    }


                //performance is the number of loans created by the ro officer which are approved, disbursed, closed or written off +
                // $performance[$month] = Loan::where('created_by', $ro_officer->id)->whereMonth('created_at', array_search($month, $months) + 1)->whereIn('status', ['approved', 'disbursed', 'closed', 'written_off'])->count();
            }
            
        }
    }


    $ROPerformanceData  = json_encode($ro_officers_performance);


  

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
        $transactionData = json_encode($transactionData);

        $transactionSet = json_decode($transactionData);
        $transactionData = $transactionSet->original->transactions;

        $transactionData = json_encode($transactionData);


        $disbursementData = $transactionSet->original->disbursements;
        $disbursementData = json_encode($disbursementData);

        // get loans created between 7 days ago and today
        $loansCreatedWithin7Days = Loan::where('created_at', '>=',  Carbon::now()->subDays(7))->get();
        // dd($loansCreatedWithin7Days);



 


        }

         return view('dashboard',compact('total_users','total_customers','all_pending_loans','all_approved_loans','all_rejected_loans','all_disbursed_loans','all_closed_loans','all_written_off_loans','all_recovered_loans','all_overdue_loans','all_defaulted_loans','total_loans','total_pending_loans','total_approved_loans','total_rejected_loans','total_disbursed_loans','total_closed_loans','total_written_off_loans','total_recovered_loans','total_overdue_loans','total_defaulted_loans','total_amount_pending_loans','total_amount_approved_loans','total_amount_rejected_loans','total_amount_closed_loans','total_interest_earned','profit','total_amount_pending_loans','total_amount_approved_loans','total_amount_rejected_loans','total_amount_disbursed_loans','total_amount_closed_loans','total_amount_written_off_loans','total_amount_recovered_loans','total_amount_overdue_loans','total_amount_defaulted_loans', 'expenditure', 'profit', 'total_loans', 'total_disbursed', 'total_disbursed_amount', 'total_amount_loans', 'loanData', 'activeLoanData', 'pendingLoanData', 'overDueLoanData','transactionData', 'disbursementData', 'ROPerformanceData' ));
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
            $profit = Loan::where('status', 'closed')->whereDate('updated_at', $start_date )->sum('loan_interest');

            
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
            $expenditure = Disburse::where('created_at', '>=', Carbon::parse($start_date)->startOfDay())->where('created_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('_amount');


            //get Total interest earned from closed loans
            $profit = Loan::where('status', 'closed')->where('updated_at', '>=', Carbon::parse($start_date)->startOfDay())->where('updated_at', '<=', Carbon::parse($end_date)->endOfDay())->sum('interest');

            
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
        $profit = Loan::where('status', 'closed')->whereBetween('updated_at', [$start_date, $end_date])->sum('interest');
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

        $loanData =  $sortedData;

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
        $sortedData = [];

        $transactionsByMonth = Transaction::all()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('F'); // grouping by months
        });

        $disbursementsByMonth =  Disburse::all()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('F'); // grouping by months
        });

        // get the current month
        foreach($months as $month){
            // transactions by month
            if(array_key_exists($month, $transactionsByMonth->toArray())){
                foreach($transactionsByMonth as $transaction => $value){
                    if($transaction == $month){
                        $monthlyUniqueTransactions = $value;
                        $monthlyTransactionsCount = count($monthlyUniqueTransactions->toArray());
                        $monthlyTransactionsAmount = $monthlyUniqueTransactions->pluck('disbursement_amount')->sum();
                        $monthh = $month;
                        // $type = 'all';

                        $transactions[$monthh] = [$monthlyTransactionsAmount];
                       
                        // array_push($sortedData, ['month' => $monthh, 'transactions' => $monthlyTransactionsCount, 'amount' => $monthlyTransactionsAmount, 'type' => $type]);

                        break;
                    }
                  
                }
            }

            // disbursements by month
            if(array_key_exists($month, $disbursementsByMonth->toArray())){
                foreach($disbursementsByMonth as $disbursement => $value){
                    if($disbursement == $month){
                        $monthlyUniqueDisbursements = $value;
                        $monthlyDisbursementsCount = count($monthlyUniqueDisbursements->toArray());
                        $monthlyDisbursementsAmount = $monthlyUniqueDisbursements->pluck('amount')->sum();
                        $monthh = $month;

                        $disbursements[$monthh] = [$monthlyDisbursementsAmount];
                        
                        // array_push($sortedData, ['month' => $monthh, 'disbursements' => $monthlyDisbursementsCount, 'amount' => $monthlyDisbursementsAmount, 'type' => $type]);

                        break;
                    }
                  
                }
            }
        }

        return response()->json(['transactions' => $transactions, 'disbursements' => $disbursements], 200);

    }

    // get performance of users
    public function getPerformanceOfUsers(){
        $user = Auth::user();
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $sortedData = [];
        //get users with loans, where the status is active, disburded , completed 
        $users = User::with('loans')->whereHas('loans', function($query){
            $query->where('status', 'active')
            ->orWhere('status', 'disbursed')
            ->orWhere('status', 'completed');
        })->get();
        dd($users);
        $usersByMonth = $users->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('F'); // grouping by months
        });

        // get the current month
        foreach($months as $month){
            // users by month
            if(array_key_exists($month, $usersByMonth->toArray())){
                foreach($usersByMonth as $user => $value){
                    if($user == $month){
                        $monthlyUniqueUsers = $value;
                        $monthlyUsersCount = count($monthlyUniqueUsers->toArray());
                        $monthh = $month;
                        $type = 'all';

                        array_push($sortedData, ['month' => $monthh, 'users' => $monthlyUsersCount, 'type' => $type]);

                        break;
                    }
                  
                }
            }
        }

        return response()->json($sortedData, 200);
    }

}
