@extends('layouts.backend.app')


@section('content')
<div class="row">
  <div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title">{{ __('Loan Statistics') }}
        </div>
        <div class="card-stats-items">
          <div class="card-stats-item">
            <div class="card-stats-item-count" id="pening_support"><span class="loader">
              <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
            </span></div>
            <div class="card-stats-item-label">{{ __('Open Support') }}</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count" id="pending_withdraw"><span class="loader">
              <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
            </span></div>
            <div class="card-stats-item-label">{{ __('Pending Withdraw') }}</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count" id="pending_deposit"><span class="loader">
              <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
            </span></div>
            <div class="card-stats-item-label">{{ __('Pending Deposit') }}</div>
          </div>
        </div>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-archive"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>{{ __('Total Issues') }}</h4>
        </div>
        <div class="card-body" id="total_issue">
          <span class="loader">
            <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-chart">
        <canvas id="deposit_transactions" height="80"></canvas>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-dollar-sign"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>{{ __('Deposit Transactions') }} - {{ date('Y') }}</h4>
        </div>
        <div class="card-body" id="deposit_sum">
          <span class="loader">
            <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-chart">
        <canvas id="all_transactions" height="80"></canvas>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-shopping-bag"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>{{ __('All Transactions') }} - {{ date('Y') }}</h4>
        </div>
        <div class="card-body" id="all_transaction_count">
          <span class="loader">
            <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="section">
      <h2 class="section-title m-0 mb-3">{{ __('Users & Customers') }}</h2>
      <div class="card">
        <div class="card-body">
            <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <!--customers-->
                  <div class="card-icon bg-primary">
                    <i class="fas fa-user-tag"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Customers') }}</h4>
                    </div>
                    <div class="card-body" id="customer_count">
                      <!--customer count-->
                      <p> {{$total_customers}} </p>
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>

  
                    </div>
                  </div>
                </div>
              </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Users') }}</h4>
                      </div>
                      <div class="card-body" id="total_users">
                        <span>
                        <p>{{$total_users}}</p>

                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-success">
                      <i class="fas fa-circle"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Active Users') }}</h4>
                      </div>
                      <div class="card-body" id="active_users">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>      

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-success">
                      <i class="far fa-circle"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Phone Verified') }}</h4>
                      </div>
                      <div class="card-body" id="phone_verified">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>           
              </div>
          </div>
      </div>
    </div>
    <!-- <div class="section">
      <h2 class="section-title">{{ __('Users Finance Statistics') }}</h2>
      <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Deposit (users)') }}</h4>
                      </div>
                      <div class="card-body" id="total_deposit">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div> -->
    <!-- <div class="section">
      <h2 class="section-title">{{ __('Fix Deposit Statistics') }}</h2>
      <div class="card">
        <div class="card-body">
          <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-primary">
                    <i class="fas fa-money-check-alt"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Number of Deposit') }}</h4>
                    </div>
                    <div class="card-body" id="number_of_fixed_deposit">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-warning">
                    <i class="fas fa-money-check-alt"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('In queue') }}</h4>
                    </div>
                    <div class="card-body" id="fixed_deposit_queue">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-success">
                    <i class="fas fa-money-check-alt"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Completed') }}</h4>
                    </div>
                    <div class="card-body" id="fixed_deposit_completed">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-danger">
                    <i class="fas fa-money-check-alt"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('In queue') }}</h4>
                    </div>
                    <div class="card-body" id="fixed_deposit_cancelled">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-primary">
                    <i class="fas fa-money-check-alt"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Total Deposit') }}</h4>
                    </div>
                    <div class="card-body" id="fixed_deposit_amount">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-primary">
                    <i class="fas fa-money-check-alt"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Total Return') }}</h4>
                    </div>
                    <div class="card-body" id="fixed_deposit_return">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-primary">
                    <i class="fas fa-money-check-alt"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Total Interest') }}</h4>
                    </div>
                    <div class="card-body" id="fixed_deposit_interest">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      </div>
    </div> -->
    <div class="section">
      <h2 class="section-title">{{ __('Loans Statistics') }}</h2>
      <div class="card">
        <div class="card-body">
            <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-warning">
                    <i class="fas fa-hand-holding-usd"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Loan Pending') }}</h4>
                    </div>
                    <div class="card-body" id="loan_pending">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>                  
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-primary">
                    <i class="fas fa-hand-holding-usd"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Loan Queue') }}</h4>
                    </div>
                    <div class="card-body" id="loan_queue">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>                  
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-primary">
                    <i class="fas fa-hand-holding-usd"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Loan Given') }}</h4>
                    </div>
                    <div class="card-body" id="loan_given">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>                  
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 mb-0">
                  <div class="card-icon bg-success">
                    <i class="fas fa-hand-holding-usd"></i>
                  </div>
                  <div class="card-wrap position-relative">
                    <div class="card-header">
                      <h4>{{ __('Loan Complete') }}</h4>
                    </div>
                    <div class="card-body" id="loan_complete">
                      <span class="loader">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                      </span>
                    </div>
                  </div>
                </div>
              </div>                  
            </div>
        </div>
      </div>
    </div>
    <!-- <div class="section">
      <h2 class="section-title">{{ __('Deposit Statistics') }}</h2>
      <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-money-check"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Deposits') }}</h4>
                      </div>
                      <div class="card-body" id="total_deposit_number">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-success">
                      <i class="fas fa-money-check"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Deposit Amount') }}</h4>
                      </div>
                      <div class="card-body" id="total_deposit_amount">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-warning">
                      <i class="fas fa-money-check"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Deposit Charge') }}</h4>
                      </div>
                      <div class="card-body" id="total_deposit_charge">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div> -->
    <!-- <div class="section">
      <h2 class="section-title">{{ __('Transaction Statistics') }}</h2>
      <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Number of Deposit') }}</h4>
                      </div>
                      <div class="card-body" id="other_bank_total">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-success">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Approved') }}</h4>
                      </div>
                      <div class="card-body" id="other_bank_approved">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-danger">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Rejected') }}</h4>
                      </div>
                      <div class="card-body" id="other_bank_reject">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-warning">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Pending') }}</h4>
                      </div>
                      <div class="card-body" id="other_bank_pending">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Amount') }}</h4>
                      </div>
                      <div class="card-body" id="other_bank_amount">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-success">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Charge') }}</h4>
                      </div>
                      <div class="card-body" id="other_bank_charge">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="section">
      <h2 class="section-title">{{ __('Withdraw Statistics') }}</h2>
      <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Withdraw') }}</h4>
                      </div>
                      <div class="card-body" id="withdraw_total">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-warning">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Pending') }}</h4>
                      </div>
                      <div class="card-body" id="withdraw_pending">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-danger">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Rejected') }}</h4>
                      </div>
                      <div class="card-body" id="withdraw_reject">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-warning">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Charge') }}</h4>
                      </div>
                      <div class="card-body" id="withdraw_charge">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div> -->
    <!-- <div class="section">
      <h2 class="section-title">{{ __('Billing Statistics') }}</h2>
      <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Bill') }}</h4>
                      </div>
                      <div class="card-body" id="bill_total">
                        <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span>
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1 mb-0">
                <div class="card-icon bg-warning">
                  <i class="fas fa-money-check-alt"></i>
                </div>
                <div class="card-wrap position-relative">
                  <div class="card-header">
                    <h4>{{ __('Pending') }}</h4>
                  </div>
                  <div class="card-body" id="bill_pending">
                    <span class="loader">
                      <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1 mb-0">
                <div class="card-icon bg-success">
                  <i class="fas fa-money-check-alt"></i>
                </div>
                <div class="card-wrap position-relative">
                  <div class="card-header">
                    <h4>{{ __('Complete') }}</h4>
                  </div>
                  <div class="card-body" id="bill_complete">
                    <span class="loader">
                      <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                    </span>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div> -->
  </div>

</div>

<!--create a row with 2 columns, one for the Loan Payment chart, and a table for loan disburse and repayment-->



@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/sparkline.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/chart.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/page/index.js') }}"></script>
@endpush