@extends('layouts.backend.app')

@push('css')
    <!-- Custom styles for this page -->
   
    <style type="text/css">
      .filter-toggle {
        float: right;
        list-style: none;
        margin-top: 15px;
      }
/* 
        .dataTables_filter {
            float: right;
        }

        .dataTables_length {
            float: left;
        }

        .dataTables_info {
            float: left;
        }

        .dataTables_paginate {
            float: right;
        }

        .dataTables_paginate ul.pagination {
            margin: 0;
        }

        .dataTables_paginate ul.pagination li {
            margin: 0;
        }

        .dataTables_paginate ul.pagination li a {
            padding: 5px 10px;
        }

        .dataTables_paginate ul.pagination li.active a {
            background-color: #4e73df;
            border-color: #4e73df;
        } */
    </style>
@endpush

@section('content')
<div class="row" style="padding-top: 35px!important; padding-bottom: 15px;">
  <!-- <div class="container-fluid"> -->
    <div class="col-md-12">
      <div class="brand-text float-left mt-4">
          <h3>Welcome <span>{{ Auth::user()->first_name }} </span></h3>
      </div>
      <div class="filter-toggle btn-group">
        <button class="btn btn-secondary date-btn " data-start_date="{{date('Y-m-d')}}" data-end_date="{{date('Y-m-d')}}">Today</button>
        <button class="btn btn-secondary date-btn" data-start_date="{{date('Y-m-d', strtotime(' -7 day'))}}" data-end_date="{{date('Y-m-d')}}">Last 7 Days</button>
        <button class="btn btn-secondary date-btn active" data-start_date="{{date('Y').'-'.date('m').'-'.'01'}}" data-end_date="{{date('Y-m-d')}}">This Month</button>
        <button class="btn btn-secondary date-btn" data-start_date="{{date('Y').'-01'.'-01'}}" data-end_date="{{date('Y').'-12'.'-31'}}">This Year</button>
      </div>
    </div>
  <!-- </div> -->
</div>
<div class="row">

  <!-- <div class="col-lg-4 col-md-4 col-sm-12">
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
  </div> -->
  <div class="col-12">
    <div class="section">
      <!-- <h2 class="section-title m-0 mb-3">{{ __('Users & Customers') }}</h2> -->
      <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Total Customers') }}</h4>
                      </div>
                      <div class="card-body" id="total_users">
                        <span>
                        <p id="total-users">{{$total_customers}}</p>

                        </span>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-success">
                      <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Loans') }}</h4>
                      </div>
                      <div class="card-body" id="active_users">
                        <span>
                        <p id ="total-expenditure">{{$total_loans}}</p>
                        </span>
                        <!-- <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span> -->
                      </div>
                    </div>
                  </div>
                </div>      

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-success">
                      <i class="fas fa-donate"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Performace Rate') }}</h4>
                      </div>
                      <div class="card-body" id="phone_verified">
                        <span>
                        <p id ="total-profit">{{$performance_rate_ro}}</p>
                          
                        </span>
                      </div>
                    </div>
                  </div>
                </div>           
              </div>
          </div>
      </div>
    </div>



 
  </div>
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="card">
      <!--Card header-->
 
      <div class="card-header" style="min-height: 58px!important;border-bottom: 0px!important;">
        <h4>{{ __('Loan Statistics') }}</h4>
      </div>
      <div class="card-body">
          <div class="row">

            <div class="col-lg-12 col-md-9 col-sm-9 col-12">
              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Loans Pending') }}</h4>
                      </div>
                      <div class="card-body" id="loans_queue_pending">
                        <span>
                          <p id="total-pending-loans">{{$total_pending_loans}}</p>
                          <span >
                            <p id="total-amount-pending-loans">{{$total_amount_pending_loans}}</p>
                          </span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Loans Approved') }}</h4>
                      </div>
                      <div class="card-body" id="loan_given">
                        <span>
                          <p id="total-approved-loans">{{$total_approved_loans}}</p>
                          <span >
                            <p id="total-amount-approved-loans">{{$total_amount_approved_loans}}</p>
                          </span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-success">
                      <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Loans Rejected ') }}</h4>
                      </div>
                      <div class="card-body" id="loan_complete">
                        <span>
                          <p id="total-rejected-loans">{{$total_rejected_loans}}</p>
                          <span >
                            <p id="total-amount-rejected-loans">{{$total_amount_rejected_loans}}</p>
                          </span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Loans Disbursed') }}</h4>
                      </div>
                      <div class="card-body" id="loan_queue">
                        <!--have 2 sections one with count and one with amount-->
                        <span>
                          <p id="total-disbursed-loans">{{$total_disbursed_loans}}</p>
                          <span >
                            <p id="total-amount-disbursed-loans">{{$total_amount_disbursed_loans}}</p>
                          </span>
                        </span>

                        <!-- <span class="loader">
                          <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                        </span> -->
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-primary">
                      <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Loans Paid') }}</h4>
                      </div>
                      <div class="card-body" id="loan_given">
                        <span>
                          <p id="total-closed-loans">{{$total_closed_loans}}</p>
                          <span >
                            <p id="total-amount-closed-loans">{{$total_amount_closed_loans}}</p>
                          </span>
                        </span>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                  <div class="card card-statistic-1 mb-0">
                    <div class="card-icon bg-success">
                      <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-wrap position-relative">
                      <div class="card-header">
                        <h4>{{ __('Loans OverDue') }}</h4>
                      </div>
                      <div class="card-body" id="loan_complete">
                        <span>
                          <p id="total-overdue-loans">{{$total_overdue_loans}}</p>
                          <span >
                            <p id="total-amount-overdue-loans">{{$total_amount_overdue_loans}}</p>
                          </span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              
              
            </div>

                              
          </div>

      </div>
      </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Loans') }}</h4>
      </div>
      <div class="card-body">
        <div class="chart-container">
        <canvas id="loansChart" height="280"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('RO Performance') }}</h4>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="roPerformanceChart" height="280"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>



<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Overdue Loans') }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="overdueLoanTable">
            <thead>
              <tr>
                <th> {{ __('Loan ID') }} </th>
                <th> {{ __('Customer Name') }} </th>
                <th>{{ __('Loan Amount') }}</th>
                <th>{{ __('Total Amount') }}</th>
                <th>{{ __('RO officer') }}</th>
                <th>{{ __('Due Date') }}</th>
                <th>{{ __('Overdue Days') }}</th>
                <th>{{ __('Loan Status') }}</th>
                <!-- <th>{{ __('Overdue Amount') }}</th> -->

              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="2" class="text-center">
                  <span class="loader">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Pending Loans') }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="pendingLoanTable">
            <thead>
              <tr>
                <th> {{ __('ID') }} </th>
                <th> {{ __('Loan ID') }} </th>
                <th> {{ __('Customer Name') }} </th>                
                <th>{{ __('Loan Amount') }}</th>
                <th>{{ __('Total Amount') }}</th>
                <th> {{ __('Loan Duration') }} </th>
                <th>{{ __('RO officer') }}</th>
                <th>{{ __('Loan Status') }}</th>
                <th>{{ __('Actions') }}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Approved Loans') }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="approvedLoanTable">
            <thead>
              <tr>
                <th> {{ __('ID') }} </th>
                <th> {{ __('Loan ID') }} </th>
                <th> {{ __('Customer Name') }} </th>                
                <th>{{ __('Loan Amount') }}</th>
                <th>{{ __('Total Amount') }}</th>
                <th> {{ __('Loan Duration') }} </th>
                <th>{{ __('RO officer') }}</th>
                <th> {{ __('Approved Date') }} </th>
                <th> {{ __('Approved By') }} </th>
                <th>{{ __('Loan Status') }}</th>
                <th>{{ __('Actions') }}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Disbursed Loans') }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="disbursedLoanTable">
            <thead>
              <tr>
                <th> {{ __('ID') }} </th>
                <th> {{ __('Loan ID') }} </th>
                <th> {{ __('Customer Name') }} </th>                
                <th>{{ __('Loan Amount') }}</th>
                <th>{{ __('Total Amount') }}</th>
                <th> {{ __('Loan Duration') }} </th>
                <th>{{ __('RO officer') }}</th>
                <th> {{ __('Disbursed Date') }} </th>
                <th> {{ __('Due Date') }} </th>
                <th>{{ __('Loan Status') }}</th>
                <th>{{ __('Actions') }}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!--loans which have started to be paid but not yet completed-->
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Active Payment Loans') }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="activeLoanTable">
            <thead>
              <tr>
                <th> {{ __('Loan ID') }} </th>
                <th> {{ __('Customer Name') }} </th>
                <th>{{ __('Loan Amount') }}</th>
                <th>{{ __('Total Amount') }}</th>
                <th> {{ __('Remaining Amount') }} </th>
                <th> {{ __('Loan Duration') }} </th>
                <th>{{ __('RO officer') }}</th>
                <th>{{ __('Due Date') }}</th>
                <th>{{ __('Loan Status') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="2" class="text-center">
                  <span class="loader">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Complete Loans') }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="completeLoanTable">
            <thead>
              <tr>

                <th> {{ __('Loan ID') }} </th>
                <th> {{ __('Customer Name') }} </th>
                <th>{{ __('Loan Amount') }}</th>
                <th>{{ __('Total Amount') }}</th>
                <th> {{ __('Loan Duration') }} </th>
                <th>{{ __('RO officer') }}</th>
                <th>{{ __('Due Date') }}</th>
                <th>{{ __('Loan Status') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="2" class="text-center">
                  <span class="loader">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="" width=50>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection

@push('js')
<!--datatables-->

<script src="{{ asset('assets/backend/admin/assets/js/sparkline.js') }}"></script>
<script src="{{ asset('assets/backend/admin/assets/js/chart.min.js') }}"></script>
<!-- <script src="{{ asset('assets/backend/admin/assets/js/page/index.js') }}"></script> -->




<script type="text/javascript">
  $(function(){
    var table = $('#pendingLoanTable'). DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('loans.pending.get') }}",
      columns: [
        {data: 'id', name: 'id'},
        {data: 'loan_id', name: 'loan_id'},
        //from customer_id in loans table, get customer first name and last name
       //return the first name and last name as a string
        {data: 'customer', name: 'customer', render: function(data, type, row){
          return data.first_name + ' ' + data.last_name;
        }},
        {data: 'amount', name: 'loan_amount'},
        {data: 'total_payable', name: 'total_payable'},
        {data: 'duration', name: 'duration'},
        {data: 'creator', name: 'creator', render: function(data, type, row){
          return data.first_name + ' ' + data.last_name;
        }},
        {data: 'status', name: 'loan_status'},
        {
          data: 'action', 
          name: 'action', 
          orderable: true, 
          searchable: true
        },

      ]
    });


  });

  $(function(){
    var table = $('#disbursedLoanTable'). DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('loans.disbursed.get') }}",
      columns: [
        {data: 'id', name: 'id'},
        {data: 'loan_id', name: 'loan_id'},
        //from customer_id in loans table, get customer first name and last name
       //return the first name and last name as a string
        {data: 'customer', name: 'customer', render: function(data, type, row){
          return data.first_name + ' ' + data.last_name;
        }},
        {data: 'amount', name: 'loan_amount'},
        {data: 'total_payable', name: 'total_loan'},
        {data: 'duration', name: 'loan_duration'},
        {data: 'creator', name: 'creator', render: function(data, type, row){
          return data.first_name + ' ' + data.last_name;
        }},
        //loan end date set it in carbon
        {data: 'start_date', name: 'loan_start_date'},
        {data: 'end_date', name: 'loan_end_date'},
        {data: 'status', name: 'loan_status'},
        {
          data: 'action', 
          name: 'action', 
          orderable: true, 
          searchable: true
        },

      ]
    });




    //overdue loans


  });


  $(function(){
    var table = $('#approvedLoanTable'). DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('loans.approved.get') }}",
      columns: [
        {data: 'id', name: 'id'},
        {data: 'loan_id', name: 'loan_id'},
        //from customer_id in loans table, get customer first name and last name
       //return the first name and last name as a string
        {data: 'customer', name: 'customer', render: function(data, type, row){
          return data.first_name + ' ' + data.last_name;
        }},
        {data: 'amount', name: 'amount'},
        {data: 'total_payable', name: 'total_payable'},
        {data: 'duration', name: 'duration'},
        {data: 'creator', name: 'creator', render: function(data, type, row){
          return data.first_name + ' ' + data.last_name;
        }},
        //loan end date set it in carbon
        {data: 'approved_at', name: 'approved_at'},
        {data: 'approver', name: 'approver', render: function(data, type, row){
          return data.first_name + ' ' + data.last_name;
        }},
        {data: 'status', name: 'status'},
        {
          data: 'action', 
          name: 'action', 
          orderable: true, 
          searchable: true
        },

      ]
    });

  });


  // get id of loanchart
  var loanGraph = $('#loansChart');

  var performanceGraph = $('#roPerformanceChart');

  var loanData = JSON.parse('<?php echo isset($loanData) ? $loanData : '' ?>');

 

  


var activeloanData = JSON.parse('<?php echo isset($activeLoanData) ? $activeLoanData : '' ?>');

var overdueLoanData = JSON.parse('<?php echo isset($overDueLoanData) ? $overDueLoanData : '' ?>');

var ROPerformanceData = JSON.parse('<?php echo isset($ROPerformanceData) ? $ROPerformanceData : '' ?>');

console.log(loanData, activeloanData,  overdueLoanData, ROPerformanceData);

  // console.log(loanData[0].loans);


  var loanCtx = document.getElementById('loansChart').getContext('2d');


  var performanceCtx = document.getElementById('roPerformanceChart').getContext('2d');

//  Label months in short form
  const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

  //create the chart
  var loanChart = new Chart(loanCtx, {
    type: 'line',
    //append data to the chart by checking the loanData array, if the month is equal to the month in the array, append the data to the chart


    data: {
      // labels: loanData.labels,
      labels: labels,
      datasets: [
      
        {
          label: 'Loans',
          data: loanData,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        },
        {
          label: 'Active Loans',
          data: activeloanData,
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        },
        {
          label: 'Overdue Loans',
          data: overdueLoanData, 
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1
        },
        // add padding 
      ]


    },
    options: {
      responsive: true,
      legend: {
                display: false
            },
      scales: {
        // yAxes start from 0 to 1000
        y: {
          stacked: false,
          suggestedMin: 0,
          suggestedMax: 200,
          title: {
            display: true,
            text: 'Number of Loans',
            // color: 'black',
            font: {
              size: 14,
              weight: 'bold',
              color: 'black'
            },
            align: 'center',
            padding: {
              top: 10,
              bottom: 10
            }
          },



          ticks: {
            beginAtZero: true,
            stepSize: 20,
          },
          // gridLines: {
          //   display: true,
          //   color: 'rgba(0, 0, 0, 0.1)'
          // }
        },
        x: {
          title : {
            display: true,
            text: 'Months',
            // color: 'black',
            font: {
              size: 14,
              weight: 'bold',
              color: 'black'
            },
            align: 'center',
            padding: {
              top: 10,
              bottom: 10
            }
          },
        }
      }
    }
  });


  //create the chart



  var performanceChart = new Chart(performanceCtx, {
    type: 'line',
    data: {
      labels: labels,
      // // loop through json data in ROPerformanceData and append to the chart
      

      datasets: [{
        label: 'Performance',      
        data: ROPerformanceData,
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      legend: {
                display: false
            },
      scales: {
        // yAxes start from 0 to 1000
        y: {
          stacked: false,
          suggestedMin: 0,
          // max is 500,000
          suggestedMax: 100,
          title: {
            display: true,
            text: 'Users Performance',
            // color: 'black',
            font: {
              size: 14,
              weight: 'bold',
              color: 'black'
            },
            align: 'center',
            padding: {
              top: 10,
              bottom: 10
            }
          },



          ticks: {
            beginAtZero: true,
            // stepSize: 5,
          },
          // gridLines: {
          //   display: true,
          //   color: 'rgba(0, 0, 0, 0.1)'
          // }
        },
        x: {
          title : {
            display: true,
            text: 'Months',
            // color: 'black',
            font: {
              size: 14,
              weight: 'bold',
              color: 'black'
            },
            align: 'center',
            padding: {
              top: 10,
              bottom: 10
            }
          },
        }
      }
    }
  });







  $(".date-btn").on("click", function() {
    $(".date-btn").removeClass("active");
    $(this).addClass("active");
    var start_date = $(this).data('start_date');
    var end_date = $(this).data('end_date');

    console.log(start_date);
    console.log(end_date);


    

    //get dashboard data according to date range
    $.ajax({
      //pass the start and end date as parameters to the route
      url: "{{ route('ro.dashboard.data', ['start_date' => ':start_date', 'end_date' => ':end_date']) }}".replace(':start_date', start_date).replace(':end_date', end_date),
        
      type: "GET",
      success: function(data) {
        // $("#total-loans").html(data.total_loans);
        $("total-expenditure").html(data.expenditure);
        // $("#total-revenue").html(data.revenue);
        $("#total-profit").html(data.profit);       
        $("#total-customers").html(data.total_customers);
        $("#total-users").html(data.total_users);
        $("#total-loans").html(data.total_loans);
        $("#total-amount-loans").html(data.total_amount_loans);
        //pending loans
        $("#total-pending-loans").html(data.total_pending_loans);
        $("#total-amount-pending-loans").html(data.total_amount_pending_loans);
        //overdue loans
        $("#total-overdue-loans").html(data.total_overdue_loans);
        $("#total-amount-overdue-loans").html(data.total_amount_overdue_loans);
        //disbursed loans
        $("#total-disbursed-loans").html(data.total_disbursed_loans);
        $("#total-amount-disbursed-loans").html(data.total_amount_disbursed_loans);
        //active loans
        $("#total-active-loans").html(data.total_active_loans);
        $("#total-amount-active-loans").html(data.total_amount_active_loans);
        //completed loans
        $("#total-closed-loans").html(data.total_closed_loans);
        $("#total-amount-closed-loans").html(data.total_amount_closed_loans);
        //rejected loans
        $("#total-rejected-loans").html(data.total_rejected_loans);
        $("#total-amount-rejected-loans").html(data.total_amount_rejected_loans);
        //approved loans
        $("#total-approved-loans").html(data.total_approved_loans);
        $("#total-amount-approved-loans").html(data.total_amount_approved_loans);

      }
    });

  });




</script>




@endpush