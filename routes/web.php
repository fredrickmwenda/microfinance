<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\HomeController;

//Branch controller
use App\Http\Controllers\BranchController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    //if the user is logged in, redirect to dashboard
    if (Auth::check()) {
        //get role id
        $role = Auth::user()->role_id;
        //if role is admin, redirect to admin dashboard
        if ($role == 1 || $role == 3 || $role == 4) {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 2 ) {
            return redirect()->route('agent.dashboard');
        }
    }
    return view('auth.login');
});

Auth::routes();

//protect the route with auth middleware
Route::group(['middleware' => ['auth']], function () {
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/admin/dashboard',  [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/agent/dashboard',  [App\Http\Controllers\ROController::class, 'index'])->name('agent.dashboard');
    Route::get('/user/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    
    Route::get('/admin/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('admin.logout');
    Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
 
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [App\Http\Controllers\AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [App\Http\Controllers\AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'update'])->name('admin.users.update');
    #destroy
    Route::get('/admin/users/{id}/destroy', [App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.user.destroy');
    Route::get('/admin/users/delete', [App\Http\Controllers\AdminController::class, 'delete_users'])->name('admin.users.delete');

    //getDashboardStatistics which accepts start date and end date as parameters to get the statistics
    Route::get('/dashboard-statistics/{start_date}/{end_date}', [App\Http\Controllers\HomeController::class, 'getDashboardStatistics'])->name('dashboard.data');

    //getDashboardStatics for ro dashboard
    Route::get('/ro-dashboard-statistics/{start_date}/{end_date}', [App\Http\Controllers\ROController::class, 'getDashboardStatistics'])->name('ro.dashboard.data');
    
    //Branch resource routes
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('branch', 'App\Http\Controllers\BranchController');
        //Roles
        Route::post('role/destroy', [App\Http\Controllers\RoleController::class, 'destroy'])->name('role.destroy');
        Route::get('role', [App\Http\Controllers\RoleController::class, 'index'])->name('role.index');
        Route::get('role/create', [App\Http\Controllers\RoleController::class, 'create'])->name('role.create');
        Route::post('role/store', [App\Http\Controllers\RoleController::class, 'store'])->name('role.store');
        Route::get('role/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('role.edit');
        Route::any('role/{id}/update', [App\Http\Controllers\RoleController::class, 'update'])->name('role.update');
        // Route::resource('role','App\Http\Controllers\RoleController');

        // Route::post('role/{id}/update', [App\Http\Controllers\RoleController::class, 'update'])->name('role.update');   

        //settings
        Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settingsPage'])->name('settings');
        //update settings
        Route::post('/settings/update', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');
        //Payment Gateway resource routes
        Route::resource('payment-gateway', 'App\Http\Controllers\PaymentGatewayController');
        //connect payment gateway
        Route::get('payment-gateway/{id}/connect', [App\Http\Controllers\PaymentGatewayController::class, 'connect'])->name('payment-gateway.connect');
        //disconnect payment gateway
        Route::get('payment-gateway/{id}/disconnect', [App\Http\Controllers\PaymentGatewayController::class, 'disconnect'])->name('payment-gateway.disconnect');


        Route::resource('disburse', 'App\Http\Controllers\DisburseController');
        Route::get('customer-details', [App\Http\Controllers\DisburseController::class, 'getCustomerDetails'])->name('customer_details.get_customer_details');

        // notifications
        Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/{id}/show', [App\Http\Controllers\NotificationController::class, 'show'])->name('notifications.show');
        Route::get('notifications/{id}/destroy', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
        // Route::get('notifications/delete', [App\Http\Controllers\NotificationController::class, 'delete'])->name('notifications.delete');
        Route::get('notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark_all_as_read');
        Route::get('notifications/mark-as-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark_as_read');
    });

    //transaction resource
    Route::resource('transaction', 'App\Http\Controllers\TransactionController');

    //loans pages
    Route::get('/loans/pending', [App\Http\Controllers\LoanController::class, 'pendingLoansPage'])->name('loans.pending');
    Route::get('/loans/approved', [App\Http\Controllers\LoanController::class, 'approvedLoansPage'])->name('loans.approved');
    Route::get('/loans/rejected', [App\Http\Controllers\LoanController::class, 'rejectedLoansPage'])->name('loans.rejected');
    Route::get('/loans/overdue', [App\Http\Controllers\LoanController::class, 'overdueLoansPage'])->name('loans.overdue');
    Route::get('/loans/closed', [App\Http\Controllers\LoanController::class, 'closedLoansPage'])->name('loans.closed');
    Route::get('/loans/active', [App\Http\Controllers\LoanController::class, 'activeLoansPage'])->name('loans.active');
    Route::get('/loans/disbursed', [App\Http\Controllers\LoanController::class, 'disbursedLoansPage'])->name('loans.disbursed');
    Route::get('/loans/due_today', [App\Http\Controllers\LoanController::class, 'dueTodayLoansPage'])->name('loans.due_today');
    Route::get('/loans/due_tomorrow', [App\Http\Controllers\LoanController::class, 'dueTomorrowLoansPage'])->name('loans.due_tomorrow');
    // prehistoric loans
    Route::get('/loans/prehistoric', [App\Http\Controllers\LoanController::class, 'createPrehistoricLoansPage'])->name('loans.prehistoric');
    //store prehistoric loans
    Route::post('/loans/prehistoric/store', [App\Http\Controllers\LoanController::class, 'storePrehistoricLoans'])->name('loans.prehistoric.store');

    //get Pending Loans
    Route::get('/loans/pending/get', [App\Http\Controllers\LoanController::class, 'getPendingLoans'])->name('loans.pending.get');
    //get Approved Loans
    Route::get('/loans/approved/get', [App\Http\Controllers\LoanController::class, 'getApprovedLoans'])->name('loans.approved.get');
    //get Rejected Loans
    Route::get('/loans/rejected/get', [App\Http\Controllers\LoanController::class, 'getRejectedLoans'])->name('loans.rejected.get');
    //get Overdue Loans
    Route::get('/loans/overdue/get', [App\Http\Controllers\LoanController::class, 'getOverdueLoans'])->name('loans.overdue.get');
    //get Closed Loans
    Route::get('/loans/closed/get', [App\Http\Controllers\LoanController::class, 'getClosedLoans'])->name('loans.closed.get');
    //get Active Loans
    Route::get('/loans/active/get', [App\Http\Controllers\LoanController::class, 'getActiveLoans'])->name('loans.active.get');  
    //get Disbursed Loans
    Route::get('/loans/disbursed/get', [App\Http\Controllers\LoanController::class, 'getDisbursedLoans'])->name('loans.disbursed.get');

    Route::resource('loan', 'App\Http\Controllers\LoanController');
    //approve loan
    Route::get('loan/{id}/approve', [App\Http\Controllers\LoanController::class, 'approve'])->name('loan.approve');
    //reject loan
    Route::any('loan/{id}/reject', [App\Http\Controllers\LoanController::class, 'reject'])->name('loan.reject');
    

    Route::resource('customer', 'App\Http\Controllers\CustomerController');
    //active customers
    Route::get('customers/active', [App\Http\Controllers\CustomerController::class, 'active'])->name('customers.active');
    //inactive customers
    Route::get('customers/inactive', [App\Http\Controllers\CustomerController::class, 'inactive'])->name('customers.inactive');
    //search customer
    Route::get('customer/search', [App\Http\Controllers\CustomerController::class, 'search'])->name('customer.search');
    //transaction Report
    // Route::get('transaction/report', [App\Http\Controllers\TransactionController::class, 'transactionReport'])->name('customer.transaction.report');




    ///send customer Transaction Mail
    Route::post('customer/transaction//mail/{{id}}', [App\Http\Controllers\CustomerController::class, 'customerTransactionMail'])->name('customer.transaction.mail');
    

    Route::get('loan/calculator', [App\Http\Controllers\LoanController::class, 'calculator'])->name('loan.calculator'); 
    Route::post('loan/calculate', [App\Http\Controllers\LoanController::class, 'calculate'])->name('loan.calculate');

    //Disburse loan to customer
    Route::get('loan/{id}/disburse', [App\Http\Controllers\LoanController::class, 'create'])->name('loan.disburse');
    Route::post('loan/{id}/disburse', [App\Http\Controllers\LoanController::class, 'store'])->name('loan.disburse');

    //store disburse loan
    Route::post('loan/disburse/store', [App\Http\Controllers\LoanController::class, 'disburseStore'])->name('loan.disburse.store');

    //change password from AdminController
    //Route::get('change/password', [App\Http\Controllers\AdminController::class, 'changePassword'])->name('change.password');
    Route::post('change/password', [App\Http\Controllers\AdminController::class, 'changePassword'])->name('change.password');
    //profile
    Route::get('profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
    Route::post('profile', [App\Http\Controllers\AdminController::class, 'update_profile'])->name('profile.update');


    //Disburse loan  page from DisburseController
    Route::get('disburse/{id}/loan', [App\Http\Controllers\DisburseController::class, 'disburseLoan'])->name('disburse.loan');
    //Disburse loan  using loan_id DisburseController
    Route::post('disburse/{id}/loan', [App\Http\Controllers\DisburseController::class, 'disburseLoanStore'])->name('disburse.loan.store');

    //Reports
    Route::get('transactions/report', [App\Http\Controllers\ReportController::class, 'transactionReport'])->name('transactios.report');
    Route::get('loan/report', [App\Http\Controllers\ReportController::class, 'loanReport'])->name('loan.report');
    Route::get('customers/report', [App\Http\Controllers\ReportController::class, 'customerReport'])->name('customers.report');
    Route::get('disburse/report', [App\Http\Controllers\ReportController::class, 'disburseReport'])->name('disburse.report');
    Route::get('performance/report', [App\Http\Controllers\ReportController::class, 'performanceReport'])->name('performance.report');


    //   Graphs 
    Route::get('graph', [App\Http\Controllers\ReportController::class, 'graphs'])->name('graph');
});

    

