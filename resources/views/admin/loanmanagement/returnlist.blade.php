@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Loan Return List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-4">
                  <form method="get" >
                    <div class="input-group mb-2 col-12">
                       <input type="text" class="form-control" value="{{ $request->q ?? '' }}" placeholder="Search..." name="q" autocomplete="off" value="">
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
                  <form method="get" >
                  <input type="hidden" name="type" value="duration">
                  <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group row">
                          <div class="col-lg-3 d-flex align-items-center">
                            {{ __('Start Date') }}
                         </div>
                         <div class="col-lg-9">
                            
                            <input type="date" value="{{ $request->start_date ?? '' }}" class="form-control" name="start_date">
                         </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group row">
                          <div class="col-lg-3 d-flex align-items-center">
                            {{ __('End Date') }} 
                         </div>
                         <div class="col-lg-9 input-group">
                            <input type="date" class="form-control" value="{{ $request->end_date ?? '' }}" name="end_date">
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
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                      <th>{{ __('User Name') }}</th>
                      <th>{{ __('Package Name') }}</th>
                      <th>{{ __('Total Amount') }}</th>
                      <th>{{ __('Total Days') }}</th>
                      <th>{{ __('Return At') }}</th>
                      <th>{{ __('Applied At') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($posts as $row)
                    <tr>
                        <td><a href="{{ url('/admin/users',$row->user->id) }}">{{ $row->user->name }}</a></td>
                        <td>
                         {{ $row->loanplan->name }} 
                        </td>
                        <td>{{ $row->charge+$row->amount }}</td>
                        <td>
                          {{ $row->days }}
                        </td>
                        <td>{{ $row->return_date }}</td>
                        <td>{{ $row->created_at->format('Y-m-d') }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              <div class="float-right">
              {{ $posts->links('vendor.pagination.bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection