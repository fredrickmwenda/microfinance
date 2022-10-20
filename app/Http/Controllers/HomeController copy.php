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

         if (Auth()->user()->hasRole('RO')) {

            //customers created by this user
            $total_customers = customer::where('created_by', Auth()->user()->id)->count();
            //get the loans of customers be
            $total_loans = Loan::where('created_by', Auth()->user()->id)->count();
            $total_amount_pending_loans = Loan::where('loan_status', 'pending')->where('created_by', Auth()->user()->id)->sum('loan_amount');
            $total_amount_approved_loans = Loan::where('loan_status', 'approved')->where('created_by', Auth()->user()->id)->sum('loan_amount');
            
            $total_amount_rejected_loans = Loan::where('loan_status', 'rejected')->where('created_by', Auth()->user()->id)->sum('loan_amount');
            $total__amount_disbursed_loans = Loan::where('loan_status', 'disbursed')->where('created_by', Auth()->user()->id)->sum('loan_amount');
            $total_amount_closed_loans = Loan::where('loan_status', 'closed')->where('created_by', Auth()->user()->id)->sum('total_payable');
            $total_amount_written_off_loans = Loan::where('loan_status', 'written_off')->where('created_by', Auth()->user()->id)->sum('total_payable');
            $total_amount_recovered_loans = Loan::where('loan_status', 'recovered')->where('created_by', Auth()->user()->id)->sum('total_payable');
            $total_amount_overdue_loans = Loan::where('loan_status', 'overdue')->where('created_by', Auth()->user()->id)->sum('total_payable');
            $total_amount_defaulted_loans = Loan::where('loan_status', 'defaulted')->where('created_by', Auth()->user()->id)->sum('total_payable');
        }
        else{
        $total_users = User::count();
         //pending loans
        $total_customers = customer::count();
        //total applied loans
        $total_loans = Loan::count();

        //loans
        //paginate Loans where status is pending, while order by created_at desc
        $all_pending_loans = Loan::where('loan_status', 'pending')->orderBy('created_at', 'desc')->paginate(10);
        $all_rejected_loans = Loan::where('loan_status', 'rejected')->orderBy('created_at', 'desc')->paginate(10);
        $all_approved_loans = Loan::where('loan_status', 'approved')->orderBy('created_at', 'desc')->paginate(10);
        $all_disbursed_loans = Loan::where('loan_status', 'disbursed')->orderBy('created_at', 'desc')->paginate(10);
        $all_closed_loans = Loan::where('loan_status', 'closed')->orderBy('created_at', 'desc')->paginate(10);
        $all_written_off_loans = Loan::where('loan_status', 'written_off')->orderBy('created_at', 'desc')->paginate(10);
        $all_recovered_loans = Loan::where('loan_status', 'recovered')->orderBy('created_at', 'desc')->paginate(10);
        $all_overdue_loans = Loan::where('loan_status', 'overdue')->orderBy('created_at', 'desc')->paginate(10);
        $all_defaulted_loans = Loan::where('loan_status', 'defaulted')->orderBy('created_at', 'desc')->paginate(10);

        //get Total interest earned from closed loans
        $total_interest_earned = Loan::where('loan_status', 'closed')->sum('loan_interest');
        $profit = $total_interest_earned;

        //total disbursed amount
        $total_disbursed_amount = Disburse::sum('transaction_amount');
        $expenditure = $total_disbursed_amount;
        


        $total_disbursed = Disburse::count();



        
        $total_pending_loans = Loan::where('loan_status', 'pending')->count();
        $total_approved_loans = Loan::where('loan_status', 'approved')->count();

    
        
        $total_rejected_loans = Loan::where('loan_status', 'rejected')->count();
        $total_disbursed_loans = Loan::where('loan_status', 'disbursed')->count();
        $total_closed_loans = Loan::where('loan_status', 'closed')->count();
        $total_written_off_loans = Loan::where('loan_status', 'written_off')->count();
        $total_recovered_loans = Loan::where('loan_status', 'recovered')->count();
        $total_overdue_loans = Loan::where('loan_status', 'overdue')->count();
        $total_defaulted_loans = Loan::where('loan_status', 'defaulted')->count();


        $total_amount_loans = Loan::sum('loan_amount');
        $total_amount_pending_loans = Loan::where('loan_status', 'pending')->sum('loan_amount');
        $total_amount_approved_loans = Loan::where('loan_status', 'approved')->sum('loan_amount');
        $total_amount_rejected_loans = Loan::where('loan_status', 'rejected')->sum('loan_amount');
        $total_amount_disbursed_loans = Loan::where('loan_status', 'disbursed')->sum('loan_amount');
        $total_amount_closed_loans = Loan::where('loan_status', 'closed')->sum('total_payable');
        $total_amount_recovered_loans = Loan::where('loan_status', 'recovered')->sum('loan_amount');
        $total_amount_written_off_loans = Loan::where('loan_status', 'written_off')->sum('loan_amount');
        $total_amount_overdue_loans = Loan::where('loan_status', 'overdue')->sum('loan_amount');
        $total_amount_defaulted_loans = Loan::where('loan_status', 'defaulted')->sum('loan_amount');

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
        // $loans = Loan::where('created_by', 7)->whereIn('loan_status', ['approved', 'disbursed', 'closed', 'written_off'])->whereMonth('created_at', 9)->get();
        // dd($loans);
        
        foreach($ro_officers as $ro_officer){

            //check if ro_officer has created any loan
            $loans = Loan::where('created_by', $ro_officer->id)->whereIn('loan_status', ['approved', 'disbursed', 'closed', 'written_off'])->get();
            if(count($loans) > 0){
                // $performance = [];
                // foreach($months as $month){
                //     $loans = Loan::where('created_by', $ro_officer->id)->whereIn('loan_status', ['approved', 'disbursed', 'closed', 'written_off'])->whereMonth('created_at', $month)->get();
                //     $performance[] = count($loans);
                // }
                // $ro_officers_performance[$ro_officer->id] = $performance;
                $loans = Loan::where('created_by', $ro_officer->id)->whereIn('loan_status', ['approved', 'disbursed', 'closed', 'written_off'])->get()->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });
            
            foreach($months as $month){
                //get the loans created by the ro officer in the month
                if(array_key_exists($month, $loans)){
                    // $performance[] = count($loans[$month]);
                    //get total_processing fee for the loans created by the ro officer in the month
                    $total_processing_fee = 0;
                    $total_interest = $loans->sum('loan_interest');
                    $total_payable = $loans->sum('total_payable');
                    foreach($loans[$month] as $loan){
                        $total_processing_fee += $loan->processing_fee;
                    }
                    $performance[] = [
                        'month' => $month,
                        'loans' => count($loans[$month]),
                        'processing_fee' => $total_processing_fee,
                        'interest' => $total_interest,
                        'total_payable' => $total_payable
                    ];
               
                // // $total_loans = $loans->count();
                // $total_processing_fee = $loans->sum('processing_fee');
                // $total_interest = $loans->sum('loan_interest');
                // $total_payable = $loans->sum('total_payable');
                // if ($total_loans > 0){
                //     $overall_performance = ($total_processing_fee + $total_interest) / $total_loans;
                // }
                // else{
                //     $overall_performance = 0;
                // }
               

                // //percentage of overall performance
                // $percentage = $overall_performance / 100;

                // //push the performance to the performance array, with also the name of the user
                // array_push($performance, [
                //     'name' => $ro_officer->first_name,
                //     'month' => $month,
                //     // 'total_loans' => $total_loans,
                //     'processing_fee' => $total_processing_fee,
                //     'interest' => $total_interest,
                //     'total_payable' => $total_payable,
                //     'overall_performance' => $overall_performance,
                //     'percentage' => $percentage
                // ]);
                




                //performance is the number of loans created by the ro officer which are approved, disbursed, closed or written off +
                // $performance[$month] = Loan::where('created_by', $ro_officer->id)->whereMonth('created_at', array_search($month, $months) + 1)->whereIn('loan_status', ['approved', 'disbursed', 'closed', 'written_off'])->count();
            }
            
        }
        $ro_officers_performance[$ro_officer->id] = $performance;
        }
        




        

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



 


        }

         return view('dashboard',compact('total_users','total_customers','all_pending_loans','all_approved_loans','all_rejected_loans','all_disbursed_loans','all_closed_loans','all_written_off_loans','all_recovered_loans','all_overdue_loans','all_defaulted_loans','total_loans','total_pending_loans','total_approved_loans','total_rejected_loans','total_disbursed_loans','total_closed_loans','total_written_off_loans','total_recovered_loans','total_overdue_loans','total_defaulted_loans','total_amount_pending_loans','total_amount_approved_loans','total_amount_rejected_loans','total_amount_closed_loans','total_interest_earned','profit','total_amount_pending_loans','total_amount_approved_loans','total_amount_rejected_loans','total_amount_disbursed_loans','total_amount_closed_loans','total_amount_written_off_loans','total_amount_recovered_loans','total_amount_overdue_loans','total_amount_defaulted_loans', 'expenditure', 'profit', 'total_loans', 'total_disbursed', 'total_disbursed_amount', 'total_amount_loans', 'loanData', 'activeLoanData', 'pendingLoanData', 'overDueLoanData','transactionData', 'disbursementData' ));
    }


  //function to get DashboardStatistics that passes start and end date to the function, returns the statistics for the period
    public function getDashboardStatistics($start_date, $end_date){
        // dd($start_date, $end_date);
        $total_users = User::whereBetween('created_at', [$start_date, $end_date])->count();
        $total_customers = Customer::whereBetween('created_at', [$start_date, $end_date])->count();
        $total_loans = Loan::whereBetween('created_at', [$start_date, $end_date])->count();
        $total_pending_loans = Loan::where('loan_status', 'pending')->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_approved_loans = Loan::where('loan_status', 'approved')->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_rejected_loans = Loan::where('loan_status', 'rejected')->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_disbursed_loans = Loan::where('loan_status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_closed_loans = Loan::where('loan_status', 'closed')->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_written_off_loans = Loan::where('loan_status', 'written_off')->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_recovered_loans = Loan::where('loan_status', 'recovered')->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_overdue_loans = Loan::where('loan_status', 'overdue')->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_defaulted_loans = Loan::where('loan_status', 'defaulted')->whereBetween('created_at', [$start_date, $end_date])->count();


        $total_amount_loans = Loan::whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_pending_loans = Loan::where('loan_status', 'pending')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_approved_loans = Loan::where('loan_status', 'approved')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_rejected_loans = Loan::where('loan_status', 'rejected')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_disbursed_loans = Loan::where('loan_status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_closed_loans = Loan::where('loan_status', 'closed')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_written_off_loans = Loan::where('loan_status', 'written_off')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_recovered_loans = Loan::where('loan_status', 'recovered')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_overdue_loans = Loan::where('loan_status', 'overdue')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_defaulted_loans = Loan::where('loan_status', 'defaulted')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');

        //performance of the loan officers, according to the loans they have created
        $loan_officers = User::where('role', 'loan_officer')->get();
        $loan_officers_performance = [];
        foreach($loan_officers as $loan_officer){
            $loan_officers_performance[] = [
                'name' => $loan_officer->name,
                'total_loans' => Loan::where('loan_officer_id', $loan_officer->id)->whereBetween('created_at', [$start_date, $end_date])->count(),
                'total_amount' => Loan::where('loan_officer_id', $loan_officer->id)->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount'),
            ];
            
        }

        //get Total disbursed loans
        $expenditure = Disburse::whereBetween('created_at', [$start_date, $end_date])->sum('transaction_amount');

        //get Total interest earned from closed loans
        $profit = Loan::where('loan_status', 'closed')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_interest');


        $data = [
            'total_users' => $total_users,
            'total_customers' => $total_customers,
            'total_loans' => $total_loans,
            'total_pending_loans' => $total_pending_loans,
            'total_approved_loans' => $total_approved_loans,
            'total_rejected_loans' => $total_rejected_loans,
            'total_disbursed_loans' => $total_disbursed_loans,
            'total_closed_loans' => $total_closed_loans,
            'total_written_off_loans' => $total_written_off_loans,
            'total_recovered_loans' => $total_recovered_loans,
            'total_overdue_loans' => $total_overdue_loans,
            'total_defaulted_loans' => $total_defaulted_loans,
            'total_amount_pending_loans' => $total_amount_pending_loans,
            'total_amount_approved_loans' => $total_amount_approved_loans,
            'total_amount_rejected_loans' => $total_amount_rejected_loans,
            'total_amount_disbursed_loans' => $total_amount_disbursed_loans,
            'total_amount_closed_loans' => $total_amount_closed_loans,
            'total_amount_written_off_loans' => $total_amount_written_off_loans,
            'total_amount_recovered_loans' => $total_amount_recovered_loans,
            'total_amount_overdue_loans' => $total_amount_overdue_loans,
            'total_amount_defaulted_loans' => $total_amount_defaulted_loans,
            'loan_officers_performance' => $loan_officers_performance,
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
            $activeLoansByMonth = Loan::where('loan_status', 'active')->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('M'); // grouping by months
            });
            

            $overDueLoansByMonth = Loan::where('loan_status', 'overdue')->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('M'); // grouping by months
            });
            
            // pending loans
            $pendingLoansByMonth = Loan::where('loan_status', 'pending')->get()->groupBy(function($date) {
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
            //         $activeLoans = Loan::where('loan_status', 'active')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $overDueLoans = Loan::where('loan_status', 'overdue')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $pendingLoans = Loan::where('loan_status', 'pending')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //     }

            //     // check if the filter is 6 months
            //     if($filter == '6months'){
            //         // get the current month
            //         $currentMonth = Carbon::now()->format('F');
            //         // get the previous 6 months
            //         $previousMonths = Carbon::now()->subMonths(6)->format('F');

            //         $loans = Loan::whereBetween('created_at', [$previousMonths, $currentMonth])->get();
            //         $activeLoans = Loan::where('loan_status', 'active')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $overDueLoans = Loan::where('loan_status', 'overdue')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $pendingLoans = Loan::where('loan_status', 'pending')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //     }

            //     // check if the filter is 1 year
            //     if($filter == '1year'){
            //         // get the current month
            //         $currentMonth = Carbon::now()->format('F');
            //         // get the previous 1 year
            //         $previousMonths = Carbon::now()->subMonths(12)->format('F');

            //         $loans = Loan::whereBetween('created_at', [$previousMonths, $currentMonth])->get();
            //         $activeLoans = Loan::where('loan_status', 'active')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $overDueLoans = Loan::where('loan_status', 'overdue')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //         $pendingLoans = Loan::where('loan_status', 'pending')->whereBetween('updated_at', [$previousMonths, $currentMonth])->get();
            //     }
            // }


        }
        else if($user->role_id == 3){
            $loansByMonth = Loan::where('created_by', $user->id)->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('F'); // grouping by months
            });

            $activeLoansByMonth = Loan::where('loan_status', 'active')->where('created_by', $user->id)->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('F'); // grouping by months
            });

            $overDueLoansByMonth = Loan::where('loan_status', 'overdue')->where('created_by', $user->id)->get()->groupBy(function($date) {
                return Carbon::parse($date->updated_at)->format('F'); // grouping by months
            });

            $pendingLoansByMonth = Loan::where('loan_status', 'pending')->where('created_by', $user->id)->get()->groupBy(function($date) {
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
                        $monthlyTransactionsAmount = $monthlyUniqueTransactions->pluck('transaction_amount')->sum();
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
        //get users with loans, where the loan_status is active, disburded , completed 
        $users = User::with('loans')->whereHas('loans', function($query){
            $query->where('loan_status', 'active')
            ->orWhere('loan_status', 'disbursed')
            ->orWhere('loan_status', 'completed');
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
