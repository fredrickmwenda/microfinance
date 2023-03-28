@extends('layouts.backend.app')
@push('css')
<link href="{{asset('assets/backend/admin/assets/css/stio.css')}}" rel="stylesheet" type="text/css" />
<style text="css">
    .pe-2 {
        padding-right: 0.5rem !important;
    }

    .me-5 {
        margin-right: 3rem !important;
    }

    .my-4 {
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
    }

    @media (min-width: 1200px) {
        .fs-2 {
            font-size: 16px !important;
        }
    }

    .btn-light-success {
        color: #50cd89 !important;
        border-color: var(--bs-success-light);
        background-color: #1c3238;
    }

    .fw-bold {
        font-weight: 600 !important;
    }

    .rounded {
        border-radius: 0.475rem !important;
    }

    .py-1 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .px-3 {
        padding-right: 0.75rem !important;
        padding-left: 0.75rem !important;
    }

    .px-4 {
        padding-right: 1rem !important;
        padding-left: 1rem !important;
    }

    .py-3 {
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }

    .ms-2 {
        margin-left: 0.5rem !important;
    }

    .border-dashed {
        border-style: dashed !important;
        border-color: var(--bs-border-dashed-color);
    }

    .me-6 {
        margin-right: 1.5rem !important;
    }

    .border-gray-300 {
        border-color: rgb(32 89 175) !important;
    }
    .card-header .card-toolbar {
    display: flex;
    align-items: center;
    margin: 0.5rem 0;
    flex-wrap: wrap;
    }
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <div class="row mb-4">
                    <div class="col-lg-6">
                    <h4>{{ __('Customer Details') }}</h4>
                    </div>
                </div> -->

                <div class="card mb-5 mb-xl-10" style="box-shadow: 0 4rem 6rem rgb(32 89 175);">
                    <div class="card-header" id="headingOne">

                        <h3 class="mb-0"><b>{{$user->first_name}} {{$user->last_name}} Portfolio<b></h3>
                        <div class="card-toolbar">
                            <div class="filter-courses">
                                
                                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #323232; font-size:18px; font-weight:600;">
                                    Filter
                                    <i class="fa fa-angle-down" aria-hidden="true" style="margin-left:10px"></i>
                                </a>
                                <div class="dropdown-menu menu-levels" aria-labelledby="navbarDropdown" style="min-width: 8rem !important;">
                                    <a class="dropdown-item s-level" data-start_date="{{date('Y-m-d')}}" data-end_date="{{date('Y-m-d')}}" href="#" onclick="">Today</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item s-level active" data-start_date="{{date('Y').'-'.date('m').'-'.'01'}}" data-end_date="{{date('Y-m-d')}}" href="#" onclick="">This Month</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item s-level" data-start_date="{{date('Y-m-d', strtotime('-3 months'))}}" data-end_date="{{date('Y-m-d')}}" href="#" onclick="">3 Month</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item s-level" data-start_date="{{date('Y-m-d', strtotime('-6 months'))}}" data-end_date="{{date('Y-m-d')}}" href="#" onclick="">6 Month</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item s-level" data-start_date="{{date('Y').'-01'.'-01'}}" data-end_date="{{date('Y').'-12'.'-31'}}"href="#" onclick="">Year</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item s-clear" href="#" onclick="">Clear</a>
                                </div>
                            </div>            
                        </div>
                    </div>
                    <div class="card-body pt-9 pb-0">
                        <div class="d-flex flex-wrap flex-sm-nowrap ">
                            <div class="me-7 mb-4">
                                <!--Profile picture-->
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    @if(!empty($user->avatar))
                                    <img src="{{ asset('assets/images/profile/'.$user->avatar) }}" class="rounded-circle" width="150" alt="" />
                                    @else
                                    <img src="{{ asset('assets/backend/admin/assets/img/avatar/avatar-1.png') }}" class="rounded-circle" width="150" alt="">
                                    @endif
                                    <!-- @if (session()->has('user_id'))
                                        <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                    @else
                                        <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-danger rounded-circle border border-4 border-body h-20px w-20px"></div>
                                    @endif -->
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mt-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h3 class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{$user->first_name}} {{$user->last_name}} </h3>
                                            @php

                                            $role = \Spatie\Permission\Models\Role::where('id', $user->role_id)->get();

                                            $role= $role[0]['name'];

                                            @endphp

                                            <a class="btn btn-sm btn-light-success fw-bold ms-2 fs-8 py-1 px-3" style="margin-bottom: 10px;" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan"> {{$role}}</a>

                                            <!-- <span class="badge badge-light-success fw-bolder fs-8 px-4 py-2" style="color: black;">Active</span> -->
                                        </div>

                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <div class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                                <!--get rounded small user icon-->
                                                <span class="me-1">
                                                    <i class="fa fa-phone"></i>
                                                </span>
                                                @if(Auth::user()->id == $user->id)
                                                {{ $user->phone }}
                                                @else
                                                <a href="tel:{{$user->phone}}">{{ $user->phone }} </a>
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                                <span class="me-1">
                                                    <i class="far fa-envelope"></i>
                                                </span>
                                                @if(Auth::user()->id == $user->id)
                                                {{ $user->email }}
                                                @else
                                                <a href="mailto:{{ $user->email }}">{{ $user->email }} </a>
                                                @endif
                                            </div>

                                            <div class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                                <span class="me-1">
                                                    <i class="fa fa-stop"></i>
                                                </span>
                                                @php
                                                $branch = \App\Models\Branch::where('id', $user->branch_id)->get();
                                                @endphp
                                                {{$branch[0]['name']}}
                                            </div>

                                            <div id="user_id" style="display:none">{{$user->id}}</div>

                                        </div>
                                    </div>

                                    <div class="d-flex my-4">
                                        <!-- <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                            <span class="fw-semibold fs-6 text-gray-400">Performance</span>
                                            <span class="fw-bold fs-6">50%</span>
                                        </div> -->

                                        <div class="h-5px mx-3 w-100 bg-light mb-3">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="d-flex flex-wrap flex-stack mb-2">
                                    <div class="d-flex flex-grow-1 w-200px w-sm-300px flex-column mt-3">
                                        <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                            <span class="fw-semibold fs-6 text-gray-400">Performance</span>
                                            <span class="fw-bold fs-6 s-percent" id="progress-percent"></span>
                                        </div>

                                        <div class="h-5px mx-3 w-100 bg-light mb-3">
                                            <div class="progress">
                                            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                <!-- <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap flex-stack">

                                    <div class="d-flex flex-column flex-grow-1 pe-8">
                                        <div class="d-flex flex-wrap">
                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                    </span>
                                                    <div class="fs-2 fw-bold counted" id="count_customers" data-kt-countup="true" data-kt-countup-value="4500" data-kt-countup-prefix="$" data-kt-initialized="1">{{number_format($customers->count()) }}</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">Customers</div>
                                            </div>
                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                    </span>
                                                    <div class="fs-2 fw-bold counted" id="count_all" data-kt-countup="true" data-kt-countup-value="4500" data-kt-countup-prefix="$" data-kt-initialized="1">KES {{number_format($loans->count())}} </div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">All Loans</div>
                                            </div>
                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                    </span>
                                                    <div class="fs-2 fw-bold counted" id="count_active"data-kt-countup="true" data-kt-countup-value="4500" data-kt-countup-prefix="$" data-kt-initialized="1">KES {{number_format($active_loans->count())}}</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">Active Loans</div>
                                            </div>


                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                    </span>
                                                    <div class="fs-2 fw-bold counted" id="count_overdue" data-kt-countup="true" data-kt-countup-value="4500" data-kt-countup-prefix="$" data-kt-initialized="1">KES {{number_format($overdue_loans->count())}}</div>
                                                </div>
                                                <div class="fw-semibold fs-6 text-gray-400">Overdue Loans</div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>

                    </div>
                </div>



                <div class="row">
                    <div class="col-12">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Garnered Customers
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="cust_in">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Name') }}</th>                                                     
                                                        <th>{{ __('Phone') }} </th>
                                                        <th>{{ __('ID') }} </th>
                                                        <th>{{ __('Status') }}</th>
                                                        <th>{{ __('Creator')}} </th>
                                                        <th> {{ __('Actions')}} </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($customers as $customer)
                                                    <tr>
                                                        <td>
                                                            @if ($customer->passport)
                                                            <img src="{{ asset('assets/images/customer/'.$customer->passport) }}" alt="" class="avatar-xs rounded-circle me-2" height="20px" width="20px">
                                                            @else
                                                            <img src="{{ asset('assets/backend/admin/assets/img/avatar/avatar-1.png') }}" alt="" class="avatar-xs rounded-circle me-2" height="20px" width="20px">
                                                            @endif
                                                            {{ $customer->first_name }}{{ $customer->last_name }}
                                                        </td>
                                                        <td>
                                                            <a href="tel:{{$customer->phone}}">{{$customer->phone}}</a>
                                                        </td>
                                                        <td>{{ $customer->national_id }}</td>
                                                        <td>
                                                            @if($customer->status == 'active')
                                                            <span class="badge badge-success">{{ $customer->status }}</span>
                                                            @else
                                                            <span class="badge badge-danger">{{ $customer->status }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $customer->national_id }}</td>
                                                        <td>
                                                            <a class="btn btn-success has-icon" href="{{ route('customer.show', $customer->id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                                                        </td>
                                                        @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <!-- <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> -->
                                            Managed Loans
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="cust_all">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Loan ID') }}</th>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Amount') }} </th>
                                                        <th>{{ __('Payable') }} </th>
                                                        <th>{{ __('Interest') }}</th>
                                                        <th>{{ __('Duration') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                        <th>{{ __('Creator')}} </th>
                                                        <th> {{__('Start Date')}} </th>
                                                        <th> {{ __('Actions')}} </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($loans as $loan)
                                                    <tr>
                                                        <td>{{ $loan->loan_id }}</td>
                                                        <td>
                                                            {{ $loan->customer->first_name }} {{ $loan->customer->last_name }}
                                                        </td>
                                                        <td>{{ $loan->amount }}</td>
                                                        <td>{{ $loan->total_payable }}</td>
                                                        <td>{{ $loan->interest }}</td>
                                                        <td>{{ $loan->duration }}</td>
                                                        <td>
                                                            <span class="badge badge-uccess">{{ $loan->status }}</span>
                                                        </td>
                                                        <td>{{ $loan->creator->first_name }} {{ $loan->creator->last_name }}</td>
                                                        <td>{{ $loan->start_date }}</td>
                                                        <td>
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    {{ __('Action') }}
                                                                </button>
                                                                <div class="dropdown-menu">

                                                                    <a class="dropdown-item has-icon" href=""><i class="fa fa-receipt"></i>{{ __('Download Receipt') }}</a>

                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Active Loans
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        @if(isset($active_loans))
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="cust_active">
                                                <thead>
                                                    <tr>
                                                        <!-- <th>{{ __('Loan ID') }}</th> -->
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Amount') }} </th>
                                                        <th>{{ __('Payable') }} </th>
                                                        <th>{{ __('Interest') }}</th>
                                                        <th>{{ __('Duration') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                        <th>{{ __('Creator')}} </th>
                                                        <th> {{__('Start Date')}} </th>
                                                        <th> {{ __('Actions')}} </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($active_loans as $loan)
                                                    <tr>
                                                        <td>
                                                            {{ $loan->customer->first_name }} {{ $loan->customer->last_name }}
                                                        </td>
                                                        <td>{{ $loan->amount }}</td>
                                                        <td>{{ $loan->total_payable }}</td>
                                                        <td>{{ $loan->interest }}</td>
                                                        <td>{{ $loan->duration }}</td>
                                                        <td>
                                                            <span class="badge badge-uccess">{{ $loan->status }}</span>
                                                        </td>
                                                        <td>{{ $loan->creator->first_name }} {{ $loan->creator->last_name }}</td>
                                                        <td>{{ $loan->start_date }}</td>
                                                        <td>
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    {{ __('Action') }}
                                                                </button>
                                                                <div class="dropdown-menu">

                                                                    <a class="dropdown-item has-icon" href=""><i class="fa fa-receipt"></i>{{ __('Download Receipt') }}</a>

                                                                </div>
                                                            </div>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingFour">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            Overdue Loans
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                    <div class="card-body">
                                        @if(isset($overdue_loans))
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="cust_overdue">
                                                <thead>
                                                    <tr>
                                                        <!-- <th>{{ __('Loan ID') }}</th> -->
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Amount') }} </th>
                                                        <th>{{ __('Payable') }} </th>
                                                        <th>{{ __('Interest') }}</th>
                                                        <th>{{ __('Duration') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                        <th>{{ __('Creator')}} </th>
                                                        <th> {{__('Start Date')}} </th>
                                                        <th> {{__('End Date')}} </th>
                                                        <th> {{ __('Actions')}} </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($overdue_loans as $loan)
                                                    <tr>
                                                        <td>
                                                            {{ $loan->customer->first_name }} {{ $loan->customer->last_name }}
                                                        </td>
                                                        <td>{{ $loan->amount }}</td>
                                                        <td>{{ $loan->total_payable }}</td>
                                                        <td>{{ $loan->interest }}</td>
                                                        <td>{{ $loan->duration }}</td>
                                                        <td>
                                                            <span class="badge badge-uccess">{{ $loan->status }}</span>
                                                        </td>
                                                        <td>{{ $loan->creator->first_name }} {{ $loan->creator->last_name }}</td>
                                                        <td>{{ $loan->start_date }}</td>
                                                        <td>{{ $loan->end_date }}</td>
                                                        <td>
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    {{ __('Action') }}
                                                                </button>
                                                                <div class="dropdown-menu">

                                                                    <a class="dropdown-item has-icon" href=""><i class="fa fa-receipt"></i>{{ __('Download Receipt') }}</a>

                                                                </div>
                                                            </div>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif

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


@endsection


@push('js')
<script>
    $(document).ready(function() {
        $('#cust_in').DataTable({
            responsive: true,
            "pageLength": 10,
        });
        $('#cust_active').DataTable({
            responsive: true,
            "pageLength": 10,
        });
        $('#cust_all').DataTable({
            responsive: true,
            "pageLength": 10,
        });
        $('#cust_overdue').DataTable({
            responsive: true,
            "pageLength": 10,
        });
        var progressData = <?php echo isset($_POST['performance']) ? $_POST['performance'] : 0; ?>;
        console.log(progressData);

        var progressBar = document.getElementById("progress-bar");
        var progressPercent = document.getElementById("progress-percent");

        progressBar.style.width = progressData + "%";
        progressBar.setAttribute("aria-valuenow", progressData);

        if (progressData < 50) {
            progressBar.classList.add("bg-danger");
        } else {
            progressBar.classList.add("bg-success");
        }

        progressPercent.textContent = progressData + "%";
    });
</script>

<script>
 $(".s-level").on("click", function() {
    $(".s-level").removeClass("active");
    $(this).addClass("active");
    var start_date = $(this).data('start_date');
    var end_date = $(this).data('end_date');
    var id = $('#user_id').text();

    console.log(start_date);
    console.log(end_date);
    console.log(id)


    

    //get dashboard data according to date range
    $.ajax({
      //pass the start and end date as parameters to the route
      url: "{{ route('portfolio.data', ['start_date' => ':start_date', 'end_date' => ':end_date', 'id']) }}".replace(':start_date', start_date).replace(':end_date', end_date),
        
      type: "GET",
      success: function(data) {
        // $("#total-loans").html(data.total_loans);
        $("#count_customers").html(data.total_customers);
        $("#count_all").html(data.total_loans);       
        $("#count_active").html(data.total_active_loans);
        $("#count_overdue").html(data.total_overdue_loans);


      }
    });

  });
</script>


<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->


@endpush