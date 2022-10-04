@extends('layouts.backend.app')

@push('css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/select2.min.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Manual Payment Gateway Create') }}</h4>
            </div>
            <form method="POST" action="{{ route('admin.manual_gateway.store') }}" class="basicform">
              @csrf
              <div class="card-body">
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Payment Method Name') }}</label>
                          <input type="text" name="name" class="form-control" placeholder="Payment Method Name" required>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Select Currency') }}</label>
                        <select name="currency[]" class="form-control select2" multiple="multiple">
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->title }}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Minimum Amount') }}</label>
                        <input type="number" step="any" name="deposit_min" class="form-control" placeholder="Minimum Amount" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Maximum Amount') }}</label>
                      <input type="number" step="any" name="deposit_max" class="form-control" placeholder="Maximum Amount" required>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Charge Type') }}</label>
                      <select name="charge_type" class="form-control" id="charge_type">
                        <option value="">{{ __('Select charge type') }}</option>
                        <option value="fixed">{{ __('Fixed') }}</option>
                        <option value="percentage">{{ __('Percentage') }}</option>
                        <option value="both">{{ __('Both') }}</option>
                      </select>
                    </div>                  
                    <!--- Transaction Charge Both --->
                    <div>
                      <div class="form-row">
                        <div class="transaction_fixed col-lg-6 col-md-6 col-sm-12">
                          <div class="form-group">
                              <label>{{ __('Fixed Amount') }}</label>
                              <input type="number" step="any" name="fix_charge" class="form-control" placeholder="Fixed Amount">
                          </div>
                        </div>
                        <div class="transaction_percentage col-lg-6 col-md-6 col-sm-12">
                          <div class="form-group">
                            <label>{{ __('Percentage Amount') }}</label>
                            <input type="number" step="any" name="percent_charge" class="form-control" placeholder="Percentage Amount">
                          </div>
                        </div>
                    </div>
                  </div><!-- end btoh -->
                  </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Payment Method Logo') }}</label>
                        <input type="file" name="logo" class="form-control">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Status') }}</label>
                            <select name="status" class="form-control">
                              <option value="">{{ __('Select Status') }}</option>
                              <option value="1">{{ __('Active') }}</option>
                              <option value="0">{{ __('Deactive') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Account Information') }}</label>
                  <textarea class="form-control" name="data" id="" cols="30" rows="10"></textarea>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="basicbtn btn btn-primary btn-lg float-right w-100">Submit</button>
                  </div>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/select2.min.js') }}"></script>
@endpush
