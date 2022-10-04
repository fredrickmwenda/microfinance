@extends('layouts.backend.app')

@push('css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Counter Section Update') }}</h4>
            </div>
            @if ($errors->any())
              <div class="alert alert-danger">
                  <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}<br><br>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <form method="POST" action="{{ route('admin.counter.update', $counter_data->id) }}" class="basicform">
              @csrf
              @method('put')
              @php
                $info = json_decode($counter_data->value);
              @endphp
              <div class="card-body">
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Happy Customers Counter') }}</label>
                        <input type="text" class="form-control" placeholder="Happy Customers Counter" required name="happy_customers_no" value="{{ $info->happy_customers_no }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <div class="form-group">
                          <label>{{ __('Happy Customers Title') }}</label>
                          <input type="text" class="form-control" placeholder="Happy Customers Title" required name="happy_customers_title" value="{{ $info->happy_customers_title }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Years in banking Counter') }}</label>
                        <input type="text" class="form-control" placeholder="Years in banking Counter" required name="year_banking_no" value="{{ $info->year_banking_no }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <div class="form-group">
                          <label>{{ __('Years in banking Title') }}</label>
                          <input type="text" class="form-control" placeholder="Years in banking Title" required name="year_banking_title" value="{{ $info->year_banking_title }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Our branches Counter') }}</label>
                        <input type="text" class="form-control" placeholder="Our branches Counter" required name="our_branches_no" value="{{ $info->our_branches_no }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <div class="form-group">
                          <label>{{ __('Our branches Title') }}</label>
                          <input type="text" class="form-control" placeholder="Our branches Title" required name="our_branches_title" value="{{ $info->our_branches_title }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Successfully works Counter') }}</label>
                        <input type="text" class="form-control" placeholder="Successfully works Counter" required name="successfully_work_no" value="{{ $info->successfully_work_no }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <div class="form-group">
                          <label>{{ __('Successfully works Title') }}</label>
                          <input type="text" class="form-control" placeholder="Successfully works Title" required name="successfully_work_title" value="{{ $info->successfully_work_title }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ __('Update') }}</button>
                  </div>
                </div>
              </div>
          </form>
      </div>
  </div>
</div>
@endsection