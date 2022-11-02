<?php

namespace App\Http\Controllers;

use App\Helpers\Charts;
use App\Models\customer;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class ROController extends Controller
{
    
    public function index(){
    //     if (!Auth()->user()->can('ro-officer.dashboard')) {
    //         return abort(401);
    //    } 

    // get all users that belong to RO role
    // $role = Role::with('users')->where('name', 'RO')->first();
    // dd($role);
    // $users = $role->users()->get();
    // dd($users);

       
        //total customers created, total_loans,
        $total_customers = customer::where('created_by', Auth()->user()->id)->count();
        
        $total_loans = Loan::where('created_by', Auth()->user()->id)->count();
        //Performance Rate of the RO is the total_processing_fee of loans created by the RO which have been been disbursed, half paid and closed + Number of clients created by the RO which have been been disbursed, half paid and closed + total_
        $total_processing_fee_ro = Loan::where('created_by', Auth()->user()->id)->where('status', 'disbursed')->orWhere('status', 'active')->orWhere('status', 'closed')->sum('processing_fee');
        $total_interest_ro = Loan::where('created_by', Auth()->user()->id)->where('status', 'disbursed')->orWhere('status', 'active')->orWhere('status', 'closed')->sum('interest');
        $total_clients_ro = customer::whereHas('loans', function($q){
            $q->where('created_by', Auth()->user()->id)->where('status', 'disbursed')->orWhere('status', 'active')->orWhere('status', 'closed');
        })->count();

        
        if($total_processing_fee_ro == 0 && $total_interest_ro == 0 && $total_clients_ro == 0){
            $performance_rate_ro = 0;
        }
        else{
            $performance_rate_ro = ($total_processing_fee_ro + $total_interest_ro)* $total_clients_ro /  100;
           
        }

        // $performance_rate_ro = ($total_processing_fee_ro + $total_interest_ro) / $total_clients_ro;



 
       
       $all_pending_loans = Loan::where('status', 'pending')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();  
       $all_approved_loans = Loan::where('status', 'approved')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_disbursed_loans = Loan::where('status', 'disbursed')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_closed_loans = Loan::where('status', 'closed')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_recovered_loans = Loan::where('status', 'recovered')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_overdue_loans = Loan::where('status', 'overdue')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_defaulted_loans = Loan::where('status', 'defaulted')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();


       $total_pending_loans = Loan::where('status', 'pending')->where('created_by', Auth()->user()->id)->count();
       $total_approved_loans = Loan::where('status', 'approved')->where('created_by', Auth()->user()->id)->count();
       $total_rejected_loans = Loan::where('status', 'rejected')->where('created_by', Auth()->user()->id)->count();
       $total_disbursed_loans = Loan::where('status', 'disbursed')->where('created_by', Auth()->user()->id)->count();
       $total_closed_loans = Loan::where('status', 'closed')->where('created_by', Auth()->user()->id)->count();
       $total_written_off_loans = Loan::where('status', 'written_off')->where('created_by', Auth()->user()->id)->count();
       $total_recovered_loans = Loan::where('status', 'recovered')->where('created_by', Auth()->user()->id)->count();
       $total_overdue_loans = Loan::where('status', 'overdue')->where('created_by', Auth()->user()->id)->count();
       $total_defaulted_loans = Loan::where('status', 'defaulted')->where('created_by', Auth()->user()->id)->count();
       $total_amount_loans = Loan::where('created_by', Auth()->user()->id)->sum('amount');
       $total_amount_pending_loans = Loan::where('status', 'pending')->where('created_by', Auth()->user()->id)->sum('amount');
       $total_amount_approved_loans = Loan::where('status', 'approved')->where('created_by', Auth()->user()->id)->sum('amount');
       $total_amount_rejected_loans = Loan::where('status', 'rejected')->where('created_by', Auth()->user()->id)->sum('amount');
       $total_amount_disbursed_loans = Loan::where('status', 'disbursed')->where('created_by', Auth()->user()->id)->sum('amount');
       $total_amount_closed_loans = Loan::where('status', 'closed')->where('created_by', Auth()->user()->id)->sum('total_payable');
       $total_amount_recovered_loans = Loan::where('status', 'recovered')->where('created_by', Auth()->user()->id)->sum('amount');
       $total_amount_written_off_loans = Loan::where('status', 'written_off')->where('created_by', Auth()->user()->id)->sum('amount');
       $total_amount_overdue_loans = Loan::where('status', 'overdue')->where('created_by', Auth()->user()->id)->sum('amount');
       $total_amount_defaulted_loans = Loan::where('status', 'defaulted')->where('created_by', Auth()->user()->id)->sum('amount');

       $loanData   = $this->getLoanData();
       $loanData = json_encode($loanData);
       $loanSet = json_decode($loanData); 
       $loanData = $loanSet->original->loans;
       //convert to json
       $loanData = json_encode($loanData);
       $activeLoanData = $loanSet->original->activeLoans;
       $overDueLoanData = $loanSet->original->overDueLoans;
       $activeLoanData = json_encode($activeLoanData);
       $overDueLoanData = json_encode($overDueLoanData);

       $ROPerformanceData = $this->getPerformanceOfUser();
        $ROPerformanceSet = json_encode($ROPerformanceData);
        $ROPerformanceData  = json_decode($ROPerformanceSet);
        $ROPerformanceData = $ROPerformanceData->original;
        $ROPerformanceData = json_encode($ROPerformanceData);
        // dd($ROPerformanceData, $loanData);


       return view('ro/dashboard', compact('total_customers', 'total_loans', 'total_amount_loans', 'performance_rate_ro', 'all_approved_loans', 'all_disbursed_loans', 'all_closed_loans', 'all_recovered_loans', 
       'all_overdue_loans', 'all_defaulted_loans', 'total_pending_loans', 'total_approved_loans', 'total_rejected_loans', 'total_disbursed_loans', 'total_closed_loans', 'total_written_off_loans', 'total_recovered_loans', 'total_overdue_loans', 'total_defaulted_loans', 'total_amount_pending_loans', 'total_amount_approved_loans', 'total_amount_rejected_loans', 'total_amount_disbursed_loans', 'total_amount_closed_loans', 'total_amount_recovered_loans', 'total_amount_written_off_loans', 'total_amount_overdue_loans', 'total_amount_defaulted_loans', 'all_pending_loans', 'loanData', 'activeLoanData', 'overDueLoanData', 'ROPerformanceData'));
    }

    public function getDashboardStatistics($start_date, $end_date){
        $total_customers = Customer::where('created_by', Auth()->user()->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_loans = Loan::where('created_by', Auth()->user()->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_disbursed = Loan::where('created_by', Auth()->user()->id)->where('status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->count();

        $total_processing_fee = Loan::where('created_by', Auth()->user()->id)->where('status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->sum('processing_fee');
        $total_interest_earned = Loan::where('created_by', Auth()->user()->id)->where('status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->sum('interest');
        $total_clients_ro = customer::whereHas('loans', function($q){
            $q->where('created_by', Auth()->user()->id)->where('status', 'disbursed')->orWhere('status', 'active')->orWhere('status', 'closed');
        })->whereBetween('created_at', [$start_date, $end_date])->count();

        if($total_processing_fee == 0 && $total_interest_earned == 0 && $total_clients_ro == 0){
            $performance_rate_ro = 0;
        }
        else{
            $performance_rate_ro = ($total_processing_fee + $total_interest_earned )* $total_clients_ro /  100;
        }

        // $performance_rate_ro  = ($total_processing_fee + $total_interest_earned / $total_clients_ro) * 100;

        $all_pending_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'pending')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_approved_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'approved')->whereBetween('created_at', [$start_date, $end_date])->count();   
        $all_rejected_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'rejected')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_disbursed_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_closed_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'closed')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_written_off_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'written_off')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_recovered_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'recovered')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_overdue_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'overdue')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_defaulted_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'defaulted')->whereBetween('created_at', [$start_date, $end_date])->count();


        $total_amount_loans = Loan::where('created_by', Auth()->user()->id)->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        $total_amount_pending_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'pending')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        $total_amount_approved_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'approved')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');   
        $total_amount_rejected_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'rejected')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        $total_amount_disbursed_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        $total_amount_closed_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'closed')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        $total_amount_written_off_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'written_off')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        $total_amount_recovered_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'recovered')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        $total_amount_overdue_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'overdue')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');
        $total_amount_defaulted_loans = Loan::where('created_by', Auth()->user()->id)->where('status', 'defaulted')->whereBetween('created_at', [$start_date, $end_date])->sum('amount');


        $data = [
            'total_customers' => $total_customers,
            'total_loans' => $total_loans,
            'ro_performance' => $performance_rate_ro,
            'total_pending_loans' => $all_pending_loans,
            'total_approved_loans' => $all_approved_loans,
            'total_rejected_loans' => $all_rejected_loans,
            'total_disbursed_loans' => $all_disbursed_loans,
            'total_closed_loans' => $all_closed_loans,
            'total_written_off_loans' => $all_written_off_loans,
            'total_recovered_loans' => $all_recovered_loans,
            'total_overdue_loans' => $all_overdue_loans,
            'total_defaulted_loans' => $all_defaulted_loans,
            'total_amount_pending_loans' => $total_amount_pending_loans,
            'total_amount_approved_loans' => $total_amount_approved_loans,
            'total_amount_rejected_loans' => $total_amount_rejected_loans,
            'total_amount_disbursed_loans' => $total_amount_disbursed_loans,
            'total_amount_closed_loans' => $total_amount_closed_loans,
            'total_amount_written_off_loans' => $total_amount_written_off_loans,
            'total_amount_recovered_loans' => $total_amount_recovered_loans,
            'total_amount_overdue_loans' => $total_amount_overdue_loans,
            'total_amount_defaulted_loans' => $total_amount_defaulted_loans,
            
            'total_amount_loans' => $total_amount_loans,
        ];

        return json_encode($data);



        

    }

    public function getLoanData(){
        $user = Auth()->user();
        // dd($user);
        $loanData = []; //array to hold the sorted data


        $activeLoanData = [];
        $overDueLoanData = [];
        $pendingLoanData = [];
        $months = ['Jan','Feb','Mar','Apr','May','Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ];

        $loansByMonth = Loan::where('created_by', $user->id)->get()->groupBy(function($date) {
            // grouping by months in short form
            return Carbon::parse($date->created_at)->format('M');
        });

        $activeLoansByMonth = Loan::where('status', 'active')->where('created_by', $user->id)->get()->groupBy(function($date) {
            return Carbon::parse($date->updated_at)->format('M'); // grouping by months
        });

        $overDueLoansByMonth = Loan::where('status', 'overdue')->where('created_by', $user->id)->get()->groupBy(function($date) {
            return Carbon::parse($date->updated_at)->format('M'); // grouping by months
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
                        $loanData[$monthh] = $monthlyLoansCount;

                        break;
                    }
                    
                }
            }
            else{
                $monthh = $month;
                $loanData[$monthh] = 0;
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
                        
                        $activeLoanData[$monthh] = $monthlyLoansCount;

                        break;
                    }
                    
                }
            }
            else{
                $monthh = $month;
                $activeLoanData[$monthh] = 0;
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
        
                        $overDueLoanData[$monthh] = $monthlyLoansCount;

                        break;
                    }

                }
            }
            else{
                $monthh = $month;
                $overDueLoanData[$monthh] = 0;
            }
        }

        $loanData = Charts::getData($loanData);
        $activeLoanData = Charts::getData($activeLoanData);
        $overDueLoanData = Charts::getData($overDueLoanData);

        
       

        return response()->json(['loans' => $loanData, 'activeLoans' => $activeLoanData, 'overDueLoans' => $overDueLoanData, 'pendingLoans' => $pendingLoanData]);    
    }




    // get performance of current user
    public function getPerformanceOfUser(){
        $user = Auth::user();
       
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $sortedData = [];
        //get  current user with loans, where the status is active, disburded , completed 
        // $userLoansByMonth  = Loan::where('created_by', $user->id)->where('status', 'active')->orWhere('status', 'disbursed')->orWhere('status', 'closed')->get()->groupBy(function($date) {
        //     // grouping by months in short form               
        //     return Carbon::parse($date->updated_at)->format('M'); 
        // });

        $userLoansByMonth  = Loan::where('created_by', $user->id)->where(function($query){
            $query->where('status', 'active')->orWhere('status', 'disbursed')->orWhere('status', 'closed');
        })->get()->groupBy(function($date) {
            // grouping by months in short form               
            return Carbon::parse($date->updated_at)->format('M'); 
        });

       

        // get the current month
        foreach($months as $month){
            // users by month
            if(array_key_exists($month, $userLoansByMonth->toArray())){
                foreach($userLoansByMonth as $user => $value){
                    if($user == $month){
                        $total_processing_fee = $value->pluck('processing_fee')->sum();
                        $total_interest = $value->pluck('interest')->sum();
                        // get the number of loans
                        $total_clients =  count($value->toArray());
                        $performance_rate = ($total_processing_fee + $total_interest)*$total_clients / 100;
                        $monthh = $month;
                        // array_push($sortedData, ['month' => $monthh, 'performance_rate' => $performance_rate, 'total_clients' => $total_clients, 'total_processing_fee' => $total_processing_fee, 'total_interest' => $total_interest]);
                        $sortedData[$monthh] = $performance_rate;
                        break;
                    }
                  
                }
            }
            else{
                $monthh = $month;
                $sortedData[$monthh] = 0;
            }
        }
        // dd($sortedData);

        $performanceData =  Charts::getData($sortedData);

        return response()->json($performanceData);
    }



}
