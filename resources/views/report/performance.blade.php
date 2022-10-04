@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'RO Performance Report'])
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
                        <h4>{{ __('Total ROs') }}</h4>
                      </div>
                      <div class="card-body">
                        {{ $total_ro }}
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
                        <h4>{{ __('Active ROs') }}</h4>
                      </div>
                      <div class="card-body">
                        {{ $active_ro }}
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
                          <h4>{{ __('Inactive ROs') }}</h4>
                        </div>
                        <div class="card-body">
                          {{ $banned_ro }}
                        </div>
                      </div>
                    </div>
                  </div>            
               </div>
              <div class="col-lg-8">
                <form method="get" action="{{ route('admin.report.ro.search') }}">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="start_date">{{ __('Start Date') }}</label>
                        <input type="text" class="form-control datepicker" name="start_date" id="start_date" value="{{ $start_date }}" placeholder="{{ __('Start Date') }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="end_date">{{ __('End Date') }}</label>
                        <input type="text" class="form-control datepicker" name="end_date" id="end_date" value="{{ $end_date }}" placeholder="{{ __('End Date') }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="end_date">{{ __('Select RO') }}</label>
                        <select class="form-control" name="ro_id">
                          <option value="">{{ __('Select RO') }}</option>
                          @foreach ($ros as $ro)
                          <option value="{{ $ro->id }}" {{ $ro->id == $ro_id ? 'selected' : '' }}>{{ $ro->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                    </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive">
                <table class="table table-striped table-bordered" id="basic-datatable">
                    <thead>
                    <tr>
                        <th>{{ __('RO Name') }}</th>
                        <th>{{ __('Total Orders') }}</th>
                        <th>{{ __('Total Amount') }}</th>
                        <th>{{ __('Total Commission') }}</th>
                        <th>{{ __('Total Paid') }}</th>
                        <th>{{ __('Total Due') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($ros as $ro)
                    <tr>
                        <td>{{ $ro->name }}</td>
                        <td>{{ $ro->orders->count() }}</td>
                        <td>{{ $ro->orders->sum('total') }}</td>
                        <td>{{ $ro->orders->sum('commission') }}</td>
                        <td>{{ $ro->orders->sum('paid') }}</td>
                        <td>{{ $ro->orders->sum('due') }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div >
</div>

@endsection
@push('js')
<script src="{{ asset('assets/backend/js/datatables.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#basic-datatable').DataTable();
    });

