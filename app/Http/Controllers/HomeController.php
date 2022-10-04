<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\Disburse;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Datatables;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

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
        $ro_officers = User::where('role', 'ro')->get();
        $ro_officers_performance = [];
        foreach($ro_officers as $ro_officer){
            $ro_officers_performance[$ro_officer->id] = [
                'full_name' => $ro_officer->first_name.' '.$ro_officer->last_name,
                'approved_loans' => Loan::where('loan_status', 'approved')->where('created_by', $ro_officer->id)->count(),
                'disbursed_loans' => Loan::where('loan_status', 'disbursed')->where('created_by', $ro_officer->id)->count(),
                'closed_loans' => Loan::where('loan_status', 'closed')->where('created_by', $ro_officer->id)->count(),
                'written_off_loans' => Loan::where('loan_status', 'written_off')->where('created_by', $ro_officer->id)->count(),
            ];
        }

        // $start_date = date("Y").'-'.date("m").'-'.'01';
        // $end_date = date("Y").'-'.date("m").'-'.date('t', mktime(0, 0, 0, date("m"), 1, date("Y")));
        // $yearly_sale_amount = [];



        }


        
         

         return view('dashboard',compact('total_users','total_customers','all_pending_loans','all_approved_loans','all_rejected_loans','all_disbursed_loans','all_closed_loans','all_written_off_loans','all_recovered_loans','all_overdue_loans','all_defaulted_loans','total_loans','total_pending_loans','total_approved_loans','total_rejected_loans','total_disbursed_loans','total_closed_loans','total_written_off_loans','total_recovered_loans','total_overdue_loans','total_defaulted_loans','total_amount_pending_loans','total_amount_approved_loans','total_amount_rejected_loans','total_amount_closed_loans','total_interest_earned','profit','total_amount_pending_loans','total_amount_approved_loans','total_amount_rejected_loans','total_amount_disbursed_loans','total_amount_closed_loans','total_amount_written_off_loans','total_amount_recovered_loans','total_amount_overdue_loans','total_amount_defaulted_loans', 'expenditure', 'profit', 'total_loans', 'total_disbursed', 'total_disbursed_amount', 'total_amount_loans'));
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



    public function statics(){
        $total_users = User::count();
        $total_active_users = User::where('status', 1)->count();
        $total_inactive_users = User::where('status', 0)->count();
        $total_verified_users = User::where('email_verified_at', '!=', null)->count();
        $total_unverified_users = User::where('email_verified_at', null)->count();
        #customers
        $total_customers = customer::count();
        $total_active_customers = customer::where('status', 1)->count();
        $total_inactive_customers = customer::where('status', 0)->count();

        #how many customers has a user registered
        $total_customers_per_user = User::select('id', 'name', 'email', 'phone', 'created_at')->get();

        #rank customers by number of users that have registered them in the last 30 days, order by highest to lowest
        $total_customers_per_user_last_30_days = customer::select('id', 'name', 'email', 'phone', 'created_at')->where('created_at', '>=', now()->subDays(30))->get();
        #count the number of users that have registered a customer in the last 30 days
        $total_users_per_customer_last_30_days = User::select('id', 'name', 'email', 'phone', 'created_at')->where('created_at', '>=', now()->subDays(30))->get();
        // order users that created customers from the one with the highest number of customers created
        $total_users_per_customer_last_30_days =customer::with('user')->select('id', 'name', 'email', 'phone', 'created_at', 'created_by')->where('created_at', '>=', now()->subDays(30))->get();

        #rank total_users_per_customer_last_30_days by number of users that have registered them in the last 30 days, order by highest to lowest
        $rank = $total_users_per_customer_last_30_days->sortByDesc(function($user){
            return $user->user->count();
        });

        #plot a graph of the number of users "users table' that have registered a customer "customers_table" in the last 7 days, 30 days, and 365 days
        $users_per_customer_last_7_days = User::select('id', 'name', 'email', 'phone', 'created_at')->with('customer')->where('created_at', '>=', now()->subDays(7))->get();
        $users_per_customer_last_30_days = User::select('id', 'name', 'email', 'phone', 'created_at')->with('customer')->where('created_at', '>=', now()->subDays(30))->get();
        $users_per_customer_last_365_days = User::select('id', 'name', 'email', 'phone', 'created_at')->with('customer')->where('created_at', '>=', now()->subDays(365))->get();
        $users_per_customer_last_7_days = $users_per_customer_last_7_days->sortByDesc(function($user){
            return $user->customer->count();
        });
        $users_per_customer_last_30_days = $users_per_customer_last_30_days->sortByDesc(function($user){
            return $user->customer->count();
        });
        $users_per_customer_last_365_days = $users_per_customer_last_365_days->sortByDesc(function($user){
            return $user->customer->count();
        });
       $data = [
            'total_users' => $total_users,
            'total_active_users' => $total_active_users,
            'total_inactive_users' => $total_inactive_users,
            'total_verified_users' => $total_verified_users,
            'total_unverified_users' => $total_unverified_users,
            'total_customers' => $total_customers,
            'total_active_customers' => $total_active_customers,
            'total_inactive_customers' => $total_inactive_customers,
            'total_customers_per_user' => $total_customers_per_user,
            'total_customers_per_user_last_30_days' => $total_customers_per_user_last_30_days,
            'total_users_per_customer_last_30_days' => $total_users_per_customer_last_30_days,
            'rank' => $rank,
            'users_per_customer_last_7_days' => $users_per_customer_last_7_days,
            'users_per_customer_last_30_days' => $users_per_customer_last_30_days,
            'users_per_customer_last_365_days' => $users_per_customer_last_365_days,
        ];
        return json_encode($data);

    }

    public function getPendingLoans(Request $request){
        if ($request->ajax()) {
            //get 
            $data = Loan::where('loan_status', 'pending')->latest()->get();
            return FacadesDataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
  
    }

    //get closed loans
    public function getClosedLoans(Request $request){
        if ($request->ajax()) {
            //get 
            $data = Loan::where('loan_status', 'closed')->latest()->get();
            return FacadesDataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLoan">Edit</a>';
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLoan">Delete</a>';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
  
    }
}
