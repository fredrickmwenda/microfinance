@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'All Transaction List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row" mb-4>
              <div class="col-lg-12">
                <form action="{{ route('transaction.index') }}" method="GET">
                  <div class="form-row">                                              
                    <div class="col-lg-6">
                      <div class="input-group form-row">
                        <input type="text" class="form-control" placeholder="Search..." required="" name="value" autocomplete="off" value="" id="query_term">
                        <select class="form-control" name="type">               
                            <option value="trxid">{{ __('Transaction No') }}</option>
                            <option value="name">{{ __('Customer Name') }}</option>
                            <option value="national_id">{{ __('National ID') }}</option>
                        </select>
                      </div>







                      
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="input-group">
                          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>Search</button>
                          <a href="{{ route('transaction.index') }}" class="btn btn-danger ml-2"><i class="fas fa-sync-alt"></i>Refresh</a>
                        </div>
                      </div>                        
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div> 
            
            @if (Session::has('message'))
              <div class="alert alert-danger">{{ Session::get('message') }}</div>
            @endif

            
            <div class="table-responsive">
                <table class="table table-striped" id="transaction_list">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Transaction No') }}</th>
                        <th>{{ __('Customer Name') }}</th>
                        <th>{{ __('Amount Paid') }}</th>
                        <th>{{ __('Total Payable') }}</th>
                        <th>{{ __('Loan amount') }}</th>
                        <th>{{ __('Balance') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($transactions as $key => $row)
                    <tr>
                      <td>{{ $key+1 }}</td>
                      <td>{{ $row->transaction_code }}</td>
                      <td>
                        <a href="#">{{ $row->customer->first_name }} {{ $row->customer->last_name }}</a>
                      </td>
                      <td>
                        {{ $row->transaction_amount }}
                      </td>
                      <td>
                        {{ $row->loan->total_payable }}
                      </td>
                    <td>
                        {{ $row->loan->amount }}
                      </td>
                      
                      <td>{{ $row->remaining_balance }}</td>
                      <!-- get date  from transaction_date field  which is a string in this format 2018-11-27 00:00:00.0 -->
                      <td>{{ date('d-m-Y', strtotime($row->transaction_date)) }}</td>
                      <td>
                          <span class="badge badge-success">{{ $row->transaction_status }}</span>

                      </td>
                      <td>  
                        <a title="view" class="btn btn-info btn-sm" href="#">
                            <i class="fa fa-eye"></i>
                        </a>
                      </td>
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

@push ('js')
<script>
    $(document).ready(function() {
        $('#transaction_list').DataTable({
            responsive: true,

        });
    });
</script> -->
@endpush        