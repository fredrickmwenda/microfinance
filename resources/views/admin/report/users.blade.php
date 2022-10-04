@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'User Report'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                      <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                      <div class="card-header">
                        <h4>{{ __('Total Users') }}</h4>
                      </div>
                      <div class="card-body">
                        {{ $total_user }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                      <i class="fas fa-circle"></i>
                    </div>
                    <div class="card-wrap">
                      <div class="card-header">
                        <h4>{{ __('Active Users') }}</h4>
                      </div>
                      <div class="card-body">
                        {{ $active_user }}
                      </div>
                    </div>
                  </div>
                </div>      
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-danger">
                        <i class="far fa-circle"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{ __('Inactive Users') }}</h4>
                        </div>
                        <div class="card-body">
                          {{ $banned_user }}
                        </div>
                      </div>
                    </div>
                  </div>            
              </div>
              <div class="col-lg-8">
                <form method="get" action="{{ route('admin.report.users.search') }}">
                <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group row">
                        <div class="col-lg-3 d-flex align-items-center">
                          {{ __('From') }}
                       </div>
                       <div class="col-lg-9">
                          <input type="date" class="form-control" name="from">
                       </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group row">
                        <div class="col-lg-3 d-flex align-items-center">
                          {{ __('To') }}
                       </div>
                       <div class="col-lg-9 input-group">
                          <input type="date" class="form-control" name="to">
                          <div class="input-group-append">                                            
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                         </div>
                       </div>
                      </div>
                    </div>
                </div>
              </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                      <th>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                          <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                        </div>
                      </th>
                      <th>{{ __('Name') }}</th>
                      <th>{{ __('Email') }}</th>
                      <th>{{ __('Phone') }}</th>
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('View') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($users as $user)
                    <tr>
                      <td>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->phone }}</td>
                      <td>{{ date('d-m-Y', strtotime($user->created_at)) }}</td>
                      <td>
                        {{ $user->status == 0 ? 'Inactive' : 'Active' }}
                      </td>
                      <td>
                        <a class="btn btn-primary" href="{{ route('admin.users.show', $user->id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                      </td>
                      
                    </tr>
                    @empty 
                       <p>{{ __('No users!') }}</p>
                    @endforelse
                  </tbody>
                </table>
              {{ $users->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
</div>
@endsection


