@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'All Bill Transaction List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-4">
                    <form method="get" action="{{ route('admin.bill.search') }}">
                      <div class="input-group mb-2 col-12">
                         <input type="text" class="form-control" placeholder="Search..." name="q" autocomplete="off" value="">
                         <select class="form-control" name="type">
                            <option value="trx">{{ __('Search By Trx') }}</option>
                         </select>
                         <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>
                            </button>
                         </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-lg-8">
                    <form method="get" action="{{ route('admin.bill.search') }}">
                    <input type="hidden" name="type" value="duration">
                    <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group row">
                            <div class="col-lg-3 d-flex align-items-center">
                              {{ __('Start Date') }}
                           </div>
                           <div class="col-lg-9">
                              <input type="date" class="form-control" name="start_date">
                           </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group row">
                            <div class="col-lg-3 d-flex align-items-center">
                              {{ __('End Date') }}
                           </div>
                           <div class="col-lg-9 input-group">
                              <input type="date" class="form-control" name="end_date">
                              <div class="input-group-append">                                            
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                             </div>
                           </div>
                          </div>
                        </div>
                    </div>
                </form>
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
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Trx ID') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Bill Type') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($transactions as $key => $row)
                    <tr>
                      <td>{{ $key+1 }}</td>
                      <td><a href="{{ route('admin.users.show', $row->user->id) }}">{{ $row->user->name }}</a></td>
                      <td>
                        {{ $row->trxid }}
                      </td>
                      <td>{{ $row->amount }}</td>
                      
                      <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                      <td>{{ str_replace('_',' ',$row->type) }}</td>
                      <td>
                          @if($row->status == 1)
                          <span class="badge badge-success">{{ __('Success') }}</span>
                          @else
                          <span class="badge badge-danger">{{ __('Faild') }}</span>
                          @endif
                         
                      </td>
                      <td>  
                        <a title="view" class="btn btn-info btn-sm" href="{{ route('admin.all.transaction.view', $row->id) }}">
                            <i class="fa fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              {{ $transactions->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
</div>
@endsection