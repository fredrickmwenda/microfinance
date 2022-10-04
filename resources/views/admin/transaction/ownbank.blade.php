@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Ownbank Transactions'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
              <div class="col-lg-4">
                <form method="get" action="{{ route('admin.ownbank.search') }}">
                  <div class="input-group mb-2 col-12">
                     <input type="text" class="form-control" placeholder="Search..." name="q" autocomplete="off" value="">
                     <select class="form-control" name="type">
                        <option value="account_number">{{ __('Search By Account Number') }}</option>
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
                <form method="get" action="{{ route('admin.ownbank.search') }}">
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
                         {{ __(' End Date ') }}
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
            @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                      <th>{{ __('User') }}</th>
                      <th>{{ __('Account') }}</th>
                      <th>{{ __('Trx') }}</th>
                      <th>{{ __('Amount') }}</th>
                      <th>{{ __('Info') }}</th>
                      <th>{{ __('Date') }}</th>
                      <th>{{ __('View') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($transaction as $row)
                    <tr>
                      <td><a href="{{ url('/admin/users',$row->user->id) }}">{{ $row->user->name }}</a></td>
                      <td><a href="{{ url('/admin/users',$row->user->id) }}">{{ $row->user->account_number }}</a></td>
                      <td>{{ $row->trxid }}</td>
                      <td>{{ $row->amount }}</td>
                      <td>{{ $row->info }}</td>
                      <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                      <td>  
                        <a title="view" class="btn btn-info btn-sm" href="{{ route('admin.all.transaction.view', $row->id) }}">
                            <i class="fa fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $transaction->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
</div>
@endsection
