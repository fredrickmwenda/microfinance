<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\Loan;
use Illuminate\Http\Request;

class ROController extends Controller
{
    
    public function index(){
    //     if (!Auth()->user()->can('ro-officer.dashboard')) {
    //         return abort(401);
    //    } 
       
        //total customers created, total_loans,
        $total_customers = customer::where('created_by', Auth()->user()->id)->count();
        $total_loans = Loan::where('created_by', Auth()->user()->id)->count();
        //Performance Rate of the RO is the total_processing_fee of loans created by the RO which have been been disbursed, half paid and closed + Number of clients created by the RO which have been been disbursed, half paid and closed + total_
        $total_processing_fee_ro = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'disbursed')->orWhere('loan_status', 'half_paid')->orWhere('loan_status', 'closed')->sum('processing_fee');
        $total_interest_ro = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'disbursed')->orWhere('loan_status', 'half_paid')->orWhere('loan_status', 'closed')->sum('loan_interest');
        $total_clients_ro = customer::whereHas('loans', function($q){
            $q->where('created_by', Auth()->user()->id)->where('loan_status', 'disbursed')->orWhere('loan_status', 'half_paid')->orWhere('loan_status', 'closed');
        })->count();

        
        if($total_processing_fee_ro == 0 && $total_interest_ro == 0 && $total_clients_ro == 0){
            $performance_rate_ro = 0;
        }
        else{
            $performance_rate_ro = ($total_processing_fee_ro + $total_interest_ro) / $total_clients_ro * 100;
        }

        // $performance_rate_ro = ($total_processing_fee_ro + $total_interest_ro) / $total_clients_ro;



 
       
       $all_pending_loans = Loan::where('loan_status', 'pending')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();  
       $all_approved_loans = Loan::where('loan_status', 'approved')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_disbursed_loans = Loan::where('loan_status', 'disbursed')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_closed_loans = Loan::where('loan_status', 'closed')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_recovered_loans = Loan::where('loan_status', 'recovered')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_overdue_loans = Loan::where('loan_status', 'overdue')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();
       $all_defaulted_loans = Loan::where('loan_status', 'defaulted')->where('created_by', Auth()->user()->id)->orderBy('created_at', 'desc')->paginate();


       $total_pending_loans = Loan::where('loan_status', 'pending')->where('created_by', Auth()->user()->id)->count();
       $total_approved_loans = Loan::where('loan_status', 'approved')->where('created_by', Auth()->user()->id)->count();
       $total_rejected_loans = Loan::where('loan_status', 'rejected')->where('created_by', Auth()->user()->id)->count();
       $total_disbursed_loans = Loan::where('loan_status', 'disbursed')->where('created_by', Auth()->user()->id)->count();
       $total_closed_loans = Loan::where('loan_status', 'closed')->where('created_by', Auth()->user()->id)->count();
       $total_written_off_loans = Loan::where('loan_status', 'written_off')->where('created_by', Auth()->user()->id)->count();
       $total_recovered_loans = Loan::where('loan_status', 'recovered')->where('created_by', Auth()->user()->id)->count();
       $total_overdue_loans = Loan::where('loan_status', 'overdue')->where('created_by', Auth()->user()->id)->count();
       $total_defaulted_loans = Loan::where('loan_status', 'defaulted')->where('created_by', Auth()->user()->id)->count();
       $total_amount_loans = Loan::where('created_by', Auth()->user()->id)->sum('loan_amount');
       $total_amount_pending_loans = Loan::where('loan_status', 'pending')->where('created_by', Auth()->user()->id)->sum('loan_amount');
       $total_amount_approved_loans = Loan::where('loan_status', 'approved')->where('created_by', Auth()->user()->id)->sum('loan_amount');
       $total_amount_rejected_loans = Loan::where('loan_status', 'rejected')->where('created_by', Auth()->user()->id)->sum('loan_amount');
       $total_amount_disbursed_loans = Loan::where('loan_status', 'disbursed')->where('created_by', Auth()->user()->id)->sum('loan_amount');
       $total_amount_closed_loans = Loan::where('loan_status', 'closed')->where('created_by', Auth()->user()->id)->sum('total_payable');
       $total_amount_recovered_loans = Loan::where('loan_status', 'recovered')->where('created_by', Auth()->user()->id)->sum('loan_amount');
       $total_amount_written_off_loans = Loan::where('loan_status', 'written_off')->where('created_by', Auth()->user()->id)->sum('loan_amount');
       $total_amount_overdue_loans = Loan::where('loan_status', 'overdue')->where('created_by', Auth()->user()->id)->sum('loan_amount');
       $total_amount_defaulted_loans = Loan::where('loan_status', 'defaulted')->where('created_by', Auth()->user()->id)->sum('loan_amount');

       return view('ro/dashboard', compact('total_customers', 'total_loans', 'total_amount_loans', 'performance_rate_ro', 'all_approved_loans', 'all_disbursed_loans', 'all_closed_loans', 'all_recovered_loans', 
       'all_overdue_loans', 'all_defaulted_loans', 'total_pending_loans', 'total_approved_loans', 'total_rejected_loans', 'total_disbursed_loans', 'total_closed_loans', 'total_written_off_loans', 'total_recovered_loans', 'total_overdue_loans', 'total_defaulted_loans', 'total_amount_pending_loans', 'total_amount_approved_loans', 'total_amount_rejected_loans', 'total_amount_disbursed_loans', 'total_amount_closed_loans', 'total_amount_recovered_loans', 'total_amount_written_off_loans', 'total_amount_overdue_loans', 'total_amount_defaulted_loans', 'all_pending_loans'));
    }

    public function getDashboardStatistics($start_date, $end_date){
        $total_customers = Customer::where('created_by', Auth()->user()->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_loans = Loan::where('created_by', Auth()->user()->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $total_disbursed = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->count();

        $total_processing_fee = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->sum('processing_fee');
        $total_interest_earned = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_interest');
        $total_clients_ro = customer::whereHas('loans', function($q){
            $q->where('created_by', Auth()->user()->id)->where('status', 'disbursed')->orWhere('status', 'half_paid')->orWhere('status', 'closed');
        })->whereBetween('created_at', [$start_date, $end_date])->count();

        if($total_processing_fee == 0 && $total_interest_earned == 0 && $total_clients_ro == 0){
            $performance_rate_ro = 0;
        }
        else{
            $performance_rate_ro = ($total_processing_fee + $total_interest_earned) / $total_clients_ro * 100;
        }

        // $performance_rate_ro  = ($total_processing_fee + $total_interest_earned / $total_clients_ro) * 100;

        $all_pending_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'pending')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_approved_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'approved')->whereBetween('created_at', [$start_date, $end_date])->count();   
        $all_rejected_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'rejected')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_disbursed_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_closed_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'closed')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_written_off_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'written_off')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_recovered_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'recovered')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_overdue_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'overdue')->whereBetween('created_at', [$start_date, $end_date])->count();
        $all_defaulted_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'defaulted')->whereBetween('created_at', [$start_date, $end_date])->count();


        $total_amount_loans = Loan::where('created_by', Auth()->user()->id)->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_pending_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'pending')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_approved_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'approved')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');   
        $total_amount_rejected_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'rejected')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_disbursed_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'disbursed')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_closed_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'closed')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_written_off_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'written_off')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_recovered_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'recovered')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_overdue_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'overdue')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');
        $total_amount_defaulted_loans = Loan::where('created_by', Auth()->user()->id)->where('loan_status', 'defaulted')->whereBetween('created_at', [$start_date, $end_date])->sum('loan_amount');


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
}
