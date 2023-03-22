@extends('layouts.backend.app')
@push('css')
<link href="{{asset('assets/backend/admin/assets/css/stio.css')}}" rel="stylesheet" type="text/css" />

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
                
                    <div class="card">
                    @php
                        $guarantor_name = ucwords($customer->guarantor_first_name) . ' ' . ucwords($customer->guarantor_last_name);
                        $customer_name =  ucwords($customer->first_name) . ' ' . ucwords($customer->last_name);
                        $referee_name =  ucwords($customer->referee_first_name) . ' ' . ucwords($customer->referee_first_name);
                        $next_of_kin_name =  ucwords($customer->next_of_kin_first_name) . ' ' . ucwords($customer->next_of_kin_last_name);
                    @endphp
                        <div class="card-header">
                            <h4> {{ $customer_name }} Details</h4>
                        </div>
                        <div class="card-body">
                        <div class="d-flex flex-wrap flex-sm-nowrap ">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                @if($customer->passport)
                                <img src="{{ asset('assets/images/customer/'.$customer->passport) }}" alt="image" class="rounded-circle" width="150" />
                                @else
                                <img src="{{ asset('assets/backend/admin/assets/img/avatar/avatar-1.png') }}" class="rounded-circle" width="150" alt="">
                                @endif
                                
                                </div>
                                <div class="mt-3">
                                    <h4></h4>
                                    @if(isset($customer->email))
                                    <p>Email:<span><b> {{$customer->email}}</b></span></p>
                                    @endif
                                    <p>Phone: <span><a href="tel:{{$customer->phone}}">{{$customer->phone}}</a></span></p>
                                    <p>ID:<span><b> {{$customer->national_id}}</b></span></p>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">

                                    <div class="d-flex flex-column">
                                        <div class="d-flex mb-2">
                                            <h3 class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">Guarantor Details</h3>
                                        </div>

                                        <div class="d-flex fw-semibold fs-6 mb-4 pe-2">
                                            <ul style="list-style:none;padding:0;">
                                                <li>

                                                <p>Name:<span>{{$guarantor_name}}</span></p>
                                                </li>
                                                <li>
                                                <p>Phone: <span><a href="tel:{{$customer->guarantor_phone}}">{{$customer->guarantor_phone}}</a></span></p>
                                                </li>
                                                <li>
                                                <p>National ID:<span>{{  $customer->guarantor_national_id }}</span></p>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>

                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h3 class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">Referee </h3>
                                        </div>

                                        <div class="d-flex fw-semibold fs-6 mb-4 pe-2">
                                            <ul style="list-style:none;padding:0;">
                                                <li>
                                                <p>Name:<span>{{ $referee_name }} </span></p>
                                                </li>
                                                <li>
                                                <p>Phone: <span><a href="tel:{{$customer->referee_phone}}">{{$customer->referee_phone}}</a></span></p>
                                                </li>
                                                <li>
                                                <p>Relationship:<span>{{  $customer->referee_relationship }}</span></p>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>

                                    <div class="d-flex flex-column">
                                        <div class="d-flex mb-2">
                                        <h3 class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">Next Of Kin</h3>
                                        </div>

                                        <div class="d-flex fw-semibold fs-6 mb-4 pe-2">
                                            <ul style="list-style:none;padding:0;">
                                                <li>
                                                <p>Name:<span>{{$next_of_kin_name}}</span></p>
                                                </li>
                                                <li>
                                                <p>Phone: <span><a href="tel:{{$customer->next_of_kin_phone}}">{{$customer->next_of_kin_phone}}</a></span></p>
                                                </li>
                                                <li>
                                                <p>Relationship:<span>{{ $customer->next_of_kin_relationship }}</span></p>
                                                </li>
                                            </ul>

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
            All Loans
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="cust_all">
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
                        @foreach($loans as $loan)
                        <tr>
                        <!-- <td>{{ $loan->loan_id }}</td> -->
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
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Active Loans
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
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
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Overdue Loans
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
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
    });

</script>


		<!--end::Page Vendors-->
		<!--begin::Page Scripts(used by this page)-->


@endpush