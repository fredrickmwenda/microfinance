@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
              <div class="col-lg-4">
                <form method="get" action="{{ route('admin.loan.rejected.search') }}">
                  <div class="input-group mb-2 col-12">
                     <input type="text" class="form-control" placeholder="Search..." name="q" autocomplete="off" value="">
                     <select class="form-control" name="type">
                        <option value="account_number">{{ __('Search By Account Number') }}</option>
                     </select>
                     <div class="input-group-append">                                            
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                     </div>
                  </div>
                </form>
              </div>
              <div class="col-lg-8">
                <form method="get" action="{{ route('admin.loan.rejected.search') }}">
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
                      <th>{{ __('User Name') }}</th>
                      <th>{{ __('Package Name') }}</th>
                      <th>{{ __('Charge') }}</th>
                      <th>{{ __('Total Amount') }}</th>
                      <th>{{ __('Total Days') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('View') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($loan_rejected as $row)
                    <tr>
                      <td><a href="{{ url('/admin/users',$row->user->id) }}">{{ $row->user->name }}</a></td>
                      <td>
                       {{ $row->loanplan->name }} 
                      </td>
                      <td>{{ $row->charge }}</td>
                      <td>
                        {{ $row->amount }}
                      </td>
                      <td>{{ $row->days }}</td>
                      @if($row->status == 2) 
                      <td ><span class="badge badge-danger">{{ __('Pending') }}</span></td>
                      @endif
                      @if($row->status == 1) 
                      <td><span class="badge badge-success">{{ __('Approved') }}</span></td>
                      @endif
                      @if($row->status == 0) 
                      <td><span class="badge badge-danger">{{ __('Rejected') }}</span></td>
                      @endif
                      <td>
                        <a class="btn btn-primary" href="{{ route('admin.loan.request.show', $row->id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                 {{ $loan_rejected->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
</div>
@endsection