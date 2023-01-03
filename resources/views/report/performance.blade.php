@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'RO Performance Report'])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Total ROs: ({{ $ros->count() }})</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <form method="GET" action="{{ route('performance.report') }}">
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group row">
                    <div class="col-lg-2 d-flex align-items-center">
                      {{ __('From:') }}
                    </div>
                    <div class="col-lg-10">
                      <input type="datetime-local" class="form-control" name="from_date" >                          
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group row">
                    <div class="col-lg-2 d-flex align-items-center">
                      {{ __('To:') }}
                    </div>
                    <div class="col-lg-10 input-group">
                      <input type="datetime-local" class="form-control" name="to_date">
                    </div>
                  </div>
                </div>
                

                <div class="col-lg-4">
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <div class="input-group">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>Filter</button>
                        <a href="{{ route('performance.report') }}" class="btn btn-danger ml-2"><i class="fas fa-sync-alt"></i>Clear</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-striped" id="basic-datatable">
              <thead>
              <tr>
                  <th>{{ __('RO Name') }}</th>
                  <th>{{ __('Total Loans') }}</th>
                  <th>{{ __('Total Loan Amount') }}</th>
                  <th>{{ __('Total Amount Payable') }}</th>
                  <th>{{ __(' Total Active Loans') }}</th>
                  <th>{{ __('Total Overdue Loans') }}</th>
                  <th>{{ __('Total Overdue Amount') }}</th>
                  <th>{{ __('Performance Rate') }}</th>
              </tr>
              </thead>
              <tbody>

              @foreach ($ros as $ro)
               <!-- loop through total_loan_amount and check if ro_id is equal to the ro_id in the loan table -->

                  <tr>
                      <td>{{ $ro->first_name }} {{ $ro->last_name }}</td>
                      @foreach ($total_loan_amount as $total_loan)
                @if ($ro->id == $total_loan['ro_id'])
                      <td>{{ $total_loan['total_loans'] }}</td>
                      <td>{{ $total_loan['total_amount']}}</td>
                      <td>{{ $total_loan['total_payable'] }}</td>
                      <td>{{ $total_loan['total_active_loans'] }}</td>
                      <td>{{ $total_loan['total_overdue_loans'] }}</td>
                      <td>{{ $total_loan['total_overdue_amount'] }}</td>
                      @foreach ($performance as $key => $value)
                        @if ($key == $ro->id)
                          @php
                            $performance_rate = $value;
                          @endphp
                        @endif
                      @endforeach
                      <td>
                        {{ $performance_rate }}%
                      
  
                      </td>

                      @endif
                @endforeach
                  </tr>
         
              @endforeach

         
            
              </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/backend/js/datatables.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#basic-datatable').DataTable();
    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });
</script>

@endpush

