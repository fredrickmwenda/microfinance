@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'All Transaction List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row">
              <div class="col-6">
              <!-- 'admin.all.transaction.search.report -->
                <form action="">
                  <div class="form-row">
                      <div class="col-lg-5">
                          <div class="form-group">
                              <label>{{ __('Start Date') }}</label>
                              <input type="date" class="form-control" name="start_date" required>
                          </div>
                      </div>
                      <div class="col-lg-5">
                          <div class="form-group">
                              <label>{{ __('End Date') }}</label>
                              <input type="date" class="form-control" name="end_date" required>
                          </div>
                      </div>
                      <div class="col-lg-2 mt-2">
                          <button type="submit" class="btn btn-primary mt-4">{{ __('Search') }}</button>
                      </div>                                    
                  </div>
                </form>
              </div>
              <div class="col-6 mt-2">
                
                 <form action="">
                    <div class="input-group form-row mt-3">

                       <input type="text" class="form-control" placeholder="Search..." required="" name="value" autocomplete="off" value="" id="query_term">
                       <select class="form-control" name="type">
                    
                          <option value="trxid">{{ __('Transaction No') }}</option>
                       </select>
                       <div class="input-group-append">                                            
                          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                       </div>
                    </div>
                  </form>
              </div>
            </div>
            @if (Session::has('message'))
              <div class="alert alert-danger">{{ Session::get('message') }}</div>
            @endif
            @isset($start_date)
            <strong>{{ date('d-m-Y', strtotime($start_date)) }} {{ __('Date To') }} {{ date('d-m-Y', strtotime($end_date)) }} {{ __('Date Report') }}</strong>
            <br>
            @endisset
            @isset($trx_id)
            <strong>{{ __('Search By') }} {{ $trx_id }}</strong>
            <br>
            @endisset 
            <div class="table-responsive">
                <table class="table table-striped" id="transaction_list">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Transaction No') }}</th>
                        <th>{{ __('Customer Name') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Loan Amount') }}</th>

                        <th>{{ __('Trx ID') }}</th>
                        <th>{{ __('Amount') }}</th>
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
                      <td><a href="{{ route('admin.users.show', $row->user->id) }}">{{ $row->customer->first_name }} {{ $row->customer->last_name }}</a></td>
                      <td>
                        
                      </td>
                      <td></td>
                      <td>{{ $row->balance }}</td>
                      <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                      <td>
                          @if($row->status == "sent")
                          <span class="badge badge-success">{{ __('Success') }}</span>
                          @else
                          <span class="badge badge-danger">{{ __('Failed') }}</span>
                          @endif
                      </td>
                      <td>  
                        <a title="view" class="btn btn-info btn-sm" href="{{ route('admin.disburse.view', $row->id) }}">
                            <i class="fa fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              {{ $transactions ->links('vendor.pagination.bootstrap-4') }}
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