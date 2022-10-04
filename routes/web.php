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

Route::get('/', function () {
    //if the user is logged in, redirect to dashboard
    if (Auth::check()) {
        //get role id
        $role = Auth::user()->role_id;
        //if role is admin, redirect to admin dashboard
        if ($role == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 6 || $role == 7) {
            return redirect()->route('agent.dashboard');
        }
        // return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Auth::routes();

//protect the route with auth middleware
Route::group(['middleware' => ['auth']], function () {
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/admin/dashboard',  [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard');
    //agent dashboard
    Route::get('/agent/dashboard',  [App\Http\Controllers\ROController::class, 'index'])->name('agent.dashboard');
    Route::get('/user/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    // Route::post('/admin/profile', 'AdminController@update_profile')->name('admin.profile.update');
    // Route::get('/admin/change-password', 'AdminController@change_password')->name('admin.change_password');
    // Route::post('/admin/change-password', 'AdminController@update_password')->name('admin.change_password.update');

    //add admin prefix to the route witth name admin.

    
    Route::get('/admin/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('admin.logout');
 
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [App\Http\Controllers\AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [App\Http\Controllers\AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'edit_user'])->name('admin.users.edit');
    Route::post('/admin/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'update_user'])->name('admin.users.update');
    #destroy
    Route::get('/admin/users/{id}/destroy', [App\Http\Controllers\AdminController::class, 'destroy_user'])->name('admin.users.destroy');
    Route::get('/admin/users/delete', [App\Http\Controllers\AdminController::class, 'delete_usera'])->name('admin.users.delete');
    // Route::get('/admin/users/{id}/delete', 'AdminController@delete_user')->name('admin.users.delete');
    // Route::get('/admin/users/{id}/restore', 'AdminController@restore_user')->name('admin.users.restore');
    // Route::get('/admin/users/{id}/permanent-delete', 'AdminController@permanent_delete_user')->name('admin.users.permanent_delete');
    // Route::get('/admin/users/{id}/profile', 'AdminController@user_profile')->name(' admin.users.profile');
    // Route::post('/admin/users/{id}/profile', 'AdminController@update_user_profile')->name('admin.users.profile.update');
    // Route::get('/admin/users/{id}/change-password', 'AdminController@user_change_password')->name('admin.users.change_password');
    // Route::post('/admin/users/{id}/change-password', 'AdminController@update_user_password')->name('admin.users.change_password.update');
    // Route::get('/admin/users/{id}/two-step-auth', 'AdminController@two_step_auth')->name('admin.users.two_step_auth');
    // Route::post('/admin/users/{id}/two-step-auth', 'AdminController@update_two_step_auth')->name('admin.users.two_step_auth.update');
    // Route::get('/admin/users/{id}/two-step-auth-verify', 'AdminController@two_step_auth_verify')->name('adimn.users.two_step_auth_verify');
    // Route::post('/admin/users/{id}/two-step-auth-verify', 'AdminController@update_two_step_auth_verify')->name('admin.users.two_step_auth_verify.update');
    // Route::get('/admin/users/{id}/two-step-auth-verify-otp', 'AdminController@two_step_auth_verify_otp')->name('admin.users.two_step_auth_verify_otp');
    
    
    //getDashboardStatistics which accepts start date and end date as parameters to get the statistics
    Route::get('/dashboard-statistics/{start_date}/{end_date}', [App\Http\Controllers\HomeController::class, 'getDashboardStatistics'])->name('dashboard.data');



    // Route::get('/admin/dashboard/{start_date}/{end_date}', [App\Http\Controllers\HomeController::class, 'getDashboardStatistics'])->name('dashboard.data');

    //Branch resource routes
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('branch', 'App\Http\Controllers\BranchController');
        //Roles
        Route::post('role/destroy', [App\Http\Controllers\RoleController::class, 'destroy'])->name('role.destroy');
        // Route::resource('role','App\Http\Controllers\RoleController');
        Route::get('role', [App\Http\Controllers\RoleController::class, 'index'])->name('role.index');
        Route::get('role/create', [App\Http\Controllers\RoleController::class, 'create'])->name('role.create');
        Route::post('role/store', [App\Http\Controllers\RoleController::class, 'store'])->name('role.store');
        Route::get('role/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('role.edit');

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


    });

    //transaction resource
    Route::resource('transaction', 'App\Http\Controllers\TransactionController');

    //getall pending loans
    Route::get('/admin/loans/pending', [App\Http\Controllers\LoanController::class, 'getPendingLoans'])->name('loans.pending');
    //get all approved loans
    Route::get('/admin/loans/approved', [App\Http\Controllers\LoanController::class, 'getApprovedLoans'])->name('loans.approved');
    //get all rejected loans
    Route::get('/admin/loans/rejected', [App\Http\Controllers\LoanController::class, 'getRejectedLoans'])->name('loans.rejected');
    //get overdue loans
    Route::get('/admin/loans/overdue', [App\Http\Controllers\LoanController::class, 'getOverdueLoans'])->name('loans.overdue');
    //get closed loans
    Route::get('/admin/loans/closed', [App\Http\Controllers\LoanController::class, 'getClosedLoans'])->name('loans.closed');

    //get active loans
    Route::get('/admin/loans/active', [App\Http\Controllers\LoanController::class, 'getActiveLoans'])->name('loans.active');

    //get disbursed loans
    Route::get('/admin/loans/disbursed', [App\Http\Controllers\LoanController::class, 'getDisbursedLoans'])->name('loans.disbursed');
    
    Route::resource('customer', 'App\Http\Controllers\CustomerController');
    //search customer
    Route::get('customer/search', [App\Http\Controllers\CustomerController::class, 'search'])->name('customer.search');
    //transaction Report
    Route::get('transaction/report', [App\Http\Controllers\TransactionController::class, 'transactionReport'])->name('customer.transaction.report');

    Route::resource('loan', 'App\Http\Controllers\LoanController');
    //approve loan
    Route::get('loan/{id}/approve', [App\Http\Controllers\LoanController::class, 'approve'])->name('loan.approve');
    //reject loan
    Route::any('loan/{id}/reject', [App\Http\Controllers\LoanController::class, 'reject'])->name('loan.reject');
    //create a loan
    //Route::get('loan/create', [App\Http\Controllers\LoanController::class, 'create'])->name('loan.create');
    //store a loan
    //Route::post('loan/store', [App\Http\Controllers\LoanController::class, 'store'])->name('loan.store');
    ///send customer Transaction Mail
    Route::post('customer/transaction//mail/{{id}}', [App\Http\Controllers\CustomerController::class, 'customerTransactionMail'])->name('customer.transaction.mail');
    
// Route::resource('loan', 'App\Http\Controllers\LoanController');
   //calculate loan
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
    //change password from UserController
    //Disburse loan  page from DisburseController
    Route::get('disburse/{id}/loan', [App\Http\Controllers\DisburseController::class, 'disburseLoan'])->name('disburse.loan');
    //Disburse loan  using loan_id DisburseController
    Route::post('disburse/{id}/loan', [App\Http\Controllers\DisburseController::class, 'disburseLoanStore'])->name('disburse.loan.store');

    //Reports
    Route::get('transaction/report', [App\Http\Controllers\ReportController::class, 'transactionReport'])->name('transaction.report');
    Route::get('loan/report', [App\Http\Controllers\ReportController::class, 'loanReport'])->name('loan.report');
    Route::get('customers/report', [App\Http\Controllers\ReportController::class, 'customerReport'])->name('customers.report');
    Route::get('disburse/report', [App\Http\Controllers\ReportController::class, 'disburseReport'])->name('disburse.report');
    //performance report
    Route::get('performance/report', [App\Http\Controllers\ReportController::class, 'performanceReport'])->name('performance.report');

    // Route::get('payment-gateway/report', [App\Http\Controllers\ReportController::class, 'paymentGatewayReport'])->name('payment-gateway.report');

    
});

    

