@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Approved Manual Deposit List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <form method="get" action="{{ route('admin.manual_deposit.approve.search') }}">
                      <div class="input-group mb-2 col-12">
                         <input type="text" class="form-control" placeholder="Search..." name="q" autocomplete="off" value="">
                         <select class="form-control" name="type">
                            <option value="account_number">{{ __('Search By Account Number') }}</option>
                            <option value="trx">{{ __('Search By Trx') }}</option>
                         </select>
                         <div class="input-group-append">                                            
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                         </div>
                      </div>
                    </form>
                </div>
                <div class="col-lg-12">
                  @if(Session::has('message'))
                    <p class="alert alert-danger">
                        {{ Session::get('message') }}
                    </p>
                  @endif
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                      <th>{{ __('Trx') }}</th>
                      <th>{{ __('User') }}</th>
                      <th>{{ __('Account') }}</th>
                      <th>{{ __('Payment Gateway') }}</th>
                      <th>{{ __('Amount') }}</th>
                      <th>{{ __('Charge') }}</th>
                      <th>{{ __('Status') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($deposits as $deposit)
                    <tr>
                      <td><a href="{{ route('admin.manual_deposit_view',$deposit->id) }}">{{ $deposit->trx }}</a></td>
                      <td class="align-middle"> {{ $deposit->user->name }}</td>
                      <td>{{ $deposit->user->account_number }}</td>
                      <td>{{ $deposit->getway->name }}</td>
                      <td>{{ $deposit->amount }}</td>
                      <td>{{ $deposit->charge }}</td>
                      <td>
                        @if ($deposit->status == 1)
                            <span class="badge badge-success">{{ __('Success') }}</span>
                        @elseif($deposit->status == 0)
                            <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                        @else 
                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                 {{ $deposits->links('vendor.pagination.bootstrap-4') }}
              </div>
            </div>
        </div>
    </div>
</div>
@endsection



