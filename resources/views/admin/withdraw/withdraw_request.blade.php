@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Withdraw Requests'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
              <div class="col-lg-4">
                <form method="get" action="{{ route('admin.withdraw.search') }}">
                  <div class="input-group mb-2 col-12">
                     <input type="text" class="form-control" placeholder="Search..." name="q" autocomplete="off" value="{{ $request->q ?? '' }}">
                     <select class="form-control" name="type">
                        <option value="account_number">{{ __('Search By Account Number') }}</option>
                        <option value="withdraw_method">{{ __('Search By Withdraw Method') }}</option>
                     </select>
                     <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>
                        </button>
                     </div>
                  </div>
                </form>
              </div>
              <div class="col-lg-8">
                <form method="get" action="{{ route('admin.withdraw.search') }}">
                <input type="hidden" name="type" value="duration">
                <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group row">
                        <div class="col-lg-3 d-flex align-items-center">
                          {{ __('Start Date') }}
                       </div>
                       <div class="col-lg-9">
                          <input type="date" class="form-control" name="start_date" value="{{ $request->start_date ?? '' }}">
                       </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group row">
                        <div class="col-lg-3 d-flex align-items-center">
                          {{ __('End Date') }} 
                       </div>
                       <div class="col-lg-9 input-group">
                          <input type="date" class="form-control" name="end_date" value="{{ $request->end_date ?? '' }}">
                          <div class="input-group-append">                                            
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
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
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                      
                      <th>{{ __('Withdraw Method') }}</th>
                      <th>{{ __('User Name') }}</th>
                      <th>{{ __('Account No') }}</th>
                      <th>{{ __('Amount') }}</th>
                      <th>{{ __('Charge') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Action') }}</th>
                      <th>{{ __('View') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($withdraws as $withdraw)
                    <tr>
                      <td>{{ $withdraw->withdrawmethod->title }}</td>
                      <td><a href="{{ url('/admin/users',$withdraw->user->id) }}">{{ $withdraw->user->name }}</a></td>
                      <td><a href="{{ url('/admin/users',$withdraw->user->id) }}">{{ $withdraw->user->account_number }}</a></td>
                      <td class="align-middle">
                        {{ $withdraw->amount_usd }}
                      </td>
                      <td>
                        {{ $withdraw->fee }}
                      </td>
                        @if($withdraw->status == 2) 
                            <td class=""><span class="badge badge-danger">{{ __('Pending') }}</span></td>
                        @endif
                        @if($withdraw->status == 1) 
                            <td class=""><span class="badge badge-success">{{ __('Approved') }}</span></td>
                        @endif
                        @if($withdraw->status == 0) 
                            <td class=""><span class="badge badge-danger">{{ __('Rejected') }}</span></td>
                        @endif
                      <td>
                        @if($withdraw->status == 2) 
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Action
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.withdraw.request.approved', $withdraw->id) }}"><i class="fa fa-eye"></i>{{ __('Approved') }}</a>
                            <a class="dropdown-item has-icon" href="{{ route('admin.withdraw.request.rejected', $withdraw->id) }}"><i class="fa fa-trash"></i>{{ __('Rejected') }}</a>
                          </div>
                        </div>
                        @endif
                      </td>
                      <td>  
                        <a title="view" class="btn btn-info btn-sm" href="{{ route('admin.withdraw.request.view', $withdraw->id) }}">
                            <i class="fa fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="float-right">
                  {{ $withdraws->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection